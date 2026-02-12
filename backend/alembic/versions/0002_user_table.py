"""create user table"""

from alembic import op
import sqlalchemy as sa
from sqlalchemy.dialects import postgresql

revision = "0002_user_table"
down_revision = "0001_seed_log"
branch_labels = None
depends_on = None


def upgrade() -> None:
    user_role = sa.Enum("admin", "organizer", "judge", "hacker", name="user_role")
    user_role.create(op.get_bind(), checkfirst=True)

    op.create_table(
        "user",
        sa.Column("id", postgresql.UUID(as_uuid=True), primary_key=True, nullable=False),
        sa.Column("email", sa.String(length=320), nullable=False),
        sa.Column("password_hash", sa.String(length=255), nullable=True),
        sa.Column("role", user_role, nullable=False, server_default="hacker"),
        sa.Column("is_active", sa.Boolean(), nullable=False, server_default=sa.true()),
        sa.Column("created_at", sa.DateTime(timezone=True), server_default=sa.func.now(), nullable=False),
        sa.UniqueConstraint("email", name="uq_user_email"),
    )
    op.create_index("ix_user_email", "user", ["email"], unique=True)


def downgrade() -> None:
    op.drop_index("ix_user_email", table_name="user")
    op.drop_table("user")
    sa.Enum(name="user_role").drop(op.get_bind(), checkfirst=True)
