from __future__ import annotations

import logging
import os
from datetime import datetime
from typing import List

import psycopg
from fastapi import APIRouter
from pydantic import BaseModel, Field

router = APIRouter()
logger = logging.getLogger("moderation")
logging.basicConfig(level=logging.INFO)


def env_flag(name: str, default: str = "false") -> bool:
    return os.getenv(name, default).strip().lower() in {"1", "true", "yes", "y", "on"}


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


DATABASE_URL = os.getenv("DATABASE_URL", "").strip() or os.getenv("DB_URL", "").strip()
NEWS_ANALYTICS_ENABLED = env_flag("NEWS_ANALYTICS_ENABLED", "true")
NEWS_TRACK_COOLDOWN_SECONDS = int(os.getenv("NEWS_TRACK_COOLDOWN_SECONDS", "1800"))


def db_ready() -> bool:
    return NEWS_ANALYTICS_ENABLED and bool(DATABASE_URL)


def normalize_session_id(value: str | None) -> str | None:
    if not value:
        return None
    cleaned = value.strip()
    if not cleaned:
        return None
    return cleaned[:120]


@router.post("/news/track-view", response_model=TrackNewsViewResponse)
def track_news_view(payload: TrackNewsViewRequest) -> TrackNewsViewResponse:
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
                    FROM news_posts
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

                news_post_id = int(post_row[0])

                if session_id and NEWS_TRACK_COOLDOWN_SECONDS > 0:
                    cur.execute(
                        """
                        SELECT 1
                        FROM news_view_events
                        WHERE news_post_id = %s
                          AND session_id = %s
                          AND viewed_at >= NOW() - (%s || ' seconds')::interval
                        LIMIT 1
                        """,
                        (news_post_id, session_id, NEWS_TRACK_COOLDOWN_SECONDS),
                    )
                    if cur.fetchone():
                        return TrackNewsViewResponse(tracked=False, reason="cooldown")

                cur.execute(
                    """
                    INSERT INTO news_view_events (news_post_id, session_id, viewed_at)
                    VALUES (%s, %s, NOW())
                    """,
                    (news_post_id, session_id),
                )

            conn.commit()
    except Exception as exc:  # noqa: BLE001
        logger.warning("News track-view error: %s", exc)
        return TrackNewsViewResponse(tracked=False, reason="db_error")

    return TrackNewsViewResponse(tracked=True, reason="ok")


@router.get("/news/popular", response_model=PopularNewsResponse)
def popular_news(days: int = 30, limit: int = 5) -> PopularNewsResponse:
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
                    JOIN news_posts p ON p.id = v.news_post_id
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
