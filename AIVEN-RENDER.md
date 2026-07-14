# Render + Aiven deployment

This version is configured for a free Render PHP web service and an external Aiven MySQL database.

## Render environment variables

Set these on the existing `e-Vartalap` Render Web Service:

APP_ENV=production
APP_URL=https://e-vartalap.onrender.com
AUTO_INIT_DB=true
DB_HOST=<Aiven Host>
DB_PORT=<Aiven Port>
DB_NAME=defaultdb
DB_USER=avnadmin
DB_PASSWORD=<Aiven Password>
DB_SSL_MODE=verify-ca
DB_SSL_CA_CERT=<paste the full Aiven CA certificate PEM>

For `DB_SSL_CA_CERT`, in Aiven open the MySQL service Overview page, show/download the CA certificate, copy the complete PEM text including:

-----BEGIN CERTIFICATE-----
...
-----END CERTIFICATE-----

Paste the entire certificate as a multiline Render secret value.

## Database initialization

On each container start, `scripts/init-db.php` checks whether the `users` table exists.

- If it does not exist, `database/schema-aiven.sql` is imported into `defaultdb`.
- If it already exists, initialization is skipped.

This avoids duplicate seed inserts during Render redeploys.

## Deploy

Replace your repository files with this package, then run:

git add .
git commit -m "Add Aiven SSL and Render database initialization"
git push origin main

Render should automatically redeploy. If not, choose Manual Deploy > Deploy latest commit.

## Security

Never commit the Aiven password to GitHub.
Do not send the password or full CA secret in chat.
