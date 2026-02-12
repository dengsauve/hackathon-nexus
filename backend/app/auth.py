import json
import urllib.parse
import urllib.request
from typing import Any

from fastapi import APIRouter, Depends, HTTPException, Query, status
from sqlalchemy import select
from sqlalchemy.orm import Session

from app.config import Settings, get_settings
from app.db import get_db
from app.models import User

GOOGLE_AUTH_URL = "https://accounts.google.com/o/oauth2/v2/auth"
GOOGLE_TOKEN_URL = "https://oauth2.googleapis.com/token"
GOOGLE_TOKEN_INFO_URL = "https://oauth2.googleapis.com/tokeninfo"

router = APIRouter(prefix="/auth/google", tags=["auth"])


def _post_form(url: str, form_data: dict[str, str]) -> dict[str, Any]:
    encoded = urllib.parse.urlencode(form_data).encode("utf-8")
    request = urllib.request.Request(url, data=encoded, method="POST")
    request.add_header("Content-Type", "application/x-www-form-urlencoded")
    with urllib.request.urlopen(request, timeout=10) as response:
        return json.loads(response.read().decode("utf-8"))


def _get_json(url: str, query_params: dict[str, str]) -> dict[str, Any]:
    query = urllib.parse.urlencode(query_params)
    with urllib.request.urlopen(f"{url}?{query}", timeout=10) as response:
        return json.loads(response.read().decode("utf-8"))


def _require_google_config(settings: Settings) -> None:
    if not settings.google_client_id or not settings.google_client_secret:
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail="Google OAuth is not configured. Set GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET.",
        )


def _resolve_redirect_uri(settings: Settings, redirect_uri: str | None) -> str:
    if redirect_uri is None:
        return settings.google_redirect_uri
    value = redirect_uri.strip()
    return value or settings.google_redirect_uri


@router.get("/login")
def google_login(
    settings: Settings = Depends(get_settings),
    state: str | None = Query(default=None),
    redirect_uri: str | None = Query(default=None),
):
    _require_google_config(settings)
    resolved_redirect_uri = _resolve_redirect_uri(settings, redirect_uri)
    query_params = {
        "client_id": settings.google_client_id,
        "redirect_uri": resolved_redirect_uri,
        "response_type": "code",
        "scope": "openid email profile",
        "access_type": "offline",
        "prompt": "consent",
    }
    if state:
        query_params["state"] = state
    authorization_url = f"{GOOGLE_AUTH_URL}?{urllib.parse.urlencode(query_params)}"
    return {"authorization_url": authorization_url}


@router.get("/callback")
def google_callback(
    code: str = Query(...),
    redirect_uri: str | None = Query(default=None),
    db: Session = Depends(get_db),
    settings: Settings = Depends(get_settings),
):
    _require_google_config(settings)
    resolved_redirect_uri = _resolve_redirect_uri(settings, redirect_uri)
    try:
        token_payload = _post_form(
            GOOGLE_TOKEN_URL,
            {
                "code": code,
                "client_id": settings.google_client_id,
                "client_secret": settings.google_client_secret,
                "redirect_uri": resolved_redirect_uri,
                "grant_type": "authorization_code",
            },
        )
    except Exception as exc:
        raise HTTPException(status_code=status.HTTP_400_BAD_REQUEST, detail="Failed to exchange code.") from exc

    id_token = token_payload.get("id_token")
    if not id_token:
        raise HTTPException(status_code=status.HTTP_400_BAD_REQUEST, detail="Missing id_token from Google.")

    try:
        token_info = _get_json(GOOGLE_TOKEN_INFO_URL, {"id_token": id_token})
    except Exception as exc:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED, detail="Google token validation failed."
        ) from exc

    if token_info.get("aud") != settings.google_client_id:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Google token audience mismatch.")
    if token_info.get("email_verified") != "true":
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Google email is not verified.")

    email = str(token_info.get("email", "")).strip().lower()
    if not email:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Google account email missing.")

    existing_user = db.scalar(select(User).where(User.email == email))
    created = False
    if existing_user is None:
        existing_user = User(email=email, password_hash=None)
        db.add(existing_user)
        db.commit()
        db.refresh(existing_user)
        created = True
    elif not existing_user.is_active:
        raise HTTPException(status_code=status.HTTP_403_FORBIDDEN, detail="User account is inactive.")

    return {
        "user": {
            "id": str(existing_user.id),
            "email": existing_user.email,
            "role": existing_user.role.value,
            "is_active": existing_user.is_active,
            "created_at": existing_user.created_at.isoformat(),
        },
        "is_new_user": created,
    }
