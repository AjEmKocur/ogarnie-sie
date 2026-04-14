from __future__ import annotations

import logging
import os
from typing import List, Literal

import httpx
from fastapi import FastAPI
from pydantic import BaseModel, Field


class ModerateRequest(BaseModel):
    content: str = Field(min_length=1, max_length=5000)


class ModerateResponse(BaseModel):
    status: Literal["approve", "review", "reject"]
    score: int = Field(ge=0, le=100)
    reasons: List[str]


app = FastAPI(title="OgarnieSie Moderation API", version="2.0.0-openai-only")

logger = logging.getLogger("moderation")
logging.basicConfig(level=logging.INFO)


OPENAI_API_KEY = os.getenv("OPENAI_API_KEY", "").strip()
OPENAI_ENABLED = os.getenv("OPENAI_MODERATION_ENABLED", "false").strip().lower() in {"1", "true", "yes", "y", "on"}
OPENAI_MODEL = os.getenv("OPENAI_MODERATION_MODEL", "omni-moderation-latest").strip() or "omni-moderation-latest"
OPENAI_TIMEOUT = int(os.getenv("OPENAI_TIMEOUT_SECONDS", "12"))


def status_from_score(score: int) -> Literal["approve", "review", "reject"]:
    if score >= 60:
        return "reject"
    if score >= 25:
        return "review"
    return "approve"


def openai_reasons(categories: dict) -> List[str]:
    labels = {
        "harassment": "Wykryto treści nękające.",
        "harassment/threatening": "Wykryto treści nękające z groźbami.",
        "hate": "Wykryto mowę nienawiści.",
        "hate/threatening": "Wykryto mowę nienawiści z groźbami.",
        "sexual": "Wykryto treści seksualne.",
        "sexual/minors": "Wykryto treści seksualne z udziałem nieletnich.",
        "violence": "Wykryto treści o przemocy.",
        "violence/graphic": "Wykryto drastyczne treści przemocy.",
        "self-harm": "Wykryto treści o samookaleczeniu.",
        "self-harm/intent": "Wykryto intencje samookaleczenia.",
        "self-harm/instructions": "Wykryto instrukcje samookaleczenia.",
        "illicit": "Wykryto treści o działaniach nielegalnych.",
        "illicit/violent": "Wykryto treści o przemocy w kontekście działań nielegalnych.",
    }

    reasons: List[str] = []
    for key, value in categories.items():
        if value is True:
            reasons.append(labels.get(key, f"Wykryto ryzykowną kategorię: {key}."))

    return reasons


def openai_score(category_scores: dict) -> int:
    max_score = 0.0
    for value in category_scores.values():
        try:
            max_score = max(max_score, float(value))
        except (TypeError, ValueError):
            continue
    return int(round(max_score * 100))


def moderate_with_openai(content: str) -> tuple[int, List[str], bool] | None:
    if not (OPENAI_ENABLED and OPENAI_API_KEY):
        return None

    payload = {"model": OPENAI_MODEL, "input": content}
    headers = {"Authorization": f"Bearer {OPENAI_API_KEY}"}

    try:
        with httpx.Client(timeout=OPENAI_TIMEOUT) as client:
            response = client.post("https://api.openai.com/v1/moderations", json=payload, headers=headers)
    except Exception as exc:  # noqa: BLE001
        logger.warning("OpenAI moderation error: %s", exc)
        return None

    if response.status_code >= 400:
        logger.warning("OpenAI moderation non-OK: %s %s", response.status_code, response.text)
        return None

    data = response.json()
    result = (data.get("results") or [{}])[0]
    categories = result.get("categories") or {}
    category_scores = result.get("category_scores") or {}
    flagged = bool(result.get("flagged", False))

    score = openai_score(category_scores)
    reasons = openai_reasons(categories)
    return score, reasons, flagged


@app.get("/health")
def health() -> dict[str, str]:
    return {"status": "ok"}


@app.post("/moderate", response_model=ModerateResponse)
def moderate(payload: ModerateRequest) -> ModerateResponse:
    openai = moderate_with_openai(payload.content)

    if openai is None:
        return ModerateResponse(
            status="reject",
            score=100,
            reasons=["Sprawdzanie opinii przez AI jest chwilowo niedostępne. Spróbuj ponownie za chwilę."],
        )

    score, reasons, flagged = openai
    if flagged:
        score = max(score, 60)

    status: Literal["approve", "review", "reject"] = status_from_score(score)

    if status == "approve" and not reasons:
        reasons.append("Brak wykrytych ryzyk. Opinia może zostać opublikowana automatycznie.")

    return ModerateResponse(status=status, score=min(100, max(0, score)), reasons=reasons)
