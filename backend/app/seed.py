"""Seed data helper."""

from app.db import SessionLocal
from app.models import SeedLog


def run_seed() -> None:
    with SessionLocal() as session:
        entry = SeedLog()
        session.add(entry)
        session.commit()


if __name__ == "__main__":
    run_seed()
