# FusionAuth Laravel Quickstart

## Documentation

This repository is documented at https://fusionauth.io/docs/quickstarts/quickstart-php-laravel-web.

Further reading:
- [Laravel Socialite concepts](https://laravel.com/docs/10.x/socialite)
- [FusionAuth OAuth Docs](https://fusionauth.io/docs/v1/tech/oauth/endpoints)

## Prerequisites

- Docker version 20 or later.

## How To Run

In a terminal run the following to start FusionAuth and Laravel.

```shell
git clone https://github.com/FusionAuth/fusionauth-quickstart-php-laravel-web.git
cd fusionauth-quickstart-php-laravel-web/complete-application
docker-compose up -d
docker compose exec lara_app composer install
docker compose exec lara_app php artisan migrate
```

Browse to the app at http://localhost:3000.