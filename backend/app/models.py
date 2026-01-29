from sqlalchemy import DateTime, Integer, func
from sqlalchemy.orm import Mapped, mapped_column

from app.db import Base


class SeedLog(Base):
    __tablename__ = "seed_log"

    id: Mapped[int] = mapped_column(Integer, primary_key=True)
    inserted_at: Mapped = mapped_column(DateTime(timezone=True), server_default=func.now(), nullable=False)
