# Mediusware Banking Application

This is a Laravel-based web application for managing banking transactions.

## Features

- **User Management:** Allows registration, login, and management of user accounts.
- **Transactions:** Users can deposit and withdraw money from their accounts.
- **Transaction History:** Users can view their transaction history.
- **Account Types:** Supports different account types with varying fee structures and privileges.

## Getting Started

1. **Clone the repository:**

    ``` 
    git clone https://github.com/muajjamhossain/mediusware-job.git
    ```

2. **Navigate to the project directory:**

    ``` 
    cd mediusware-job
    ```

3. **Install dependencies:**

    ``` 
    composer install
    ```

4. **Copy the `.env.example` file to `.env` and configure your environment variables, including the database connection.**

5. **Generate an application key:**

    ``` 
    php artisan key:generate
    ```

6. **Run migrations and seed the database:**

    ``` 
    php artisan migrate --seed
    ```

7. **Serve the application:**

    ``` 
    php artisan serve
    ```

8. **Access the application in your web browser at [http://localhost:8000](http://localhost:8000).**
