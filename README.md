Social Photos
==========================
Simple Symfony 3.4 application.

## Setup project

Clone repository

Run docker compose
```
docker-compose up -d
```
Sometimes at first runs I need to run this twice

Add url to hosts file
```
social-photos.local
```

Determine if all containers are running
```
docker ps
```

Exec into php container

```
cd app
make exec-php
```

Install dependencies
```
composer install
```

Use these details in parameters.yml
```
database_host: social_photos_mysql
database_port: 3306
database_name: social_photos_01
database_user: root
database_password: root
```

Create DB schema and populate with fixtures
```
make db-update
php bin/console doctrine:fixtures:load --append
```

Visit social-photos.local. Create account. Login. Enjoy.

## Access other serviecs

#### Mailcatcher 
Available at 1080 port 

#### PHPMyAdmin
Available at 8080 port
Use these credentials:
```
server: social_photos_mysql
user: root
password: root
```
