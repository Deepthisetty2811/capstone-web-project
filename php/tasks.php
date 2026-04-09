<?php
session_start();

// DB CONNECTION
$conn = new mysqli("localhost", "root", "", "taskmaster_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ADD TASK (FIXED)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'])) {

    $title = $_POST['title'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // ✅ SAFE INSERT
    $stmt = $conn->prepare("INSERT INTO tasks (user_id,title,description,priority,due_date,category,status) VALUES (?,?,?,?,?,?,'pending')");
    $stmt->bind_param("isssss", $user_id, $title, $description, $priority, $due_date, $category);
    $stmt->execute();
}

// FETCH TASKS
// ✅ SAFE SELECT
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Tasks - TaskMaster Pro</title>

<!-- ✅ FIXED PATH -->
<link rel="stylesheet" href="../css/styles.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
<div class="container">
<div class="logo">TaskMaster Pro</div>
<ul class="nav-links">
<li><a href="../index.html">Home</a></li>
<li><a href="tasks.php" class="active">Tasks</a></li>
<li><a href="logout.php">Logout</a></li>
</ul>
</div>
</nav>

<!-- HEADER -->
<section class="page-header">
<div class="container">
<h1>Task Management</h1>
<p>Create and manage your tasks efficiently</p>
</div>
</section>

<!-- TASK SECTION -->
<section class="tasks-section">
<div class="container">

<!-- ADD TASK FORM -->
<div class="task-form-container">
<h2>Create New Task</h2>

<!-- ✅ IMPORTANT FIX -->
<form method="POST" class="task-form">

<div class="form-row">

<div class="form-group">
<label>Task Title *</label>
<input type="text" name="title" required placeholder="Enter task title">
</div>

<div class="form-group">
<label>Priority *</label>
<select name="priority" required>
<option value="">Select Priority</option>
<option value="Low">Low</option>
<option value="Medium">Medium</option>
<option value="High">High</option>
</select>
</div>

</div>

<div class="form-row">

<div class="form-group">
<label>Due Date *</label>
<input type="date" name="due_date" required>
</div>

<div class="form-group">
<label>Category</label>
<select name="category">
<option value="Work">Work</option>
<option value="Personal">Personal</option>
<option value="Shopping">Shopping</option>
<option value="Health">Health</option>
<option value="Other">Other</option>
</select>
</div>

</div>

<div class="form-group">
<label>Description</label>
<textarea name="description" rows="3" placeholder="Enter task description"></textarea>
</div>

<button type="submit" class="btn btn-primary">Add Task</button>

</form>
</div>

<!-- TASK LIST -->
<div class="tasks-list">

<h2>Your Tasks</h2>

<?php if ($result->num_rows > 0) { ?>
    <?php while($row = $result->fetch_assoc()) { ?>

        <div class="task-card">

            <div class="task-header">
                <h3><?php echo $row['title']; ?></h3>

                <span class="priority-badge 
                    <?php echo strtolower($row['priority']); ?>">
                    <?php echo $row['priority']; ?>
                </span>
            </div>

            <p class="task-description">
                <?php echo $row['description']; ?>
            </p>

            <div class="task-meta">
                <span>📁 <?php echo $row['category']; ?></span>
                <span>📅 <?php echo $row['due_date']; ?></span>
            </div>

        </div>

    <?php } ?>
<?php } else { ?>
    <p>No tasks yet. Add your first task!</p>
<?php } ?>

</div>

</div>
</section>

<!-- FOOTER -->
<footer class="footer">
<div class="container">
<p>&copy; 2024 TaskMaster Pro</p>
</div>
</footer>

</body>
</html>