# Tirpitz

This project is build in Laravel framework and exposes the API needed by the "Bismarck" project.

## Installation

Use [docker](https://www.docker.com/get-started) to install tirpitz.

```bash
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan jwt:secret
docker-compose exec app php artisan migrate:fresh
docker-compose exec app composer dump-autoload
docker-compose exec app php artisan db:seed
```

## Public API Routes
```bash
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout
POST /api/auth/forgot-password
POST /api/auth/validate-password-reset
POST /api/auth/reset-password

GET /api/users
GET /api/users/{id}
GET /api/users/{id}/devices
GET /api/users/{id}/projects
GET /api/users/{id}/time-off
GET /api/users/{id}/feedback
GET /api/users/{user_id}/devices/{device_id}
GET /api/users/{user_id}/projects/{project_id}
POST /api/users/{id}/devices
PATCH /api/users/{id}
DELETE /api/users/{id}

GET /api/devices
POST /api/devices
GET /api/devices/{id}
PATCH /api/devices/{id}
DELETE /api/devices/{id}

GET /api/projects
POST /api/projects
GET /api/projects/{id}
PATCH /api/projects/{id}
DELETE /api/projects/{id}

GET /api/departments
POST /api/departments
GET /api/departments/{id}
PATCH /api/departments/{id}
DELETE /api/departments/{id}
```

### Resetting Password

By default, the email driver is set to "log", which means you will be able to send emails but all the emails are logged at `storage/logs/laravel.log` file. 

Reset the password with `/api/auth/forgot-password` and then open this file. This is important for devs instances where there isn't any email driver set.
 
 Once you open this file, copy the token and use it to set a new password.

![reset-password-email-1](https://user-images.githubusercontent.com/68645347/89526300-2fa9c700-d7f0-11ea-9bac-54b260c77671.jpg)

You can also open your phpMyAdmin instance and find the `password_resets` table. Then copy the token from the last row.
```bash
URL: http://localhost:8089
Username: tirpitzuser
Password: tirpitzpassword
```

![reset-password-email-2](https://user-images.githubusercontent.com/68645347/89526219-156fe900-d7f0-11ea-87c3-b8589add9795.jpg)

### Troubleshooting
If you get redirected to home when calling from REST clients like Postman, just set header parameter "Accept: application/json". 