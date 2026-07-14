# e-Vartalap Docker Setup

## Start the application

From the project root, run:

```bash
docker compose up --build
```

Open the application at `http://localhost:8080`.

MySQL is exposed on host port `3307` and the PHP application connects to the database using the Docker service name `db`.

## Stop containers

```bash
docker compose down
```

## Reset database and start fresh

The schema is automatically imported only when the MySQL data volume is first created.

```bash
docker compose down -v
docker compose up --build
```

## View logs

```bash
docker compose logs -f app
docker compose logs -f db
```

## Notes

- Docker configuration is read from environment variables in `docker-compose.yml`.
- XAMPP/local defaults still work when Docker environment variables are absent.
- Change the example database passwords before using this setup in production.
