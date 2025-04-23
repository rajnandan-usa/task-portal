# Task Portal – PHP Admin/User Task Management System

The **Task Portal** is a lightweight and secure web application built with **Core PHP** and **Bootstrap 5**, designed to streamline task management between administrators and users. This portal supports user creation, task submission, password security enforcement, and admin reporting—all in one place.

---

## Demo Login Credentials

### Admin Access
- **Email:** `admin@example.com`
- **Password:** `admin123`

>  Admin credentials are stored in the database with hashed passwords. You can update the email/password directly in the `users` table (`is_admin = 1`).

---

##  Features

###  Admin Panel
- Login with secure password validation (`password_hash()` + `md5()` fallback)
- Create users with:
  - Manual password
  - Option to auto-generate a secure password
- View all registered users and their activity
- See all tasks submitted by users
- Download all task data as a CSV report
- Fully styled interface with Bootstrap 5

###  User Panel
- Login with email and password
- Forced password change:
  - On first login
  - If last change > 30 days ago
- Submit new tasks with:
  - Start Time & Stop Time
  - Notes & Description
- Edit previously submitted tasks
- View a history of all submitted tasks

---

##  Folder Structure

task-portal/ ├── config/ │ └── db.php # Database connection ├── includes/ │ ├── functions.php # Utility functions (redirect, etc.) │ └── auth_check.php # Login/session validation ├── admin/ │ ├── login.php # Admin login │ ├── dashboard.php # Admin dashboard │ ├── create_user.php # Create a new user │ ├── users.php # List of all users │ ├── task_list.php # All submitted tasks by users │ └── download_report.php # Export tasks as CSV ├── user/ │ ├── login.php # User login │ ├── dashboard.php # User dashboard with tasks │ ├── create_task.php # Submit a new task │ ├── edit_task.php # Edit a submitted task │ └── change_password.php # Force password update ├── assets/ │ ├── css/ # Custom styles (optional) │ └── js/ # JS utilities (optional) ├── index.php # Entry point (redirects to login page) ├── logout.php # Destroys session and logs out └── README.md # This documentation

yaml
Copy
Edit

---

## 🛠️ Setup Instructions

1. **Clone the Repository**

   ```bash
   git clone https://github.com/your-username/task-portal.git
   cd task-portal
