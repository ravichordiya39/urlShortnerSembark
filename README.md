# URL Shortener - Laravel

A Laravel based URL Shortener application with Role Based Access Control
(RBAC), permissions, company management and short URL generation.

## Features

-   User Authentication
-   Role Management
-   Permission Management
-   Company Management
-   Short URL Creation
-   Admin Dashboard
-   Role Permission Based Access

## Requirements

-   PHP \>= 8.1
-   Composer
-   MySQL

Check versions:

``` bash
php -v
composer -V
```

## Installation

### Clone Repository

``` bash
git clone https://github.com/ravichordiya39/urlShortnerSembark.git
cd urlShortnerSembark
```

### Install Dependencies

``` bash
composer install
```


## Environment Setup

Copy environment file:

``` bash
cp .env.example .env
```

Generate key:

``` bash
php artisan key:generate
```

## Database Setup

Create database:

    url_shortner

Update `.env`:

``` env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortner
DB_USERNAME=root
DB_PASSWORD=
```

## Migration

Run migrations:

``` bash
php artisan migrate
```

## Default Data

Run seeders:

``` bash
php artisan db:seed SuperAdminSeeder
```

Super Admin:

    Email:
    suer@eadmin.com

    Password:
    12345678

## Storage Link

``` bash
php artisan storage:link
```

## Run Application

``` bash
php artisan serve
```

Open:

    http://127.0.0.1:8000

## Permissions

Available permissions:

    user_list
    user_create
    user_edit
    user_delete

    role_list
    role_create
    role_edit
    role_delete

    permission_list
    permission_create
    permission_edit
    permission_delete

    short_url_list
    short_url_create
    short_url_edit
    short_url_delete

    company_list
    company_create
    company_edit
    company_delete

## Clear Cache

``` bash
php artisan optimize:clear
```

## Project Structure

    app
     ├── Models
     ├── Http
     │    ├── Controllers
     │    └── Middleware

    database
     ├── migrations
     └── seeders

    resources
     └── views

    routes
     └── web.php

