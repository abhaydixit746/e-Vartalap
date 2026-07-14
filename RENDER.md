# Render deployment

This package defines two Render services in `render.yaml`:

1. `evartalap-mysql` - MySQL 8 private service with a persistent disk.
2. `e-vartalap` - PHP 8.2 + Apache web service.

The app receives the MySQL private hostname and generated password through Render Blueprint environment references.

## Deploy

1. Replace the files in your GitHub repository with this package.
2. Commit and push to GitHub.
3. In Render, create a **Blueprint** from the repository. Do not create only a standalone Web Service.
4. Render reads `render.yaml` and creates both services.
5. Wait for `evartalap-mysql` to become available and for `e-vartalap` to finish deploying.
6. Open `https://e-vartalap.onrender.com`.

## Existing standalone Render service

If you already created `e-vartalap` manually, delete the old standalone service first or use a different service name in `render.yaml`. The Blueprint must manage the app and database configuration together.

## Database initialization

`Dockerfile.mysql` copies `database/schema.sql` into MySQL's `/docker-entrypoint-initdb.d/`.
MySQL runs this SQL automatically only when `/var/lib/mysql` is initialized for the first time.

To reinitialize from scratch, delete the MySQL service/disk and recreate the Blueprint. This destroys database data.

## Important cost note

Render private services do not support the Free instance type. The MySQL service uses the `starter` plan and a persistent disk. Review Render pricing before creating the Blueprint.

## Uploaded photos

The web service's filesystem is ephemeral on the Free plan. Uploaded profile photos can be lost after redeploy/restart. For production, move uploads to object storage or attach a persistent disk to a paid web service.
