# SmartDisc

Monorepo with two parts:

- **SmartDisc-Application** — Vue 3 + Capacitor frontend (ships as a native iOS/Android app; served by Vite in dev only).
- **SmartDisc-Server** — Symfony 8 API on FrankenPHP, with PostgreSQL.

## Prerequisites

- Docker with the Compose plugin (`docker compose ...`, v2 syntax)

## First-time setup

Secrets and generated keys are gitignored on purpose and are **not** part of the repo, so a fresh clone needs a few manual steps before `docker compose up` works.

1. Clone the repo.

2. Create the server env file:
   ```bash
   cd SmartDisc-Server
   cp .env.example .env
   ```
   Open `.env` and set real values for `APP_SECRET`, `POSTGRES_PASSWORD`, and `JWT_PASSPHRASE` (the example file ships with placeholders only). The root `compose.yaml` reads this file via `env_file: ./SmartDisc-Server/.env` — without it, `docker compose up` fails immediately.

3. From the repo root, build and start the stack:
   ```bash
   docker compose up --build
   ```

4. In a second terminal, generate the JWT keypair (needed once — the `php` container must already be running):
   ```bash
   docker compose exec php bin/console lexik:jwt:generate-keypair
   ```
   Use the same passphrase as `JWT_PASSPHRASE` in `SmartDisc-Server/.env`. This writes `config/jwt/private.pem` and `config/jwt/public.pem`, which are also gitignored.

Once both steps are done:

- App (Vite dev server): http://localhost:5173
- API (Caddy/FrankenPHP): http://localhost:8083

Ports can be overridden with the `APP_PORT` / `HTTP_PORT` env vars (see `compose.yaml`).

## Frontend without Docker (optional)

```bash
cd SmartDisc-Application
npm install
npm run dev
```

Copy `.env.example` to `.env` if you need to override `VITE_API_BASE_URL` (defaults to `http://localhost:8083`).

## Production

Run from the repo root:

```bash
docker compose -f compose.prod.yaml build --pull --no-cache
SERVER_NAME=your-domain.example.com \
APP_SECRET=ChangeMe \
CADDY_MERCURE_JWT_SECRET=ChangeThisMercureHubJWTSecretKey \
docker compose -f compose.prod.yaml up --wait
```

This also requires `SmartDisc-Server/.env` and a generated JWT keypair, same as the dev setup above. `SmartDisc-Application` is not served by this stack — it ships as a native Capacitor app.

## Troubleshooting

- **`docker compose up` fails right after cloning**: you're missing `SmartDisc-Server/.env` — see step 2.
- **API returns 401s / JWT errors after setup**: the JWT keypair wasn't generated yet, or the passphrase doesn't match `JWT_PASSPHRASE` — see step 4.
