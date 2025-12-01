🍽️ Tikako Management System - Laravel Point of Sale (POS)

A clean, responsive web application built with Laravel to manage customer orders, kitchen status, sales reports, and customer data for the Tikako Caffe & Culinary.

This project demonstrates strong back-end development skills, adherence to Clean Code principles, and comprehensive database transaction handling.

🚀 Key Features

Customer Ordering: QR code-based ordering directs customers directly to the menu with auto-filled table numbers.

Real-time Order Monitoring: Admin dashboard monitors new orders and cooking status without reloading.

Order Management: Admin panel allows status updates (Received, Cooking, Completed, Cancelled).

Sales Reporting: Detailed revenue reports filtered by 7 Days, 30 Days, or Monthly periods.

Print Functionality: Generates thermal receipts (Kasir) and kitchen tickets (Dapur).

Security: Separate login portals and restricted access control using Middleware (IsAdmin).

Authentication: Dedicated user registration/login flow for customers and a separate one for administration.

🛠️ Tech Stack

Backend Framework: Laravel (PHP)

Database: MySQL

Frontend: Blade Templates, Bootstrap 5.3, Vanilla JavaScript (for AJAX/Polling)

Data Visualization: Chart.js (for sales reports)

💻 Installation Guide (For Reviewers/Developers)

To run this project locally, you must have PHP (8.1+), Composer, and a MySQL instance running.

Clone the Repository:

git clone [https://github.com/firmanhdy/tikako-management-system.git](https://github.com/firmanhdy/tikako-management-system.git)
cd tikako-management-system


Setup Environment & Key:

# Copy the example file (critical step)
cp .env.example .env

# Generate application key
php artisan key:generate


Note: Edit the newly created .env file and configure your database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

Install Dependencies:

composer install
# If running frontend locally (for asset compilation):
# npm install
# npm run build 


Database Migration & Seeding:
This command runs migrations and seeds the default Admin/User accounts.

php artisan migrate --seed


Run the Application:

php artisan serve


(The application will be accessible at http://127.0.0.1:8000)

🔑 Default Credentials

Role

Email

Password

Admin

admin@tikako.com

password123

Customer

user@gmail.com

password123
