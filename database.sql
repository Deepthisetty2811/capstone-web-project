-- ===================================
-- TaskMaster Pro Database Schema
-- MySQL Database Setup
-- ===================================

-- Create database
CREATE DATABASE IF NOT EXISTS taskmaster_db;
USE taskmaster_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tasks table
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    priority ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
    due_date DATE NOT NULL,
    category VARCHAR(50) DEFAULT 'Other',
    status ENUM('pending', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_due_date (due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact submissions table
CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    attachment VARCHAR(255),
    ip_address VARCHAR(45),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    INDEX idx_status (status),
    INDEX idx_submitted_at (submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for testing

-- Sample users
INSERT INTO users (name, email, password) VALUES
('John Doe', 'john@example.com', '$2y$10$YourHashedPasswordHere1'),
('Jane Smith', 'jane@example.com', '$2y$10$YourHashedPasswordHere2');

-- Sample tasks (user_id = 1)
INSERT INTO tasks (user_id, title, description, priority, due_date, category, status) VALUES
(1, 'Complete Web Project', 'Finish the capstone web development project with all required features', 'High', '2024-04-15', 'Work', 'pending'),
(1, 'Study for Exams', 'Review all subjects for upcoming semester exams', 'Medium', '2024-04-20', 'Personal', 'pending'),
(1, 'Grocery Shopping', 'Buy groceries for the week', 'Low', '2024-04-12', 'Shopping', 'pending'),
(1, 'Gym Workout', 'Complete full body workout routine', 'Medium', '2024-04-11', 'Health', 'pending'),
(1, 'Team Meeting', 'Attend weekly team sync meeting', 'High', '2024-04-13', 'Work', 'pending');

-- Sample contact submissions
INSERT INTO contact_submissions (name, email, phone, subject, message) VALUES
('Alice Johnson', 'alice@example.com', '+91 9876543210', 'general', 'I would like to know more about the premium features.'),
('Bob Williams', 'bob@example.com', '+91 9876543211', 'support', 'I am facing an issue with task synchronization.');

-- Create view for task statistics
CREATE OR REPLACE VIEW task_statistics AS
SELECT 
    user_id,
    COUNT(*) as total_tasks,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_tasks,
    SUM(CASE WHEN priority = 'High' THEN 1 ELSE 0 END) as high_priority_tasks,
    SUM(CASE WHEN due_date < CURDATE() AND status = 'pending' THEN 1 ELSE 0 END) as overdue_tasks
FROM tasks
GROUP BY user_id;

-- ===================================
-- End of Database Schema
-- ===================================