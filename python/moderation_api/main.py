from fastapi import FastAPI

from moderation_endpoints import router as moderation_router

app = FastAPI(title="OgarnieSie Moderation API", version="2.2.0-split-modules")


@app.get("/health")
def health() -> dict[str, str]:
    return {"status": "ok"}


app.include_router(moderation_router)
