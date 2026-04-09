<?php
// Start session to track login
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "taskmaster_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check credentials in the database
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['email'] = $email;
        echo "<script>alert('Login successful'); window.location='tasks.php';</script>";
    } else {
        // Login failed
        echo "<script>alert('Invalid email or password');</script>";
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
    <h2>Login to TaskMaster Pro</h2>

```
<form method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>
```

</body>
</html>
