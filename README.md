## Instrukcije za lokalni setup

Pratiti sledeće korake za pokretanje aplikacije na lokalnoj mašini:

### 1. Klonirati repozitorijum

```bash
git clone https://github.com/your-username/TasksToday.git
cd TasksToday
```

### 2. Instalirati dependecy-je

Postarati se da je composer instaliran. Pa pokrenuti

```bash
composer install
```

### 2. Docker kontejner

Izbildovati docker kontejner

```bash
./vendor/bin/sail build
```

### 4. Environment variable

Kopirati `.env.example` i konfigurisati po potrebi.

```bash
cp .env.example .env
```

### 5. Generisati application ključ

```bash
./vendor/bin/sail artisan key:generate
```

### 6. Pokrenuti migracije sa seeder-ima

```bash
./vendor/bin/sail artisan migrate:seed
```

### 7. Pokrenuti aplikaciju

```bash
./vendor/bin/sail up
```

Aplikacija će biti dostupna na `http://localhost:8000`.

### 8. Environment variable primer

Ispod je primer `env` fajla:

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:i0Xiqij4tlrnWMq523e0U7FjtygFwcyrb/0TkQE7mWc=
APP_DEBUG=true
APP_URL=http://localhost
APP_PORT=8000

FRONTEND_URL=http://localhost:5175

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tasks-today
DB_USERNAME=sail
DB_PASSWORD=password

SESSION_DRIVER=cookie
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_FROM_ADDRESS=no-reply@taskstoday.com
MAIL_FROM_NAME="${APP_NAME}"
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

```
