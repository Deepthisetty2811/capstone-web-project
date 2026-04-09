// ===================================
// Tasks Management
// Form Validation & Dynamic Filtering
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    // Task Form Submission with Validation
    const taskForm = document.getElementById('taskForm');
    
    if (taskForm) {
        taskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const title = document.getElementById('taskTitle').value.trim();
            const priority = document.getElementById('taskPriority').value;
            const dueDate = document.getElementById('taskDueDate').value;
            const category = document.getElementById('taskCategory').value;
            const description = document.getElementById('taskDescription').value.trim();
            
            // Clear previous errors
            clearTaskErrors();
            
            let isValid = true;
            
            // Title validation
            if (!title) {
                showTaskError('titleError', 'Task title is required');
                isValid = false;
            } else if (title.length < 3) {
                showTaskError('titleError', 'Title must be at least 3 characters');
                isValid = false;
            }
            
            // Priority validation
            if (!priority) {
                showTaskError('priorityError', 'Please select a priority');
                isValid = false;
            }
            
            // Due date validation
            if (!dueDate) {
                showTaskError('dateError', 'Due date is required');
                isValid = false;
            } else {
                const selectedDate = new Date(dueDate);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    showTaskError('dateError', 'Due date cannot be in the past');
                    isValid = false;
                }
            }
            
            if (isValid) {
                // Create task object
                const task = {
                    id: Date.now(),
                    title: title,
                    priority: priority,
                    dueDate: dueDate,
                    category: category,
                    description: description,
                    completed: false,
                    createdAt: new Date().toISOString()
                };
                
                // Add task to the list
                addTaskToList(task);
                
                // Reset form
                taskForm.reset();
                
                // Update statistics
                updateTaskStats();
                
                // Show success notification
                if (window.appUtils) {
                    window.appUtils.showNotification('Task created successfully!', 'success');
                }
            }
        });
    }
    
    // Task Filtering
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get filter value
            const filter = this.getAttribute('data-filter');
            
            // Filter tasks
            filterTasks(filter);
        });
    });
    
    // Initialize task actions
    initializeTaskActions();
    
    // Update statistics on page load
    updateTaskStats();
});

// Add task to the list
function addTaskToList(task) {
    const tasksList = document.getElementById('tasksList');
    
    const taskCard = document.createElement('div');
    taskCard.className = 'task-card';
    taskCard.setAttribute('data-priority', task.priority);
    taskCard.setAttribute('data-id', task.id);
    
    const priorityClass = task.priority.toLowerCase();
    
    taskCard.innerHTML = `
        <div class="task-header">
            <h3>${escapeHtml(task.title)}</h3>
            <span class="priority-badge ${priorityClass}">${task.priority}</span>
        </div>
        <p class="task-description">${escapeHtml(task.description) || 'No description provided'}</p>
        <div class="task-meta">
            <span class="task-category">📁 ${task.category}</span>
            <span class="task-date">📅 ${task.dueDate}</span>
        </div>
        <div class="task-actions">
            <button class="btn-small btn-complete">✓ Complete</button>
            <button class="btn-small btn-delete">🗑 Delete</button>
        </div>
    `;
    
    tasksList.insertBefore(taskCard, tasksList.firstChild);
    
    // Add animation
    taskCard.style.opacity = '0';
    taskCard.style.transform = 'translateY(-20px)';
    
    setTimeout(() => {
        taskCard.style.transition = 'all 0.3s ease';
        taskCard.style.opacity = '1';
        taskCard.style.transform = 'translateY(0)';
    }, 10);
    
    // Attach event listeners
    attachTaskActions(taskCard);
}

// Initialize task actions for existing tasks
function initializeTaskActions() {
    const taskCards = document.querySelectorAll('.task-card');
    taskCards.forEach(card => attachTaskActions(card));
}

// Attach actions to a task card
function attachTaskActions(taskCard) {
    const completeBtn = taskCard.querySelector('.btn-complete');
    const deleteBtn = taskCard.querySelector('.btn-delete');
    
    if (completeBtn) {
        completeBtn.addEventListener('click', function() {
            taskCard.style.opacity = '0.5';
            taskCard.style.textDecoration = 'line-through';
            
            setTimeout(() => {
                taskCard.remove();
                updateTaskStats();
                if (window.appUtils) {
                    window.appUtils.showNotification('Task completed!', 'success');
                }
            }, 500);
        });
    }
    
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this task?')) {
                taskCard.style.transform = 'translateX(100%)';
                taskCard.style.opacity = '0';
                
                setTimeout(() => {
                    taskCard.remove();
                    updateTaskStats();
                    if (window.appUtils) {
                        window.appUtils.showNotification('Task deleted', 'success');
                    }
                }, 300);
            }
        });
    }
}

// Filter tasks
function filterTasks(filter) {
    const taskCards = document.querySelectorAll('.task-card');
    
    taskCards.forEach(card => {
        if (filter === 'all') {
            card.style.display = 'block';
        } else {
            const priority = card.getAttribute('data-priority');
            if (priority === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
}

// Update task statistics
function updateTaskStats() {
    const taskCards = document.querySelectorAll('.task-card');
    const totalTasks = taskCards.length;
    
    let highPriority = 0;
    
    taskCards.forEach(card => {
        if (card.getAttribute('data-priority') === 'High') {
            highPriority++;
        }
    });
    
    // Update stat boxes
    document.getElementById('totalTasks').textContent = totalTasks;
    document.getElementById('completedTasks').textContent = '0'; // This would come from database
    document.getElementById('pendingTasks').textContent = totalTasks;
    document.getElementById('highPriorityTasks').textContent = highPriority;
}

// Helper functions
function showTaskError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        
        const input = errorElement.previousElementSibling;
        if (input) {
            input.style.borderColor = '#f56565';
        }
    }
}

function clearTaskErrors() {
    const errorElements = document.querySelectorAll('#taskForm .error-message');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    const inputs = document.querySelectorAll('#taskForm input, #taskForm select');
    inputs.forEach(input => {
        input.style.borderColor = '';
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Real-time form validation feedback
document.addEventListener('DOMContentLoaded', function() {
    const taskTitleInput = document.getElementById('taskTitle');
    
    if (taskTitleInput) {
        taskTitleInput.addEventListener('input', function() {
            const length = this.value.trim().length;
            
            if (length === 0) {
                this.style.borderColor = '';
            } else if (length < 3) {
                this.style.borderColor = '#ed8936';
            } else {
                this.style.borderColor = '#48bb78';
            }
        });
    }
});