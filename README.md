# 🚀 TaskMaster Pro - Capstone Web Project

A comprehensive task management web application built with HTML, CSS, JavaScript, PHP, and MySQL.

---

## 🎯 Project Overview

TaskMaster Pro is a full-stack web application that helps users organize, prioritize, and track their tasks efficiently. The application demonstrates proficiency in both frontend and backend web technologies, including responsive design, interactive user interfaces, server-side processing, and database management.

**Course:** Web Technologies (23CSE404)  
**Total Marks:** 50  
**Student:** Deepthisetty  
**Student ID:** [24BTTCN002]

---

## ✨ Features

### 🔐 Core Functionality

1. **User Authentication**
   - User registration with validation  
   - Login/logout functionality  
   - Session management  
   - Password hashing (`password_hash`)  
   - Secure login verification  

2. **📝 Task Management**
   - Create, read, update, delete (CRUD) operations  
   - Priority levels (Low, Medium, High)  
   - Task categorization (Work, Personal, Shopping, Health, Other)  
   - Due date tracking  
   - Dynamic task display from database  

3. **⚡ Interactive Features**
   - Productivity time calculator  
   - Real-time form validation  
   - Dynamic task filtering  
   - Animated statistics counter  
   - Responsive navigation menu  

4. **📩 Contact System**
   - Contact form with validation  
   - File upload functionality  
   - FAQ section with interactive elements  

---

## 🎨 Design Features

- Responsive design (desktop, tablet, mobile)  
- Modern UI with gradients and animations  
- Consistent styling across pages  
- CSS Flexbox & Grid usage  
- Smooth transitions and hover effects  

---

## 🛠️ Technologies Used

### Frontend
- HTML5  
- CSS3 (Flexbox, Grid, Animations)  
- JavaScript (ES6, DOM manipulation, validation)  

### Backend
- PHP 7.4+  
- MySQL  

### Tools
- Git & GitHub  
- XAMPP (Local Server)  

---

## 📁 Project Structure

```
capstone-web-project/
│
├── index.html
├── about.html
├── features.html
├── contact.html
│
├── css/
│   └── styles.css
│
├── js/
│   ├── script.js
│   ├── calculator.js
│   ├── auth.js
│   ├── tasks.js
│   └── contact.js
│
├── php/
│   ├── config.php
│   ├── auth.php
│   ├── tasks.php
│   └── contact.php
│
├── login.php
├── tasks.php
├── database.sql
└── README.md
```

---

## 🚀 Setup Instructions

### Prerequisites

- PHP 7.4+  
- MySQL  
- XAMPP / WAMP  

---

### ⚙️ Installation Steps

1. Clone repository:
```bash
git clone https://github.com/yourusername/capstone-web-project.git
```

2. Move project to `htdocs` (XAMPP)

3. Start Apache & MySQL

4. Create database:
```
taskmaster_db
```

5. Import:
```
database.sql
```

6. Run project:
```
http://localhost/capstone-web-project
```

---

## ▶️ How to Use

1. Register a new account  
2. Login using credentials  
3. Add tasks  
4. View tasks in dashboard  
5. Filter tasks by priority  
6. Manage tasks (view/delete)  

---

## 📸 Screenshots

### 🔐 Login Page
(Add screenshot here)

### 📝 Task Dashboard
(Add screenshot here)

### ➕ Add Task
(Add screenshot here)

---

## 📊 Database Schema

### Users Table
- id  
- name  
- email  
- password  
- created_at  

### Tasks Table
- id  
- user_id  
- title  
- description  
- priority  
- due_date  
- category  
- status  

---

## 🎓 Requirements Fulfilled

### ✅ Pages
- Home, About, Features, Contact, Login, Tasks  

### ✅ Design
- Responsive layout using Flexbox & Grid  

### ✅ JavaScript
- Form validation  
- Dynamic task updates  
- Calculator  

### ✅ PHP
- Authentication  
- Session handling  
- CRUD operations  

### ✅ Database
- MySQL integration  
- Data relationships  

---

## 🧪 Testing Checklist

- [x] Registration works  
- [x] Login works  
- [x] Task creation  
- [x] Task display from DB  
- [x] Session handling  
- [x] Responsive UI  

---

## 🔒 Security Features

- Password hashing (`password_hash`)  
- Prepared statements (SQL injection prevention)  
- Input sanitization  
- XSS protection  
- Session-based authentication  

---

## ⚠️ Known Issues

- No password reset feature  
- No email verification  
- Task editing is basic  

---

## 📚 Future Enhancements

- Password reset system  
- Email verification  
- Task editing & update  
- Notifications  
- Dark mode  

---

## 📄 License

Educational project for Web Technologies course.

---

## 👨‍💻 Author

**Deepthisetty**  
Course: Web Technologies (23CSE404)  

---

## 📧 Contact

Email: deepthisetty85@gmail.com 
GitHub: https://github.com/Deepthisetty2811  

---

## 🔗 Links

**Live Demo:** http://localhost/capstone-web-project  
**Repository:** https://github.com/yourusername/capstone-web-project  

---

© 2024 TaskMaster Pro. Built for Capstone Project 🎓
