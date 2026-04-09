<?php
/**
 * Contact Form Handler
 * Handle contact form submissions with optional file upload
 */

session_start();
require_once 'config.php';

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_response(false, 'Invalid request method');
}

// Get and sanitize form data
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : '';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

// Validate required fields
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
} elseif (strlen($name) < 2) {
    $errors[] = 'Name must be at least 2 characters';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
} elseif (strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters';
}

// If there are validation errors, return them
if (!empty($errors)) {
    send_json_response(false, implode(', ', $errors));
}

// Handle file upload if present
$attachment_path = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $upload_result = handle_file_upload($_FILES['attachment']);
    
    if ($upload_result['success']) {
        $attachment_path = $upload_result['path'];
    } else {
        send_json_response(false, $upload_result['message']);
    }
}

// Insert contact submission into database
$query = "INSERT INTO contact_submissions (name, email, phone, subject, message, attachment, ip_address, submitted_at) 
          VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

$ip_address = $_SERVER['REMOTE_ADDR'];
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssss", $name, $email, $phone, $subject, $message, $attachment_path, $ip_address);

if ($stmt->execute()) {
    $submission_id = $conn->insert_id;
    
    // Send email notification (in production)
    send_email_notification($name, $email, $subject, $message);
    
    send_json_response(true, 'Thank you for contacting us! We will respond within 24 hours.', [
        'submission_id' => $submission_id
    ]);
} else {
    send_json_response(false, 'Failed to submit contact form. Please try again.');
}

/**
 * Handle File Upload
 */
function handle_file_upload($file) {
    $upload_dir = '../uploads/';
    
    // Create upload directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Validate file size (5MB max)
    $max_size = 5 * 1024 * 1024; // 5MB in bytes
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'File size must not exceed 5MB'];
    }
    
    // Validate file type (allow images and PDFs)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $file_type = mime_content_type($file['tmp_name']);
    
    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and PDF are allowed'];
    }
    
    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('contact_') . '_' . time() . '.' . $file_extension;
    $upload_path = $upload_dir . $new_filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return ['success' => true, 'path' => $upload_path];
    } else {
        return ['success' => false, 'message' => 'Failed to upload file'];
    }
}

/**
 * Send Email Notification
 */
function send_email_notification($name, $email, $subject, $message) {
    // In production, this would send an actual email
    // Using PHP mail() function or a library like PHPMailer
    
    $to = 'info@taskmasterpro.com';
    $email_subject = 'New Contact Form Submission: ' . $subject;
    $email_body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: noreply@taskmasterpro.com\r\n";
    $headers .= "Reply-To: $email\r\n";
    
    // Uncomment in production:
    // mail($to, $email_subject, $email_body, $headers);
    
    // For now, just log it
    error_log("Contact form submission from $name ($email)");
}

?>