# Quickstart: PHP app with FusionAuth

This repo holds an example PHP application that uses FusionAuth as the identity provider.

This repository is documented at https://fusionauth.io/docs/quickstarts/quickstart-php-web.

Further reading:
- [PHP OAuth provider](https://github.com/jerryhopper/oauth2-fusionauth)
- [FusionAuth OAuth Docs](https://fusionauth.io/docs/v1/tech/oauth/endpoints)

## Project Contents

The `docker-compose.yml` file and the `kickstart` directory are used to start and configure a local FusionAuth server.

The `complete-application` directory contains a fully working version of the application.

## Prerequisites

- [PHP](https://www.php.net/downloads.php) 8+
- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com) version 20 or later.

## Running FusionAuth

To run FusionAuth, just stand up the docker containers using docker-compose. 

First clone the example repo and change into the project directory:

```shell
git clone https://github.com/FusionAuth/fusionauth-quickstart-php-web.git
cd fusionauth-quickstart-php-web/complete-application
composer install
```

Start the containers with docker compose.

```shell
docker compose up
```

This will start a PHP container, PostgreSQL, Opensearch and the FusionAuth server.

FusionAuth will initially be configured with these settings:

* Your client id is: `e9fdb985-9173-4e01-9d73-ac2d60d1dc8e`
* Your client secret is: `super-secret-secret-that-should-be-regenerated-for-production`
* Your example username is `richard@example.com` and your password is `password`.
* Your admin username is `admin@example.com` and your password is `password`.
* Your fusionAuthBaseUrl is 'http://localhost:9011/'

You can log into the [FusionAuth admin UI](http://localhost:9011/admin) and look around if you want, but with Docker/Kickstart you don't need to.

## Running the Example Application

If you followed the steps above the PHP application container will already be running and you can access it at http://localhost:9012 else go into the complete project directory

```shell
cd complete-application
composer install
```

Start up the application docker containers with the following

```shell
docker compose up
```

Browse to the app at http://localhost:9012 and login with `richard@example.com` and `password`.

Follow the tutorial at https://fusionauth.io/docs/quickstarts/quickstart-php-web to learn how to configure PHP to work with FusionAuth.