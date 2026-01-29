# hackathon-nexus

Monorepo with a Vite + React frontend and a FastAPI backend.

## Requirements
- Docker + Docker Compose
- Optional: Node.js 20+, Python 3.12 (for running locally without Docker)

## Quickstart (Docker)
```bash
make up
```

Backend: http://localhost:8000
Frontend: http://localhost:5173

## Database migrations
```bash
make migrate
```

## Seed data
```bash
make seed
```

## Stop services
```bash
make down
```

## View logs
```bash
make logs
```

## Local development (optional)
Backend:
```bash
cd backend
python -m venv .venv
. .venv/Scripts/activate
pip install -r requirements.txt -r requirements-dev.txt
uvicorn app.main:app --reload
```

Frontend:
```bash
cd frontend
npm install
npm run dev
```

## Tests
```bash
cd backend
pytest
```
