# Apex_User_Management_Api_Laravel

This project is a User Management System API built with Laravel. It provides endpoints for user registration, login, logout, profile management, user update, and user deletion. Below are the steps to set up and run the project.

## Prerequisites
- Git: Make sure you have Git installed on your system ([Download Git](https://git-scm.com/downloads)).
- PHP and Composer: Ensure you have PHP 8.0 or later and Composer installed ([Download Composer](https://getcomposer.org/)).
- Database: The project uses MySQL database. Have the appropriate database server and credentials ready (e.g., phpMyAdmin, XAMPP).

## Steps to Setup the Project
1. *Clone the Repository:*
   - Open a terminal in your desired directory.
   - Replace [USER] and [REPO] with the actual GitHub username and repository name.
   - Run the following command:
     
     git clone https://github.com/folorunsofavour/Apex_User_Management_Api_Laravel.git
     

2. *Navigate into the Project:*
   - Use the cd command to enter the project directory:
     
     cd Apex_User_Management_Api_Laravel
     

3. *Install Dependencies:*
   - Run the following command to install PHP dependencies using Composer:
     
     composer install
     

4. *Generate App Key:*
   - Laravel uses an app key for encryption and other security purposes. Run:
     
     php artisan key:generate
     

5. *Configure Database:*
   - Edit the .env file in the project root.
   - Set the appropriate database details (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

## Steps to Run Migration and Seed
1. *Run Migrations:*
   - Apply database migrations to create tables and initial data:
     
     php artisan migrate
     

2. *Seed Database:*
   
   php artisan db:seed
   

## Steps to Start Server
1. *Start Development Server:*
   - Start the Laravel development server to access the application:
     
     php artisan serve
     

2. *Open Application in Browser:*
   - Open [http://localhost:8000](http://localhost:8000) (or the designated port) in your browser.

## Steps to Run Tests
- Run tests to ensure everything is working as expected:
  
  php artisan test
