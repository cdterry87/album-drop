# Album Drop

## Installation

-   Run git clone the project and cd into the directory
-   Run `cp .env.example .env` to create the .env file, then update the DB, MAIL, and SPOTIFY variables like below

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=albumdrop
DB_USERNAME=sail
DB_PASSWORD=password

MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=YOUR_SENDGRID_API_KEY_HERE
MAIL_ENCRYPTION=tls
MAIL_FROM_NAME="${APP_NAME}"
MAIL_FROM_ADDRESS=YOUR_VERIFIED_SENDGRID_EMAIL_SENDER_ADDRESS

SPOTIFY_CLIENT_ID=YOUR_SPOTIFY_CLIENT_ID
SPOTIFY_CLIENT_SECRET=YOUR_SPOTIFY_CLIENT_SECRET
SPOTIFY_REDIRECT_URI="http://localhost/auth/spotify/callback"
```

-   Run the following command to install dependencies

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

-   Run `sail up -d` to start the container
-   Run `sail npm install`
-   Run `sail artisan key:generate`
-   Run `sail artisan migrate:fresh --seed`
-   Run `sail npm run dev` to build front end assets
-   Go to localhost in your browser to view the app
