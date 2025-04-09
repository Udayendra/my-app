# PHP REST API - Recipe App

This is a simple PHP REST API built without a full framework. It uses **JWT authentication**, **MySQL**, and **Docker**, and supports user registration, login, recipe management, and recipe ratings. Designed with clean structure and test coverage via PHPUnit.

---

## Features

- Register & Login using JWT
- CRUD for Recipes
- Recipe Rating & Average Calculation
- Basic Search on Recipes
- PHPUnit Testing
- Built with Docker (MySQL + PHP)
- Clean MVC-like structure

---

## Technologies Used

- PHP 8.2
- MySQL 8
- Docker + Docker Compose
- JWT (`firebase/php-jwt`)
- PHPUnit
- Composer
- PDO (database access)

---

## Getting Started

### 1. Clone the Repository
```bash
git clone https://github.com/Udayendra/recipe-api.git
cd recipe-api
```

### 2. Copy & Edit the `.env` File
```bash
cp .env.example .env
```
Update credentials in `.env` if needed.

### 3. Start the Docker Containers
```bash
docker-compose up -d
```

### 4. Install Composer Dependencies
```bash
composer install
```

### 5. Run Database Migration (Manual)
Import the `db.sql` dump in your MySQL container or create tables manually.

---

## Authentication

- JWT is generated during login
- Pass token in `Authorization: Bearer <token>` header for protected routes

---

## API Endpoints

### Auth
| Method | Endpoint         | Description         |
|--------|------------------|---------------------|
| POST   | `/register`      | Register user       |
| POST   | `/login`         | Login and get token |

### Recipes
| Method | Endpoint        | Description               |
|--------|-----------------|---------------------------|
| GET    | `/recipes`      | List all recipes          |
| GET    | `/recipes/{id}` | Get specific recipe       |
| POST   | `/recipes`      | Create a new recipe       |
| PUT    | `/recipes/{id}` | Update a recipe           |
| DELETE | `/recipes/{id}` | Delete a recipe           |

### Ratings
| Method | Endpoint             | Description                  |
|--------|----------------------|------------------------------|
| POST   | `/ratings`           | Add rating to recipe         |
| GET    | `/ratings/{recipe}`  | Get average rating of recipe |

---

## Running Tests

```bash
./vendor/bin/phpunit tests/
```

> Note: Tests use mock data and PHPUnit’s `ResponseHelper::$testMode` flag.

---

## .env Example

```env
APP_ENV=local
APP_DEBUG=true

MYSQL_HOST=db
MYSQL_DATABASE=recipes_db
MYSQL_USER=api_user
MYSQL_PASSWORD=secret123
MYSQL_ROOT_PASSWORD=rootpass

JWT_SECRET=supersecurejwtsecret123
```

---

## Project Structure

```
src/
├── config/         # DB config
├── controllers/    # Route handlers
├── helpers/        # Response & JWT
├── middleware/     # Auth middleware
├── models/         # DB Models
├── routes/         # (optional) Route definitions
tests/              # PHPUnit tests
index.php           # Entry point
```

---

## Contact

For any questions or contributions, feel free to open an issue or connect with me!

