<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## About The Current Proyect

The current project deals with the administration of the categories,
Events.
In addition to selling tickets.

- CRUD Events.
- CRUD Categories.
- Selling tickets.
- Send an e-mail.

---
## Installation

- Prerequisites
    - PhP >= 7.4
    - Install Composer
    - Git
---
- Run 

```
git clone https://github.com/guez/prueba_tecnica.git
```
```
cp .env.example .env
```

- Set the environment variables file .env
```
APP_NAME="EVENTOS"
APP_ENV=local
APP_KEY=base64:2QJj09mcyWJX8Yqy5UYG4GNTRoPxEJMdzR2rHDVelNc=
APP_DEBUG=true
APP_URL=http://pp.test


LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= (write the name database)
DB_USERNAME= (write the name user)
DB_PASSWORD= (write the password)


BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=(config the provider)
MAIL_PORT=(config the provider)
MAIL_USERNAME=(config the provider)
MAIL_PASSWORD=(config the provider)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=(config the provider)
MAIL_FROM_NAME="COMPRA DE ENTRADAS PARA EVENTO"


AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

```


- We continue with the generation of the key
```
php artisan key:generate
```
- We start the installation of the dependencies
```
composer install
```
- We create the Database in the Mysql manager (REQUERED)

- We begin the installation of migrations and seeds, for this we have created a command to facilitate the work
```
php artisan install:all
```



