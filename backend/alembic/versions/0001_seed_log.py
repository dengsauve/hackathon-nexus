"""create seed_log table"""

from alembic import op
import sqlalchemy as sa

revision = "0001_seed_log"
down_revision = None
branch_labels = None
depends_on = None


def upgrade() -> None:
    op.create_table(
        "seed_log",
        sa.Column("id", sa.Integer(), primary_key=True, nullable=False),
        sa.Column("inserted_at", sa.DateTime(timezone=True), server_default=sa.func.now(), nullable=False),
    )


def downgrade() -> None:
    op.drop_table("seed_log")
