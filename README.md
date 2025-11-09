# Task Manager (TODO App)

This is the API part of task manager application.

## Stack

1. **Language:** PHP 8.2 or higher
2. **Framework:** Laravel 12
3. **Database:** MySQL

## Features

1. **User authentication :** register, login, logout using the Bearer token
2. **Task management :** create, update, delete restore, pin, unpin, mark as done or undone
3. **Task category management :** for an improved task organization

## The process to install

First of all you have to make sure that php, MySQL, git and composer are installed on your computer.

> 2.1 Clone the repository from github
```shell
git clone https://github.com/SomPascal/task-manager-api.git task-manager-api
```

> 2.2 Get in the project then install dependencies using composer
```shell
cd task-manager-api
composer install
```

> 2.3 Create the .env file
```shell
cp .env.example .env ## If you're using a linux based OS
copy .env.example .env ## If you're using Windows
``` 

> 2.4 Configure your database connection in the `.env` file then run migrations

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= ## the databse
DB_USERNAME= ## the user
DB_PASSWORD= ## the password
```

```shell
php artisan migrate
```

> 2.4 Genarte the security key

```shell
php artisan key:generate
```

> 2.5 Everything is okay. Run the API server

```shell
php artisan serve
```

The app should be available on http://localhost:8000 🎉

An OpenAPI documentation is available on http://localhost:8000/docs/api