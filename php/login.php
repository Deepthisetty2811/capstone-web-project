<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "taskmaster_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// REGISTER
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $error = "User already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $success = "Account created! Please login.";
        } else {
            $error = "Registration failed!";
        }
    }
}

// LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: tasks.php");
            exit();
        } else {
            $error = "Invalid password!";
        }

    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - TaskMaster Pro</title>

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
<li><a href="login.php" class="active">Login</a></li>
<li><a href="../contact.html">Contact</a></li>
</ul>
</div>
</nav>

<section class="login-section">
<div class="container">
<div class="login-container">

<div class="login-form-wrapper">
<h2>Welcome Back</h2>
<p class="login-subtitle">Sign in to access your tasks</p>

<!-- SUCCESS / ERROR -->
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

<!-- TABS -->
<div class="form-tabs">
<button class="tab-btn active" onclick="showTab('login')">Login</button>
<button class="tab-btn" onclick="showTab('register')">Register</button>
</div>

<!-- LOGIN FORM -->
<form method="POST" id="loginForm" class="auth-form active-form">

<div class="form-group">
<label>Email Address</label>
<input type="email" name="email" required placeholder="your@email.com">
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" required placeholder="Enter your password">
</div>

<div class="form-options">
<label class="checkbox-label">
<input type="checkbox">
<span>Remember me</span>
</label>
<a href="#" class="forgot-link">Forgot password?</a>
</div>

<button type="submit" name="login" class="btn btn-primary btn-block">
Sign In
</button>

<div class="form-divider">
<span>OR</span>
</div>

<button type="button" class="btn btn-social">
<span>Continue with Google</span>
</button>

</form>

<!-- REGISTER FORM -->
<form method="POST" id="registerForm" class="auth-form">

<div class="form-group">
<label>Full Name</label>
<input type="text" name="name" required placeholder="John Doe">
</div>

<div class="form-group">
<label>Email Address</label>
<input type="email" name="email" required placeholder="your@email.com">
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" required placeholder="Create password">
</div>

<div class="form-options">
<label class="checkbox-label">
<input type="checkbox" required>
<span>I agree to Terms</span>
</label>
</div>

<button type="submit" name="register" class="btn btn-primary btn-block">
Create Account
</button>

</form>

</div>

<!-- RIGHT SIDE IMAGE (UNCHANGED) -->
<div class="login-image">
<svg width="400" height="400" viewBox="0 0 400 400">
<rect x="50" y="50" width="300" height="300" fill="#667eea" opacity="0.1" rx="20"/>
<circle cx="200" cy="130" r="40" fill="#764ba2" opacity="0.3"/>
<rect x="120" y="200" width="160" height="15" fill="#667eea" rx="5"/>
<rect x="120" y="230" width="160" height="15" fill="#764ba2" rx="5"/>
<rect x="120" y="270" width="160" height="35" fill="#48bb78" rx="8"/>
</svg>
</div>

</div>
</div>
</section>

<script>
function showTab(tab) {
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");
    const buttons = document.querySelectorAll(".tab-btn");

    buttons.forEach(btn => btn.classList.remove("active"));

    if (tab === "login") {
        loginForm.style.display = "block";
        registerForm.style.display = "none";
        buttons[0].classList.add("active");
    } else {
        loginForm.style.display = "none";
        registerForm.style.display = "block";
        buttons[1].classList.add("active");
    }
}

// default state
showTab('login');
</script>

</body>
</html>