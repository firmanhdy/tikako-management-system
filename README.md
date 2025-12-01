🍽️ Tikako Restaurant Management System (POS)

A clean, responsive web application built with Laravel to manage customer orders, kitchen status, sales reports, and customer data for the Tikako Caffe & Culinary.

This project demonstrates strong back-end development skills, adherence to Clean Code principles, and comprehensive database transaction handling.

🛠️ Tech Stack Overview

🚀 Key Features

Customer Ordering (QR Code): Customers order from their table using a QR code scan, with the table number automatically filled.

Real-time Monitoring (Admin): The admin dashboard displays new order status and currently cooking orders without manual refresh.

Order Management (Kitchen): The admin panel allows status updates for orders (Received, Cooking, Completed, Cancelled).

Sales Reporting & Analytics 📈: Detailed revenue reports with trend visualization (Chart.js), filterable by period (7 days, 30 days, This Month).

Print Functionality 🖨️: Generates thermal receipts (Cashier) and kitchen order tickets (Kitchen Docket) in print-ready format.

Security & Authorization: Strict separation of login portals and control access using Laravel Middleware (IsAdmin).

Dual Authentication Flow: Separate registration/login flows for customers and administrators.

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
