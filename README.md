# Laravel Application

## Overview

This is an API that allow users to perform CRUD operations (Create, Read, Update,Delete) on holiday plans.

## Requirements

- Docker
- Docker Compose
- Composer

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/RafaelPanisset/holiday-planner.git
cd holiday-planner
```

### 2. Install the PHP dependencies using Composer.

```bash
composer install
```

### 3.  Set Up Environment Variables
Create a .env file in the root of your project based on the provided .env.example. Update the environment variables as needed.

```bash
docker-compose up --build
```

### 4. Access the Application
The Laravel application will be accessible at http://localhost:8005.

### 5. Run Migrations
To set up the database, run the migrations:
```bash
docker exec -it holiday-planner-app php artisan migrate
```

### 6. Passport Setup
To set up the database, run the passport command:
```bash
docker exec -it holiday-planner-app php artisan passport:install
```


### 7. Running Tests
To run the tests for the Laravel application, use the following command:

```bash
docker exec -it holiday-planner-app php artisan test
```

### 8. Accessing Scribe Documentation

If you are using Scribe for API documentation, you can access it via the following URL:

```bash
docker exec -it laravel-app php artisan test
```

