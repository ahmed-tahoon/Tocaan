# Order and Payment Management API

Laravel-based API for managing orders and payments with extensible payment gateway support.

## Features

- JWT Authentication with secure token management
- Order Management (CRUD operations) with status tracking
- Payment Processing with multiple gateways (Strategy Pattern)
- Extensible payment gateway architecture
- Business rules enforcement with custom exceptions
- Event-driven architecture for order status changes
- API request logging and monitoring
- Rate limiting for API protection
- Comprehensive test coverage (Unit, Feature, Integration)
- Consistent error handling and response formatting
- Service layer abstraction for business logic
- Dependency injection and service providers

## Requirements

- PHP >= 8.2
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Laravel 12

## Installation

1. Clone the repository:
```bash
git clone [<repository-url>](https://github.com/ahmed-tahoon/Tocaan)
cd tocaan
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations:
```bash
php artisan migrate
```

7. Generate JWT secret:
```bash
php artisan jwt:secret
```

8. Start the development server:
```bash
php artisan serve
```

## Testing

Run the test suite:
```bash
php artisan test
```

Run specific test groups:
```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```
