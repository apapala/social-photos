Social Photos
==========================
Simple Symfony 3.4 application.

## Setup project

Clone repository


Use composer to download dependencies
```
cd app
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

Run docker compose
```
docker-compose up -d
```

Add url to hosts file
```
social-photos.local
```

Determine if all containers are running
```
docker ps
```

Run migration and fixtures

```
cd app
make db-update
php bin/console doctrine:fixtures:load --append
```
Create account. Login. Enjoy.

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
