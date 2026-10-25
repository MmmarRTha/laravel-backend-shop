
<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="120" alt="Laravel Logo">
</p>

# 🚀 Laravel Backend Shop API

![GitHub last commit](https://img.shields.io/github/last-commit/MmmarRTha/laravel-backend-shop)
![Issues](https://img.shields.io/github/issues/MmmarRTha/laravel-backend-shop)
![GitHub stars](https://img.shields.io/github/stars/MmmarRTha/laravel-backend-shop?style=social)
![GitHub forks](https://img.shields.io/github/forks/MmmarRTha/laravel-backend-shop?style=social)

<p align="center">
  <b>Robust, modern, and production-ready Laravel API for e-commerce applications.</b>
</p>

---

## 🛠️ Tech Stack

- **Backend:** Laravel 13+, PHP 8+
- **Database:** PostgreSQL (default)
- **API:** RESTful, Sanctum Auth
- **Testing:** Pest PHP
- **Frontend Ready:** Easily connect with React, Vue, or any SPA (Inertia.js compatible)

---

## ✨ Features

- 🛒 <b>Product Management</b>: Manage products, categories, and inventory
- 📦 <b>Order Processing</b>: Handle customer orders and payment processing
- 🔒 <b>User Authentication</b>: Secure registration and authentication (Sanctum)
- ⚡ <b>Performance</b>: Optimized for speed and scalability
- 📊 <b>API-First</b>: Designed for seamless integration with modern frontends

---

## 🚀 Quick Start

```bash
# 1. Clone the repository
git clone https://github.com/MmmarRTha/laravel-backend-shop.git
cd laravel-backend-shop

# 2. Install dependencies
composer install
npm install

# 3. Copy .env and set up your environment
cp .env.example .env
php artisan key:generate

# 4. Set up the database
php artisan migrate --seed

# 5. Start the development servers
php artisan serve
npm run dev
```

---

## 📬 Example API Usage

**Get all products:**

```http
GET /api/products
Authorization: Bearer {token}
```

**Sample Response:**
```json
[
  {
    "id": 1,
    "name": "Product One",
    "price": 59.9,
    "image": "product_01",
    "categoryId": 1,
    "available": true,
  }
]
```

---

## 🧪 Testing

```bash
# Run tests with Pest
./vendor/bin/pest

# Or use the artisan command
php artisan test
```

---

## 📄 License

This project is [MIT](LICENSE) licensed.

---

## 👤 Author

- [MmmarRTha on GitHub](https://github.com/MmmarRTha)

---

<p align="center"><i>⭐️ If you like this project, give it a star to support my work!</i></p>
