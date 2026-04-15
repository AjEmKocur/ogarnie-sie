from __future__ import annotations

import json
import logging
import os
from datetime import datetime
from typing import List, Literal

import httpx
import psycopg
from fastapi import FastAPI
from pydantic import BaseModel, Field


# ============================================================
# 1) MODELE DANYCH: MODERACJA OPINII (OpenAI)
# ============================================================
class ModerateRequest(BaseModel):
    content: str = Field(min_length=1, max_length=5000)


class ModerateResponse(BaseModel):
    status: Literal["approve", "review", "reject"]
    score: int = Field(ge=0, le=100)
    reasons: List[str]


# ============================================================
# 2) MODELE DANYCH: POPULARNOSC AKTUALNOSCI (track + ranking)
# ============================================================
class TrackNewsViewRequest(BaseModel):
    slug: str = Field(min_length=1, max_length=255)
    session_id: str | None = Field(default=None, max_length=120)


class TrackNewsViewResponse(BaseModel):
    tracked: bool
    reason: str


class PopularNewsItem(BaseModel):
    slug: str
    title: str
    views: int = Field(ge=0)
    published_at: datetime | None = None
    last_viewed_at: datetime | None = None


class PopularNewsResponse(BaseModel):
    items: List[PopularNewsItem]


app = FastAPI(title="OgarnieSie Moderation API", version="2.1.0-openai-classifier")

logger = logging.getLogger("moderation")
logging.basicConfig(level=logging.INFO)


def env_flag(name: str, default: str = "false") -> bool:
    return os.getenv(name, default).strip().lower() in {"1", "true", "yes", "y", "on"}


# ============================================================
# 3) KONFIGURACJA ENV: MODERACJA + ANALITYKA AKTUALNOSCI
# ============================================================
OPENAI_API_KEY = os.getenv("OPENAI_API_KEY", "").strip()
OPENAI_ENABLED = env_flag("OPENAI_MODERATION_ENABLED", "false")
OPENAI_TIMEOUT = int(os.getenv("OPENAI_TIMEOUT_SECONDS", "12"))
DATABASE_URL = os.getenv("DATABASE_URL", "").strip() or os.getenv("DB_URL", "").strip()
NEWS_ANALYTICS_ENABLED = env_flag("NEWS_ANALYTICS_ENABLED", "true")
NEWS_TRACK_COOLDOWN_SECONDS = int(os.getenv("NEWS_TRACK_COOLDOWN_SECONDS", "1800"))

_model_override = os.getenv("OPENAI_CLASSIFIER_MODEL", "").strip()
_model_legacy = os.getenv("OPENAI_MODERATION_MODEL", "").strip()
if _model_override:
    OPENAI_MODEL = _model_override
elif _model_legacy and "moderation" not in _model_legacy.lower():
    OPENAI_MODEL = _model_legacy
else:
    OPENAI_MODEL = "gpt-4.1-mini"


# Prompt systemowy dla klasyfikatora OpenAI (moderacja opinii).
SYSTEM_PROMPT = """Jesteś klasyfikatorem moderacji opinii użytkowników po polsku.
Zwracaj WYŁĄCZNIE poprawny JSON o strukturze:
{
  "status": "approve" | "review" | "reject",
  "score": integer 0-100,
  "reasons": [string, ...]
}

Reguły decyzji:
- Jeśli tekst zawiera wulgaryzmy/profanity/obelgi (np. kurwa, chuj i odmiany, maskowane formy) => status="reject", score >= 60.
  Dotyczy to także skrótów obraźliwych i ich wariantów z separatorami, np. "jprdl", "j.p.r.d.l", "chwdp", "c.h.w.d.p", "hwdp", "h.w.d.p".
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


# ============================================================
# 4) MODERACJA OPINII: LOGIKA OPENAI + ENDPOINT /moderate
# ============================================================
def moderate_with_openai_classifier(content: str) -> ModerateResponse | None:
    # Jeśli moderacja OpenAI jest wyłączona lub brak klucza, zwracamy None.
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
    # Endpoint techniczny dla monitoringu.
    return {"status": "ok"}


@app.post("/moderate", response_model=ModerateResponse)
def moderate(payload: ModerateRequest) -> ModerateResponse:
    # Główny endpoint moderacji opinii.
    result = moderate_with_openai_classifier(payload.content)

    if result is None:
        return ModerateResponse(
            status="reject",
            score=100,
            reasons=["Sprawdzanie opinii przez AI jest chwilowo niedostępne. Spróbuj ponownie za chwilę."],
        )

    return result


# ============================================================
# 5) POPULARNOSC AKTUALNOSCI: POMOCNICZE FUNKCJE
# ============================================================
def db_ready() -> bool:
    # Analityka działa tylko, gdy jest włączona i mamy DATABASE_URL.
    return NEWS_ANALYTICS_ENABLED and bool(DATABASE_URL)


def normalize_session_id(value: str | None) -> str | None:
    # Czyścimy i skracamy session_id, by nie zapisać śmieci.
    if not value:
        return None
    cleaned = value.strip()
    if not cleaned:
        return None
    return cleaned[:120]


# ============================================================
# 6) POPULARNOSC AKTUALNOSCI: ENDPOINT /news/track-view
# ============================================================
@app.post("/news/track-view", response_model=TrackNewsViewResponse)
def track_news_view(payload: TrackNewsViewRequest) -> TrackNewsViewResponse:
    # Zapis pojedynczego wyświetlenia aktualności.
    if not db_ready():
        return TrackNewsViewResponse(tracked=False, reason="analytics_disabled")

    slug = payload.slug.strip()
    if not slug:
        return TrackNewsViewResponse(tracked=False, reason="invalid_slug")

    session_id = normalize_session_id(payload.session_id)

    try:
        with psycopg.connect(DATABASE_URL) as conn:
            with conn.cursor() as cur:
                cur.execute(
                    """
                    SELECT id
                    FROM blog_posts
                    WHERE slug = %s
                      AND is_published = TRUE
                      AND published_at IS NOT NULL
                    LIMIT 1
                    """,
                    (slug,),
                )
                post_row = cur.fetchone()
                if not post_row:
                    return TrackNewsViewResponse(tracked=False, reason="post_not_found")

                blog_post_id = int(post_row[0])

                # Cooldown: nie nabijamy wielu view od tej samej sesji co chwilę.
                if session_id and NEWS_TRACK_COOLDOWN_SECONDS > 0:
                    cur.execute(
                        """
                        SELECT 1
                        FROM news_view_events
                        WHERE blog_post_id = %s
                          AND session_id = %s
                          AND viewed_at >= NOW() - (%s || ' seconds')::interval
                        LIMIT 1
                        """,
                        (blog_post_id, session_id, NEWS_TRACK_COOLDOWN_SECONDS),
                    )
                    if cur.fetchone():
                        return TrackNewsViewResponse(tracked=False, reason="cooldown")

                cur.execute(
                    """
                    INSERT INTO news_view_events (blog_post_id, session_id, viewed_at)
                    VALUES (%s, %s, NOW())
                    """,
                    (blog_post_id, session_id),
                )

            conn.commit()
    except Exception as exc:  # noqa: BLE001
        logger.warning("News track-view error: %s", exc)
        return TrackNewsViewResponse(tracked=False, reason="db_error")

    return TrackNewsViewResponse(tracked=True, reason="ok")


# ============================================================
# 7) POPULARNOSC AKTUALNOSCI: ENDPOINT /news/popular
# ============================================================
@app.get("/news/popular", response_model=PopularNewsResponse)
def popular_news(days: int = 30, limit: int = 5) -> PopularNewsResponse:
    # Zwraca ranking najpopularniejszych aktualności w zadanym oknie czasu.
    if not db_ready():
        return PopularNewsResponse(items=[])

    safe_days = max(1, min(365, int(days)))
    safe_limit = max(1, min(20, int(limit)))

    try:
        with psycopg.connect(DATABASE_URL) as conn:
            with conn.cursor() as cur:
                cur.execute(
                    """
                    SELECT
                        p.slug,
                        p.title,
                        p.published_at,
                        MAX(v.viewed_at) AS last_viewed_at,
                        COUNT(*)::int AS views
                    FROM news_view_events v
                    JOIN blog_posts p ON p.id = v.blog_post_id
                    WHERE p.is_published = TRUE
                      AND p.published_at IS NOT NULL
                      AND v.viewed_at >= NOW() - (%s || ' days')::interval
                    GROUP BY p.id, p.slug, p.title, p.published_at
                    ORDER BY views DESC, last_viewed_at DESC
                    LIMIT %s
                    """,
                    (safe_days, safe_limit),
                )
                rows = cur.fetchall()
    except Exception as exc:  # noqa: BLE001
        logger.warning("News popular error: %s", exc)
        return PopularNewsResponse(items=[])

    items = [
        PopularNewsItem(
            slug=str(row[0]),
            title=str(row[1]),
            published_at=row[2],
            last_viewed_at=row[3],
            views=int(row[4]),
        )
        for row in rows
    ]
    return PopularNewsResponse(items=items)
