# User Management System

This is a simple user management system built with PHP and MySQL. It allows you to add new users and view a paginated, searchable list of existing users.

## Features

- Add new users with username, age, and email.
- Client-side validation for user input.
- View users in a paginated list.
- Search users by username or email.

## Technologies Used

- PHP
- MySQL
- HTML, CSS, JavaScript

## Setup Instructions

1. Clone or download the repository to your local server environment (e.g., XAMPP).
2. Configure your database connection in `config.php`.
3. Ensure your database has a `users` table with columns: `user_id`, `username`, `age`, `email`.
4. Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP).
5. Start your web server and navigate to the project URL.

## Usage

- Open `index.php` in your browser to add a new user.
- Open `fetch.php` to view the list of users with search and pagination features.

## Admin Panel

- Open `admin.php` to manage users.
- Edit user details or delete users from the admin interface.
- Provides secure update and delete operations with user feedback.
