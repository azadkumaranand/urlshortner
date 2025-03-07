# urlshortner project setup

This guide provides a step-by-step setup for a Laravel 11 project using Vite.  

## Prerequisites  
Ensure your system has the following installed:  

- **PHP 8.2 or higher**  
- **Composer** (latest version)  
- **Node.js** (LTS version recommended)  
- **NPM**  
- **Database** (MySQL or sqlite)  

## Installation  

Follow these steps to set up:

# 1. Clone the repository
git clone -b master https://github.com/azadkumaranand/urlshortner.git

cd urlshortner

# 2. Install PHP dependencies
composer install

# 3. Copy the environment file
cp .env.example .env

# 4. Generate the application key
php artisan key:generate

# 5. Configure database (update .env file)
# Open .env and set your database credentials:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database_name
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Configure SMTP (Mail)

# Open the .env file and set up your SMTP mail configuration:
# it is important for sending invitation so make sure your credentials should correct 

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourmailserver.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@example.com

# 6. Run database migrations
php artisan migrate

# seed db with superadmin credentials 

php artisan db:seed

# 7. Install frontend dependencies
npm install

# 8. Build the frontend
npm run build

# 9. Serve the Laravel application
php artisan serve

# update APP_URL variable in .env file with your actual url where project is running 
