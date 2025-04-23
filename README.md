# Task Portal – PHP Admin/User Task Management System

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
- Fully styled  with Bootstrap 5

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


---

##  Setup Instructions

1. **Clone the Repository**

   ```bash
   git clone https://github.com/your-username/task-portal.git
   cd task-portal
