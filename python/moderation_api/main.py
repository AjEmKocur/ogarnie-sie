from __future__ import annotations

import re
from functools import lru_cache
from pathlib import Path
from typing import List, Literal

from fastapi import FastAPI
from pydantic import BaseModel, Field


class ModerateRequest(BaseModel):
    content: str = Field(min_length=1, max_length=5000)


class ModerateResponse(BaseModel):
    status: Literal["approve", "review", "reject"]
    score: int = Field(ge=0, le=100)
    reasons: List[str]


app = FastAPI(title="OgarnieSie Moderation API", version="1.2.1")

DATA_DIR = Path(__file__).resolve().parent / "data"
PROFANITY_FILE = DATA_DIR / "profanity_pl.txt"

DIACRITICS_MAP = str.maketrans(
    {
        "ą": "a",
        "ć": "c",
        "ę": "e",
        "ł": "l",
        "ń": "n",
        "ó": "o",
        "ś": "s",
        "ż": "z",
        "ź": "z",
    }
)

LEET_MAP = str.maketrans(
    {
        "@": "a",
        "$": "s",
        "€": "e",
        "0": "o",
        "1": "i",
        "3": "e",
        "4": "a",
        "5": "s",
        "7": "t",
        "!": "i",
    }
)

# Zamiany liter na cyfry do wykrywania ukrytych numerow telefonu.
DIGIT_SUBSTITUTIONS = str.maketrans(
    {
        "o": "0",
        "O": "0",
        "i": "1",
        "I": "1",
        "l": "1",
        "L": "1",
    }
)

# Kwoty w PLN (zeby nie mylic ceny z numerem telefonu).
CURRENCY_PATTERN = re.compile(
    r"\b(?:\d{1,6}|\d{1,3}(?:[ .]\d{3})+)(?:[.,]\d{1,2})?\s?(?:zł|zl|pln)\b",
    flags=re.IGNORECASE,
)


def normalize_text(text: str) -> str:
    t = text.lower().translate(DIACRITICS_MAP).translate(LEET_MAP)
    t = re.sub(r"[^a-z0-9\s]", " ", t)
    t = re.sub(r"\s+", " ", t).strip()
    return t


def compact_text(text: str) -> str:
    # Usuwa separatory typu spacja/kropka/myslnik, by lapac "k.u.r.w.a"
    return re.sub(r"[^a-z0-9]", "", text)


@lru_cache(maxsize=1)
def load_profanity_stems() -> List[str]:
    if not PROFANITY_FILE.exists():
        return []

    stems: List[str] = []
    for line in PROFANITY_FILE.read_text(encoding="utf-8").splitlines():
        raw = line.strip()
        if not raw or raw.startswith("#"):
            continue

        stem = compact_text(normalize_text(raw))
        if len(stem) >= 3:
            stems.append(stem)

    # Unikalne, dluzsze najpierw (mniej falszywych trafien)
    return sorted(set(stems), key=len, reverse=True)


def find_profanity_matches(content: str) -> List[str]:
    normalized = normalize_text(content)
    compact = compact_text(normalized)
    stems = load_profanity_stems()

    matches: List[str] = []
    for stem in stems:
        token_pattern = rf"\b{re.escape(stem)}[a-z0-9]*\b"
        compact_pattern = rf"{re.escape(stem)}[a-z0-9]*"

        if re.search(token_pattern, normalized) or re.search(compact_pattern, compact):
            matches.append(stem)

    return matches


def has_phone_like_sequence(content: str) -> bool:
    # 1) Normalizacja obejsc typu litera -> cyfra.
    normalized = content.translate(DIGIT_SUBSTITUTIONS)

    # 2) Wycinamy kwoty, np. "300,50 zl", "1200 pln", "99.99 zł".
    scrubbed = CURRENCY_PATTERN.sub(" ", normalized)

    # 3) Szukamy sekwencji wygladajacych jak telefon, także z "kombinowanymi"
    # separatorami typu "/", "_", ":", "|", "()", wieloma spacjami itp.
    for match in re.finditer(r"(?:\+?\d(?:[\s\W_]*\d){6,14})", scrubbed):
        digits = re.sub(r"\D", "", match.group(0))
        if 7 <= len(digits) <= 15:
            return True

    return False


def uppercase_ratio(text: str) -> float:
    letters = "".join(ch for ch in text if ch.isalpha())
    if not letters:
        return 0.0
    upper = "".join(ch for ch in letters if ch.isupper())
    return len(upper) / max(1, len(letters))


@app.get("/health")
def health() -> dict[str, str]:
    return {"status": "ok"}


@app.get("/dictionary-info")
def dictionary_info() -> dict[str, int]:
    stems = load_profanity_stems()
    return {"entries": len(stems)}


@app.post("/moderate", response_model=ModerateResponse)
def moderate(payload: ModerateRequest) -> ModerateResponse:
    content = payload.content
    score = 0
    reasons: List[str] = []

    profanity_matches = find_profanity_matches(content)
    if profanity_matches:
        score += 70
        reasons.append("Wykryto obraźliwe słownictwo.")

    if re.search(r"https?://|www\.", content, flags=re.IGNORECASE):
        score += 25
        reasons.append("Wykryto link w opinii.")

    if has_phone_like_sequence(content):
        score += 25
        reasons.append("Wykryto próbę podania numeru telefonu.")

    if re.search(r"(.)\1{5,}", content):
        score += 20
        reasons.append("Wykryto powtarzające się znaki (potencjalny spam).")

    if uppercase_ratio(content) > 0.6:
        score += 20
        reasons.append("Nadmierne użycie wielkich liter.")

    score = max(0, min(100, score))

    if score >= 60:
        status: Literal["approve", "review", "reject"] = "reject"
    elif score >= 25:
        status = "review"
    else:
        status = "approve"

    if status == "approve" and not reasons:
        reasons.append("Brak wykrytych ryzyk. Opinia może zostać opublikowana automatycznie.")

    return ModerateResponse(status=status, score=score, reasons=reasons)
