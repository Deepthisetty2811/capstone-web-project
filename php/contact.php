<?php
session_start();
require_once 'config.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Please fill all required fields!";
    } else {

        // FILE UPLOAD
        $attachment_path = NULL;

        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {

            $upload_dir = "../uploads/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = time() . "_" . basename($_FILES['attachment']['name']);
            $target = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target)) {
                $attachment_path = $target;
            }
        }

        // INSERT INTO DB
        $stmt = $conn->prepare("INSERT INTO contact_submissions (name,email,phone,subject,message,attachment,submitted_at) VALUES (?,?,?,?,?,?,NOW())");
        $stmt->bind_param("ssssss", $name, $email, $phone, $subject, $message, $attachment_path);

        if ($stmt->execute()) {
            $success = "Message sent successfully! We will contact you soon.";
        } else {
            $error = "Error sending message!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - TaskMaster Pro</title>
<link rel="stylesheet" href="../css/styles.css">
</head>

<body>

<nav class="navbar">
<div class="container">
<div class="logo">TaskMaster Pro</div>
<ul class="nav-links">
<li><a href="../index.html">Home</a></li>
<li><a href="../about.html">About</a></li>
<li><a href="../features.html">Features</a></li>
<li><a href="tasks.php">Tasks</a></li>
<li><a href="login.php">Login</a></li>
<li><a href="contact.php" class="active">Contact</a></li>
</ul>
</div>
</nav>

<section class="page-header">
<div class="container">
<h1>Get in Touch</h1>
<p>We'd love to hear from you</p>
</div>
</section>

<section class="contact-section">
<div class="container">
<div class="contact-grid">

<!-- LEFT INFO -->
<div class="contact-info">
<h2>Contact Information</h2>
<p>Reach out to us anytime.</p>

<p><b>Email:</b> info@taskmasterpro.com</p>
<p><b>Phone:</b> +91 9876543210</p>
<p><b>Location:</b> Bangalore, India</p>
</div>

<!-- FORM -->
<div class="contact-form-wrapper">
<h2>Send us a Message</h2>

<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if($success) echo "<p style='color:green;'>$success</p>"; ?>

<form method="POST" enctype="multipart/form-data" class="contact-form">

<div class="form-group">
<label>Your Name *</label>
<input type="text" name="name" required>
</div>

<div class="form-group">
<label>Email *</label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label>Phone</label>
<input type="text" name="phone">
</div>

<div class="form-group">
<label>Subject *</label>
<select name="subject" required>
<option value="">Select</option>
<option value="general">General</option>
<option value="support">Support</option>
<option value="feedback">Feedback</option>
</select>
</div>

<div class="form-group">
<label>Message *</label>
<textarea name="message" required></textarea>
</div>

<div class="form-group">
<label>Attachment</label>
<input type="file" name="attachment">
</div>

<button type="submit" class="btn btn-primary">Send Message</button>

</form>

</div>

</div>
</div>
</section>

<footer class="footer">
<div class="container">
<p>&copy; 2024 TaskMaster Pro. All rights reserved.</p>
</div>
</footer>

</body>
</html>