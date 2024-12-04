# Test Project by Adam Guła

## Requirements

To run this project, make sure you have the following tools installed on your system:
- **PHP** version 8.3 or higher
- **Composer**
- **MySQL** version 8
- **Symfony CLI** (optional, for running the local server)

---

## Setting Up the Project

### 1. Clone the repository
Clone the project to your local system:  
```bash
git clone <repository-url>  
cd <project-directory>
```

### 2. Install dependencies
Navigate to the project's root directory and install dependencies:  
```bash
composer install
```

---

## Configuration

### 1. Database settings
Ensure that the database connection details are correctly configured in the `.env` and `.env.test` files. These files should include the following key:  
`DATABASE_URL=`
Update the values according to your local setup.

---

## Initializing the Database

### 1. Run migrations
Execute the database migrations:  
```bash
php bin/console doctrine:migrations:migrate
```
---

## Running the Application

Start the local test server using Symfony CLI:  
```bash
symfony server:start
```

The application will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## Tests

To run the tests, make sure the test database is configured (as specified in `.env.test`), and then execute:
```bash
php ./vendor/bin/phpunit
```
