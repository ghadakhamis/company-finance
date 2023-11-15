# company-finance
# Requirements
  
- PHP 8.1
- Apache
- Redis
- Composer

# Installation Steps:

a. Clone the repository 

```bash
    git clone https://github.com/ghadakhamis/company-finance.git
  ```
- Branches: main

b. In project root copy ".env.example" to ".env", and fill in all requirements to match your need and host settings.

```bash
  cp .env.example .env
  ```
- Edit the new .env file and change a lot of variables:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE={{db-name}}
DB_USERNAME=root
DB_PASSWORD={{password}}

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME={{email}}
MAIL_PASSWORD={{password}}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS={{email}}
MAIL_FROM_NAME="${APP_NAME}"

  ```
c. Install all the dependencies using composer

```bash
  composer install
  ```
d. Generate a new application key

```bash
  php artisan key:generate
  ```
e. Run the database migrations (Set the database connection in .env before migrating)

```bash
  php artisan migrate
  ```
f. Run the seeders

```bash
  php artisan db:seed
  ```
g. For local development server
```bash
  php artisan serve
  ```                                                                                                  
You can now access the server at http://localhost:8000     

You can access the API documentation at https://documenter.getpostman.com/view/5872734/2s9YXo1eWL