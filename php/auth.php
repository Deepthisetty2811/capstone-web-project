<?php
/**
 * User Authentication Handler
 * Login and Registration with Session Management
 */

session_start();
require_once 'config.php';

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_response(false, 'Invalid request method');
}

// Get action type
$action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';

switch ($action) {
    case 'register':
        handle_registration();
        break;
    
    case 'login':
        handle_login();
        break;
    
    case 'logout':
        handle_logout();
        break;
    
    default:
        send_json_response(false, 'Invalid action');
}

/**
 * Handle User Registration
 */
function handle_registration() {
    global $conn;
    
    // Validate required fields
    if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        send_json_response(false, 'All fields are required');
    }
    
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        send_json_response(false, 'Invalid email format');
    }
    
    // Validate password strength
    if (strlen($password) < 8) {
        send_json_response(false, 'Password must be at least 8 characters');
    }
    
    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        send_json_response(false, 'Email already registered');
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $insert_query = "INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    
    if ($stmt->execute()) {
        $user_id = $conn->insert_id;
        
        // Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['logged_in'] = true;
        
        send_json_response(true, 'Registration successful', [
            'user_id' => $user_id,
            'name' => $name,
            'email' => $email
        ]);
    } else {
        send_json_response(false, 'Registration failed. Please try again.');
    }
}

/**
 * Handle User Login
 */
function handle_login() {
    global $conn;
    
    // Validate required fields
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        send_json_response(false, 'Email and password are required');
    }
    
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    // Query user by email
    $query = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        send_json_response(false, 'Invalid email or password');
    }
    
    $user = $result->fetch_assoc();
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        send_json_response(false, 'Invalid email or password');
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['logged_in'] = true;
    
    // Handle "Remember Me" cookie
    if (isset($_POST['remember']) && $_POST['remember'] === 'true') {
        setcookie('user_email', $email, time() + (86400 * 30), "/"); // 30 days
    }
    
    send_json_response(true, 'Login successful', [
        'user_id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email']
    ]);
}

/**
 * Handle User Logout
 */
function handle_logout() {
    // Destroy session
    session_unset();
    session_destroy();
    
    // Clear cookies
    if (isset($_COOKIE['user_email'])) {
        setcookie('user_email', '', time() - 3600, "/");
    }
    
    send_json_response(true, 'Logout successful');
}

?>