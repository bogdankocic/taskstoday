## Application Setup

Follow these steps to set up and run the application locally:

1. **Clone the repository**

```bash
git clone https://github.com/your-username/TasksToday.git
cd TasksToday
```

2. **Install PHP dependencies**

Make sure you have Composer installed. Then run:

```bash
composer install
```

3. **Build docker container**

Make sure you have Composer installed. Then run:

```bash
./vendor/bin/sail build
```

4. **Set up environment variables**

Copy the example environment file and configure it as needed:

```bash
cp .env.example .env
```

Edit the `.env` file to set your application key and database credentials.

5. **Generate application key**

```bash
./vendor/bin/sail artisan key:generate
```

6. **Run database migrations with seeder**

```bash
./vendor/bin/sail artisan migrate:seed
```

7. **Run the application**

You can start the development server with:

```bash
./vendor/bin/sail up
```

The application will be accessible at `http://localhost:8000`.

## Environment Variables Example

Below is an example of the `.env` file configuration:

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost
APP_PORT=8000

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```
