# 🎬 DiStreaming Laravel API

DiStreaming Laravel API is the backend service for the DiStreaming application. It provides RESTful APIs for managing users, movies, and categories, along with secure authentication using token-based authorization.

---

## 🚀 Features

* 🔐 Authentication (Login & Logout with Token)
* 👤 User Management (CRUD)
* 🎭 Category Management (CRUD)
* 🎬 Movie Management (CRUD)
* 🔒 Protected Routes using Middleware
* 📡 RESTful API Architecture
* 🧾 JSON-based Responses

---

## 🛠️ Tech Stack

* Laravel
* MySQL
* Laravel Sanctum (or token-based authentication)
* Eloquent ORM

---

## 📂 Project Structure

```bash
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── CategoryController.php
│   │   ├── MovieController.php
│
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Movie.php

routes/
└── api.php
```

---

## ⚙️ Installation

1. Clone the repository

```bash
git clone https://github.com/glriadomenica-debug/DiStreaming_laravel_project.git
```

2. Navigate into the project folder

```bash
cd DiStreaming_laravel_project
```

3. Install dependencies

```bash
composer install
```

4. Copy environment file

```bash
cp .env.example .env
```

5. Generate application key

```bash
php artisan key:generate
```

6. Configure your database in `.env`

```env
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations

```bash
php artisan migrate
```

8. Start the server

```bash
php artisan serve
```

---

## 🔑 Authentication

This API uses token-based authentication.

### Login

```http
POST /api/login
```

### Logout

```http
POST /api/logout
```

Include the token in request headers:

```http
Authorization: Bearer {your_token}
```

---

## 📡 API Endpoints

### 👤 Users

```http
GET    /api/users
POST   /api/users
PUT    /api/users/{id}
DELETE /api/users/{id}
```

### 🎭 Categories

```http
GET    /api/categories
POST   /api/categories
PUT    /api/categories/{id}
DELETE /api/categories/{id}
```

### 🎬 Movies

```http
GET    /api/movies
POST   /api/movies
PUT    /api/movies/{id}
DELETE /api/movies/{id}
```

---

## 📌 Notes

* All endpoints (except login) are protected by authentication middleware.
* Use tools like Postman or your React frontend to test the API.
* Default local server:

```bash
http://localhost:8000
```

---

## 🔗 Frontend Repository

React frontend:
👉 https://github.com/glriadomenica-debug/DiStreaming_react_project

---

## 🧠 Learning Purpose

This project was built to practice:

* RESTful API development with Laravel
* Authentication & Authorization
* CRUD operations
* Backend–Frontend integration
* MVC architecture

---

## 👤 Author

**Gloria Domenica Ferreira Da Costa E Silva**

* GitHub: https://github.com/glriadomenica-debug

---

## 📄 License

This project is for educational purposes.
