# Deploying Laravel to Vercel + Neon

This project is prepared for PostgreSQL-first deployment on Vercel using Neon.

## 0. Vercel project settings

The Vercel Root Directory must be the repository/project root, not `public`.
Leave Root Directory empty unless this app lives in a monorepo subfolder.

If Root Directory is set to `public`, Vercel treats `public/index.php` as a
static file and the browser downloads it instead of executing Laravel. The
root `vercel.json`, `composer.json`, `api/index.php`, `app`, `bootstrap`, and
`vendor` install step are all required for the PHP serverless runtime.

Recommended settings:

```text
Framework Preset: Other
Root Directory: ./ / repository root
Install Command: from vercel.json
Build Command: from vercel.json
Output Directory: public
```

## 1. Neon database

Create a Neon project and copy both connection strings:

- `DATABASE_URL`: pooled connection string, use this for the Laravel app on Vercel.
- `DATABASE_URL_UNPOOLED`: direct connection string, use this for migrations and one-off maintenance.

Neon URLs must include SSL:

```text
postgresql://USER:PASSWORD@HOST/DATABASE?sslmode=require
```

## 2. Vercel environment variables

Set these variables in Vercel Project Settings for Production and Preview:

```env
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:your-existing-laravel-app-key
APP_DEBUG=false
APP_URL=https://your-vercel-domain.vercel.app

DB_CONNECTION=pgsql
DB_URL=${DATABASE_URL}
DATABASE_URL=postgresql://USER:PASSWORD@HOST/DATABASE?sslmode=require
DATABASE_URL_UNPOOLED=postgresql://USER:PASSWORD@DIRECT_HOST/DATABASE?sslmode=require
DB_SSLMODE=require
DB_SCHEMA=public

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
FILESYSTEM_DISK=local

GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URL=https://your-vercel-domain.vercel.app/auth/google/callback
```

If you use the Neon Vercel integration, Vercel may inject `DATABASE_URL`,
`DATABASE_URL_UNPOOLED`, and `POSTGRES_URL` automatically. Laravel is configured
to read `DB_URL` first, then `DATABASE_URL`, then `POSTGRES_URL`.

## 3. Google OAuth

In Google Cloud Console, add the production callback:

```text
https://your-vercel-domain.vercel.app/auth/google/callback
```

Keep the local callback too:

```text
http://127.0.0.1:8000/auth/google/callback
```

## 4. Run migrations

Run migrations once against the direct Neon URL:

```bash
DATABASE_URL="$DATABASE_URL_UNPOOLED" DB_CONNECTION=pgsql DB_SSLMODE=require php artisan migrate --force
```

Then seed only if you intentionally want demo data in that database:

```bash
DATABASE_URL="$DATABASE_URL_UNPOOLED" DB_CONNECTION=pgsql DB_SSLMODE=require php artisan db:seed --force
```

For local migration tests with a local PostgreSQL server:

```bash
DB_CONNECTION=pgsql DB_HOST=127.0.0.1 DB_PORT=5432 DB_DATABASE=laravel DB_USERNAME=postgres php artisan migrate:fresh --seed
```

## 5. Vercel deploy

The project root contains:

- `vercel.json`: Vercel routing, build commands, and PHP runtime settings.
- `api/index.php`: serverless Laravel entrypoint.
- `api/php.ini`: PHP limits for the function.
- `.vercelignore`: excludes local secrets and local build/runtime folders.

Deploy from the project root:

```bash
vercel
vercel --prod
```

After changing Root Directory from `public` to the project root, trigger a fresh
deployment from Vercel. A redeploy is required because the previous deployment
was built as a static `public` folder.

## 6. Important serverless notes

Vercel functions have a read-only project filesystem. Runtime Laravel storage is
redirected to `/tmp` in `api/index.php`. Sessions, cache, and queues are configured
to use the database, which is safe for serverless requests.

Product images currently use local/public storage paths. For production uploads,
move images to S3/R2/Cloudinary or commit static demo images into `public`.
