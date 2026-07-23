# SmartDisc

Monorepo with two parts:

- **SmartDisc-Application** — Vue 3 + Capacitor frontend (ships as a native iOS/Android app). Always run locally with `npm`, never in Docker.
- **SmartDisc-Server** — Symfony 8 API on FrankenPHP, with PostgreSQL. Always run in Docker.

## Prerequisites

- Docker with the Compose plugin (`docker compose ...`, v2 syntax) — for the API
- Node.js (see `engines` in `SmartDisc-Application/package.json`) — for the frontend

## Development

There are exactly two Compose files in this repo: `compose.yaml` (dev) and `compose.prod.yaml` (production). No scripts, no `.env` files to create, no extra setup — both are self-contained and ship with safe dev defaults.

From the repo root:

```bash
docker compose up --build
```

This builds and starts the Symfony API + PostgreSQL. First boot also runs the database migrations and generates a JWT keypair automatically. In a second terminal, start the frontend locally with npm (see [Frontend](#frontend) below).

Once both are running:

- App (Vite dev server, run via npm): http://localhost:5173
- API (Caddy/FrankenPHP, run via Docker): http://localhost:8083

Every value has a working default — override any of them by exporting env vars or dropping a root-level `.env` file before running `docker compose up`:

| Variable | Default |
|---|---|
| `HTTP_PORT` | `8083` |
| `APP_SECRET` | dev placeholder |
| `POSTGRES_DB` / `POSTGRES_USER` / `POSTGRES_PASSWORD` | `app` / `app` / `app` |
| `JWT_PASSPHRASE` | dev placeholder |
| `CADDY_MERCURE_JWT_SECRET` | dev placeholder |

## Frontend

The Vue app is never run in Docker — always run it locally with npm:

```bash
cd SmartDisc-Application
npm install
npm run dev
```

Copy `.env.example` to `.env` if you need to override `VITE_API_BASE_URL` (defaults to `http://localhost:8083`, matching the Dockerized API's default `HTTP_PORT`).

## Production

Same idea as dev, but `compose.prod.yaml` has no insecure defaults — it refuses to start with a clear error if a required variable is missing, instead of silently falling back to a placeholder.

Required environment variables: `SERVER_NAME`, `APP_SECRET`, `POSTGRES_PASSWORD`, `CADDY_MERCURE_JWT_SECRET`, `JWT_PASSPHRASE`. Optional (have defaults): `POSTGRES_DB`, `POSTGRES_USER`, `HTTP_PORT`.

```bash
SERVER_NAME=your-domain.example.com \
APP_SECRET=$(openssl rand -hex 16) \
POSTGRES_PASSWORD=$(openssl rand -hex 12) \
CADDY_MERCURE_JWT_SECRET=$(openssl rand -hex 16) \
JWT_PASSPHRASE=$(openssl rand -hex 16) \
docker compose -f compose.prod.yaml up -d --build
```

The JWT keypair is generated at **build time** (baked into the image, using `JWT_PASSPHRASE` as a build arg — see `SmartDisc-Server/Dockerfile`), not at container startup, so it works regardless of which user/UID actually runs the container. `SmartDisc-Application` is not served by this stack — it ships as a native Capacitor app.

### Dokploy

Works with the stock deploy command, no Custom Command override needed:

- **Compose Path**: `compose.prod.yaml`
- **Environment Variables**: set the five required variables listed above in Dokploy's UI

That's it — `docker compose -f compose.prod.yaml up -d --build` (Dokploy's default) picks everything up automatically.

## Troubleshooting

- **`docker compose -f compose.prod.yaml ...` fails immediately with `required variable X is missing a value`**: set that variable — see [Production](#production).
- **API returns 401s / JWT errors**: `JWT_PASSPHRASE` at runtime doesn't match what was used to build the image — rebuild after changing it (`docker compose -f compose.prod.yaml up -d --build`).
