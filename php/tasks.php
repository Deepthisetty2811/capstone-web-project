<?php
/**
 * Task Management Handler
 * CRUD Operations for Tasks (Create, Read, Update, Delete)
 */

session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    send_json_response(false, 'Authentication required');
}

$user_id = $_SESSION['user_id'];

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    handle_read_tasks($user_id);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';
    
    switch ($action) {
        case 'create':
            handle_create_task($user_id);
            break;
        
        case 'update':
            handle_update_task($user_id);
            break;
        
        case 'delete':
            handle_delete_task($user_id);
            break;
        
        case 'complete':
            handle_complete_task($user_id);
            break;
        
        default:
            send_json_response(false, 'Invalid action');
    }
} else {
    send_json_response(false, 'Invalid request method');
}

/**
 * Create New Task
 */
function handle_create_task($user_id) {
    global $conn;
    
    // Validate required fields
    if (!isset($_POST['title']) || !isset($_POST['priority']) || !isset($_POST['due_date'])) {
        send_json_response(false, 'Title, priority, and due date are required');
    }
    
    $title = sanitize_input($_POST['title']);
    $priority = sanitize_input($_POST['priority']);
    $due_date = sanitize_input($_POST['due_date']);
    $category = isset($_POST['category']) ? sanitize_input($_POST['category']) : 'Other';
    $description = isset($_POST['description']) ? sanitize_input($_POST['description']) : '';
    
    // Validate priority
    $valid_priorities = ['Low', 'Medium', 'High'];
    if (!in_array($priority, $valid_priorities)) {
        send_json_response(false, 'Invalid priority value');
    }
    
    // Insert task
    $query = "INSERT INTO tasks (user_id, title, description, priority, due_date, category, status, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $user_id, $title, $description, $priority, $due_date, $category);
    
    if ($stmt->execute()) {
        $task_id = $conn->insert_id;
        send_json_response(true, 'Task created successfully', [
            'task_id' => $task_id
        ]);
    } else {
        send_json_response(false, 'Failed to create task');
    }
}

/**
 * Read All Tasks for User
 */
function handle_read_tasks($user_id) {
    global $conn;
    
    $filter = isset($_GET['filter']) ? sanitize_input($_GET['filter']) : 'all';
    
    $query = "SELECT id, title, description, priority, due_date, category, status, created_at 
              FROM tasks 
              WHERE user_id = ?";
    
    // Add filter if not 'all'
    if ($filter !== 'all') {
        $query .= " AND priority = ?";
    }
    
    $query .= " ORDER BY due_date ASC, created_at DESC";
    
    $stmt = $conn->prepare($query);
    
    if ($filter !== 'all') {
        $stmt->bind_param("is", $user_id, $filter);
    } else {
        $stmt->bind_param("i", $user_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    
    send_json_response(true, 'Tasks retrieved successfully', $tasks);
}

/**
 * Update Task
 */
function handle_update_task($user_id) {
    global $conn;
    
    if (!isset($_POST['task_id'])) {
        send_json_response(false, 'Task ID is required');
    }
    
    $task_id = (int)$_POST['task_id'];
    
    // Verify task belongs to user
    $check_query = "SELECT id FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows === 0) {
        send_json_response(false, 'Task not found or access denied');
    }
    
    // Build update query dynamically
    $update_fields = [];
    $params = [];
    $types = "";
    
    if (isset($_POST['title'])) {
        $update_fields[] = "title = ?";
        $params[] = sanitize_input($_POST['title']);
        $types .= "s";
    }
    
    if (isset($_POST['description'])) {
        $update_fields[] = "description = ?";
        $params[] = sanitize_input($_POST['description']);
        $types .= "s";
    }
    
    if (isset($_POST['priority'])) {
        $update_fields[] = "priority = ?";
        $params[] = sanitize_input($_POST['priority']);
        $types .= "s";
    }
    
    if (isset($_POST['due_date'])) {
        $update_fields[] = "due_date = ?";
        $params[] = sanitize_input($_POST['due_date']);
        $types .= "s";
    }
    
    if (isset($_POST['category'])) {
        $update_fields[] = "category = ?";
        $params[] = sanitize_input($_POST['category']);
        $types .= "s";
    }
    
    if (empty($update_fields)) {
        send_json_response(false, 'No fields to update');
    }
    
    $query = "UPDATE tasks SET " . implode(", ", $update_fields) . " WHERE id = ? AND user_id = ?";
    $params[] = $task_id;
    $params[] = $user_id;
    $types .= "ii";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        send_json_response(true, 'Task updated successfully');
    } else {
        send_json_response(false, 'Failed to update task');
    }
}

/**
 * Delete Task
 */
function handle_delete_task($user_id) {
    global $conn;
    
    if (!isset($_POST['task_id'])) {
        send_json_response(false, 'Task ID is required');
    }
    
    $task_id = (int)$_POST['task_id'];
    
    $query = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $task_id, $user_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json_response(true, 'Task deleted successfully');
        } else {
            send_json_response(false, 'Task not found or access denied');
        }
    } else {
        send_json_response(false, 'Failed to delete task');
    }
}

/**
 * Mark Task as Complete
 */
function handle_complete_task($user_id) {
    global $conn;
    
    if (!isset($_POST['task_id'])) {
        send_json_response(false, 'Task ID is required');
    }
    
    $task_id = (int)$_POST['task_id'];
    
    $query = "UPDATE tasks SET status = 'completed', completed_at = NOW() WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $task_id, $user_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json_response(true, 'Task marked as complete');
        } else {
            send_json_response(false, 'Task not found or access denied');
        }
    } else {
        send_json_response(false, 'Failed to complete task');
    }
}

?>