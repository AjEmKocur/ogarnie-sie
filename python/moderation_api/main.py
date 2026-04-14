from __future__ import annotations

import json
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


app = FastAPI(title="OgarnieSie Moderation API", version="2.1.0-openai-classifier")

logger = logging.getLogger("moderation")
logging.basicConfig(level=logging.INFO)


def env_flag(name: str, default: str = "false") -> bool:
    return os.getenv(name, default).strip().lower() in {"1", "true", "yes", "y", "on"}


OPENAI_API_KEY = os.getenv("OPENAI_API_KEY", "").strip()
OPENAI_ENABLED = env_flag("OPENAI_MODERATION_ENABLED", "false")
OPENAI_TIMEOUT = int(os.getenv("OPENAI_TIMEOUT_SECONDS", "12"))

_model_override = os.getenv("OPENAI_CLASSIFIER_MODEL", "").strip()
_model_legacy = os.getenv("OPENAI_MODERATION_MODEL", "").strip()
if _model_override:
    OPENAI_MODEL = _model_override
elif _model_legacy and "moderation" not in _model_legacy.lower():
    OPENAI_MODEL = _model_legacy
else:
    OPENAI_MODEL = "gpt-4.1-mini"


SYSTEM_PROMPT = """Jesteś klasyfikatorem moderacji opinii użytkowników po polsku.
Zwracaj WYŁĄCZNIE poprawny JSON o strukturze:
{
  "status": "approve" | "review" | "reject",
  "score": integer 0-100,
  "reasons": [string, ...]
}

Reguły decyzji:
- Jeśli tekst zawiera wulgaryzmy/profanity/obelgi (np. kurwa, chuj i odmiany, maskowane formy) => status="reject", score >= 60.
- Jeśli tekst zawiera dane kontaktowe (telefon, email, link, social handle) => status="reject", score >= 60.
  Traktuj jako dane kontaktowe także formy maskowane/obfuskowane, np.:
  - numery z separatorami: 5.4.3-0-9-9.3.2.1, 5 4 3 0 9 9 3 2 1, 543-099-321
  - sekwencje cyfr rozdzielane mieszanymi separatorami (.,-,:,;,/,_,spacja,nawiasy), np. 8,56-73,6.2.3.2
  - jeśli w jednym fragmencie tekstu da się złożyć 7+ cyfr rozdzielonych separatorami, traktuj to jako kontakt
  - numer podany słownie (np. "osiem pięć sześć siedem trzy..." lub "pięć-cztery-trzy..."), także traktuj jako kontakt
  - zapisy typu "dzwoń", "pisz", "dajcie do mnie", "odezwij się", gdy obok jest kontakt
- Nie traktuj jako kontaktu samych kwot/cen, np. "50,50 zł", "30.45", "za 120 PLN", o ile brak innych sygnałów kontaktowych.
  Cena to zwykle 1 liczba (ew. z 1 separatorem dziesiętnym), a nie długi ciąg wielu grup cyfr.
- Jeśli tekst zawiera spam/promocję bez danych kontaktowych => status="review", score 25-59.
- Jeśli tekst zachęca do kontaktu/oferty poza platformą lub zawiera obchodzenie moderacji => status="reject", score >= 60.
- Jeśli tekst jest neutralny i bez powyższych ryzyk => status="approve", score 0-24.

Zawsze podaj co najmniej 1 reason.
Nie dodawaj żadnego tekstu poza JSON.
"""


def moderate_with_openai_classifier(content: str) -> ModerateResponse | None:
    if not (OPENAI_ENABLED and OPENAI_API_KEY):
        return None

    payload = {
        "model": OPENAI_MODEL,
        "temperature": 0,
        "response_format": {"type": "json_object"},
        "messages": [
            {"role": "system", "content": SYSTEM_PROMPT},
            {"role": "user", "content": content},
        ],
    }
    headers = {
        "Authorization": f"Bearer {OPENAI_API_KEY}",
        "Content-Type": "application/json",
    }

    try:
        with httpx.Client(timeout=OPENAI_TIMEOUT) as client:
            response = client.post("https://api.openai.com/v1/chat/completions", json=payload, headers=headers)
    except Exception as exc:  # noqa: BLE001
        logger.warning("OpenAI classifier error: %s", exc)
        return None

    if response.status_code >= 400:
        logger.warning("OpenAI classifier non-OK: %s %s", response.status_code, response.text)
        return None

    try:
        data = response.json()
        content_json = ((data.get("choices") or [{}])[0].get("message") or {}).get("content", "")
        parsed = json.loads(content_json)
    except Exception as exc:  # noqa: BLE001
        logger.warning("OpenAI classifier invalid JSON output: %s", exc)
        return None

    status = str(parsed.get("status", "")).strip().lower()
    if status not in {"approve", "review", "reject"}:
        return None

    try:
        score = int(parsed.get("score", 0))
    except (TypeError, ValueError):
        score = 0

    reasons_raw = parsed.get("reasons", [])
    if not isinstance(reasons_raw, list):
        reasons_raw = ["Brak szczegółowego uzasadnienia z AI."]

    reasons = [str(item).strip() for item in reasons_raw if str(item).strip()]
    if not reasons:
        reasons = ["Decyzja automatycznej moderacji AI."]

    return ModerateResponse(status=status, score=max(0, min(100, score)), reasons=reasons)


@app.get("/health")
def health() -> dict[str, str]:
    return {"status": "ok"}


@app.post("/moderate", response_model=ModerateResponse)
def moderate(payload: ModerateRequest) -> ModerateResponse:
    result = moderate_with_openai_classifier(payload.content)

    if result is None:
        return ModerateResponse(
            status="reject",
            score=100,
            reasons=["Sprawdzanie opinii przez AI jest chwilowo niedostępne. Spróbuj ponownie za chwilę."],
        )

    return result
