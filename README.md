# TaskMaster Pro - Capstone Web Project

A comprehensive task management web application built with HTML, CSS, JavaScript, PHP, and MySQL.

## 🎯 Project Overview

TaskMaster Pro is a full-stack web application that helps users organize, prioritize, and track their tasks efficiently. The application demonstrates proficiency in both frontend and backend web technologies, including responsive design, interactive user interfaces, server-side processing, and database management.

**Course:** Web Technologies (23CSE404)  
**Total Marks:** 50  
**Student:** [Your Name Here]

## ✨ Features

### Core Functionality

1. **User Authentication**
   - User registration with validation
   - Login/logout functionality
   - Session management
   - "Remember Me" cookie feature
   - Password strength validation

2. **Task Management**
   - Create, read, update, delete (CRUD) operations
   - Priority levels (Low, Medium, High)
   - Task categorization (Work, Personal, Shopping, Health, Other)
   - Due date tracking
   - Task filtering and search

3. **Interactive Features**
   - Productivity time calculator
   - Real-time form validation
   - Dynamic task filtering
   - Animated statistics counter
   - Responsive navigation menu

4. **Contact System**
   - Contact form with validation
   - File upload functionality
   - Email notifications (configurable)
   - FAQ section with interactive elements

### Design Features

- **Responsive Design:** Works seamlessly on desktop, tablet, and mobile devices
- **Modern UI:** Gradient backgrounds, smooth animations, and intuitive layouts
- **Consistent Styling:** Unified color scheme and typography across all pages
- **Box Model & Positioning:** Proper use of CSS layout techniques
- **Interactive Elements:** Hover effects, transitions, and dynamic content

## 🛠️ Technologies Used

### Frontend
- **HTML5:** Semantic markup, forms, and accessibility
- **CSS3:** Flexbox, Grid, animations, transitions, responsive design
- **JavaScript (ES6+):** DOM manipulation, event handling, form validation

### Backend
- **PHP 7.4+:** Server-side processing, session management, file handling
- **MySQL:** Database design, CRUD operations, data relationships

### Development Tools
- **Git & GitHub:** Version control and repository management
- **GitHub Pages:** Static frontend hosting (HTML/CSS/JS)
- **XAMPP/WAMP:** Local PHP and MySQL development environment

## 📁 Project Structure

```
capstone-web-project/
│
├── index.html              # Home page
├── about.html              # About page
├── features.html           # Features page with calculator
├── tasks.html              # Task management page
├── login.html              # Login/Registration page
├── contact.html            # Contact page
│
├── css/
│   └── styles.css          # Main stylesheet (responsive design)
│
├── js/
│   ├── script.js           # Main JavaScript file
│   ├── calculator.js       # Productivity calculator
│   ├── auth.js             # Authentication validation
│   ├── tasks.js            # Task management
│   └── contact.js          # Contact form validation
│
├── php/
│   ├── config.php          # Database configuration
│   ├── auth.php            # User authentication handler
│   ├── tasks.php           # Task CRUD operations
│   └── contact.php         # Contact form handler
│
├── database.sql            # MySQL database schema
└── README.md               # Project documentation
```

## 🚀 Setup Instructions

### Prerequisites

- Web server with PHP support (Apache recommended)
- MySQL database server
- PHP 7.4 or higher
- Modern web browser

### Local Development Setup

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/capstone-web-project.git
   cd capstone-web-project
   ```

2. **Database Setup**
   - Start your MySQL server (via XAMPP, WAMP, or MAMP)
   - Create a new database named `taskmaster_db`
   - Import the database schema:
     ```bash
     mysql -u root -p taskmaster_db < database.sql
     ```

3. **Configure Database Connection**
   - Open `php/config.php`
   - Update database credentials if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'taskmaster_db');
     ```

4. **Start Local Server**
   - Using XAMPP/WAMP: Place the project in `htdocs` folder
   - Or use PHP built-in server:
     ```bash
     php -S localhost:8000
     ```

5. **Access the Application**
   - Open your browser and navigate to:
     - `http://localhost:8000` (if using PHP server)
     - `http://localhost/capstone-web-project` (if using XAMPP/WAMP)

### Deployment

#### Frontend (GitHub Pages)

1. Push all HTML, CSS, and JavaScript files to your GitHub repository
2. Go to repository Settings → Pages
3. Select main branch as source
4. Your static frontend will be available at: `https://yourusername.github.io/capstone-web-project`

#### Backend (PHP Hosting)

1. Choose a PHP hosting service (e.g., 000webhost, Heroku, or shared hosting)
2. Upload PHP files and configure database
3. Update AJAX endpoints in JavaScript files to point to your hosted PHP files

## 📊 Database Schema

### Users Table
- `id` (Primary Key)
- `name` (User's full name)
- `email` (Unique identifier)
- `password` (Hashed password)
- `created_at`, `updated_at` (Timestamps)

### Tasks Table
- `id` (Primary Key)
- `user_id` (Foreign Key → users.id)
- `title`, `description`
- `priority` (Low/Medium/High)
- `due_date`, `category`, `status`
- `created_at`, `completed_at` (Timestamps)

### Contact Submissions Table
- `id` (Primary Key)
- `name`, `email`, `phone`
- `subject`, `message`, `attachment`
- `ip_address`, `submitted_at`, `status`

## 🎓 Capstone Requirements Fulfillment

### ✅ Pages & Structure (Requirement 2.1)
- 6+ web pages (index, about, features, tasks, login, contact)
- Consistent navigation and styling
- Clear information architecture

### ✅ Design & Responsive Layout (Requirement 2.2)
- Fully responsive using CSS Grid and Flexbox
- Box Model, Positioning, and Floats implemented
- Works on desktop, tablet, and mobile
- Consistent color scheme and typography

### ✅ JavaScript/DHTML Functionality (Requirement 2.3)
- Productivity calculator with real-time calculations
- Client-side form validation on all forms
- Dynamic task filtering and management
- Animated statistics counter
- Interactive UI elements with DOM manipulation

### ✅ PHP Server-Side Features (Requirement 2.4)
- Form data handling (login, registration, contact, tasks)
- File upload functionality (contact attachments)
- Session management across pages
- Cookie handling (Remember Me feature)
- Built-in PHP functions utilized

### ✅ Database Integration (Requirement 2.5)
- PHP + MySQL integration
- CRUD operations (Create, Read, Update, Delete)
- User authentication and task management
- Data relationships and constraints

### ✅ GitHub Repository & Deployment (Requirement 2.6)
- All code in public GitHub repository
- Meaningful and incremental commits
- README.md with complete documentation
- Frontend deployed on GitHub Pages
- Backend deployment ready

## 🧪 Testing

### Test User Accounts
```
Email: john@example.com
Password: Test@1234

Email: jane@example.com
Password: Test@1234
```

### Testing Checklist

- [ ] User registration with validation
- [ ] User login/logout functionality
- [ ] Task creation with all fields
- [ ] Task filtering by priority
- [ ] Task update and delete
- [ ] Contact form submission
- [ ] File upload functionality
- [ ] Responsive design on mobile
- [ ] Form validation (client & server)
- [ ] Session persistence

## 📝 Features Demonstration

### JavaScript Dynamic Features

1. **Productivity Calculator** (features.html)
   - Input tasks per day and time per task
   - Adjust efficiency gain slider
   - Calculate time savings (daily, weekly, monthly, yearly)

2. **Form Validation** (All pages with forms)
   - Real-time validation feedback
   - Email format validation
   - Password strength checking
   - Required field validation

3. **Task Management** (tasks.html)
   - Add new tasks with validation
   - Filter tasks by priority
   - Complete/delete tasks
   - Dynamic statistics update

4. **Interactive Elements**
   - Animated counter on homepage
   - Mobile navigation menu
   - Smooth scroll animations
   - Hover effects and transitions

### PHP Features

1. **User Authentication**
   - Secure password hashing
   - Session-based authentication
   - Cookie management
   - SQL injection prevention

2. **Task CRUD Operations**
   - Create tasks with validation
   - Read user-specific tasks
   - Update task properties
   - Delete tasks securely

3. **File Upload**
   - Validate file type and size
   - Generate unique filenames
   - Secure file storage
   - Error handling

## 🎨 Design Principles

- **Color Palette:** Primary (#667eea), Secondary (#764ba2), Accent (#48bb78)
- **Typography:** System fonts for optimal performance
- **Layout:** Mobile-first responsive design
- **UX:** Intuitive navigation and clear call-to-actions

## 🔒 Security Features

- Password hashing using PHP's `password_hash()`
- SQL injection prevention with prepared statements
- XSS protection with `htmlspecialchars()`
- CSRF protection (session tokens)
- File upload validation
- Input sanitization

## 📚 Future Enhancements

- Email verification for new users
- Password reset functionality
- Task sharing and collaboration
- Recurring tasks feature
- Mobile app version
- Real-time notifications
- Data export (PDF, CSV)
- Calendar integration
- Dark mode toggle

## 🤝 Contributing

This is a capstone project for academic purposes. If you'd like to suggest improvements:

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📄 License

This project is created for educational purposes as part of the Web Technologies course.

## 👨‍💻 Author

**[Your Name]**  
Student ID: [Your ID]  
Course: Web Technologies (23CSE404)  
Institution: [Your Institution]

## 🙏 Acknowledgments

- Instructor: Mir Junaid Rasool
- Web Technologies Course (23CSE404)
- Open source libraries and resources used

## 📧 Contact

For questions or feedback:
- Email: [your.email@example.com]
- GitHub: [@yourusername](https://github.com/yourusername)

---

**Note:** Replace placeholder values (Your Name, GitHub username, etc.) with your actual information before submission.

**Live Demo:** [GitHub Pages URL]  
**Repository:** [GitHub Repository URL]

© 2024 TaskMaster Pro. Built with ❤️ for Web Technologies Capstone Project.