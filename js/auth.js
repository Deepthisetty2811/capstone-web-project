// ===================================
// Authentication Forms
// Client-side Form Validation
// ===================================

// Tab Switching
function showTab(tabName) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const tabBtns = document.querySelectorAll('.tab-btn');
    
    if (tabName === 'login') {
        loginForm.classList.add('active-form');
        registerForm.classList.remove('active-form');
        tabBtns[0].classList.add('active');
        tabBtns[1].classList.remove('active');
    } else {
        registerForm.classList.add('active-form');
        loginForm.classList.remove('active-form');
        tabBtns[1].classList.add('active');
        tabBtns[0].classList.remove('active');
    }
}

// Form Validation
document.addEventListener('DOMContentLoaded', function() {
    // Login Form Validation
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('loginEmail').value.trim();
            const password = document.getElementById('loginPassword').value;
            
            // Clear previous errors
            clearErrors('login');
            
            let isValid = true;
            
            // Email validation
            if (!email) {
                showError('loginEmailError', 'Email is required');
                isValid = false;
            } else if (!window.appUtils.validateEmail(email)) {
                showError('loginEmailError', 'Please enter a valid email address');
                isValid = false;
            }
            
            // Password validation
            if (!password) {
                showError('loginPasswordError', 'Password is required');
                isValid = false;
            } else if (password.length < 6) {
                showError('loginPasswordError', 'Password must be at least 6 characters');
                isValid = false;
            }
            
            if (isValid) {
                // Store login state in session
                sessionStorage.setItem('isLoggedIn', 'true');
                sessionStorage.setItem('userEmail', email);
                
                // Set a cookie for "Remember Me"
                const rememberMe = document.querySelector('input[name="remember"]').checked;
                if (rememberMe) {
                    localStorage.setItem('rememberedEmail', email);
                }
                
                window.appUtils.showNotification('Login successful!', 'success');
                
                // Redirect after 1 second
                setTimeout(() => {
                    window.location.href = 'tasks.html';
                }, 1000);
            }
        });
    }
    
    // Register Form Validation
    const registerForm = document.getElementById('registerForm');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('registerName').value.trim();
            const email = document.getElementById('registerEmail').value.trim();
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('registerConfirmPassword').value;
            
            // Clear previous errors
            clearErrors('register');
            
            let isValid = true;
            
            // Name validation
            if (!name) {
                showError('registerNameError', 'Name is required');
                isValid = false;
            } else if (name.length < 2) {
                showError('registerNameError', 'Name must be at least 2 characters');
                isValid = false;
            }
            
            // Email validation
            if (!email) {
                showError('registerEmailError', 'Email is required');
                isValid = false;
            } else if (!window.appUtils.validateEmail(email)) {
                showError('registerEmailError', 'Please enter a valid email address');
                isValid = false;
            }
            
            // Password validation
            if (!password) {
                showError('registerPasswordError', 'Password is required');
                isValid = false;
            } else if (!window.appUtils.validatePassword(password)) {
                showError('registerPasswordError', 'Password must be at least 8 characters with uppercase, lowercase, and number');
                isValid = false;
            }
            
            // Confirm password validation
            if (!confirmPassword) {
                showError('registerConfirmError', 'Please confirm your password');
                isValid = false;
            } else if (password !== confirmPassword) {
                showError('registerConfirmError', 'Passwords do not match');
                isValid = false;
            }
            
            if (isValid) {
                // Store registration data
                sessionStorage.setItem('isLoggedIn', 'true');
                sessionStorage.setItem('userEmail', email);
                sessionStorage.setItem('userName', name);
                
                window.appUtils.showNotification('Registration successful!', 'success');
                
                // Redirect after 1 second
                setTimeout(() => {
                    window.location.href = 'tasks.html';
                }, 1000);
            }
        });
        
        // Real-time password strength indicator
        const passwordInput = document.getElementById('registerPassword');
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            updatePasswordStrengthUI(strength);
        });
    }
    
    // Pre-fill email if remembered
    const rememberedEmail = localStorage.getItem('rememberedEmail');
    if (rememberedEmail) {
        const emailInput = document.getElementById('loginEmail');
        if (emailInput) {
            emailInput.value = rememberedEmail;
            document.querySelector('input[name="remember"]').checked = true;
        }
    }
    
    // Check if already logged in
    if (sessionStorage.getItem('isLoggedIn') === 'true') {
        const userName = sessionStorage.getItem('userName') || 'User';
        console.log('Welcome back, ' + userName + '!');
    }
});

// Helper Functions
function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        
        // Add error styling to input
        const input = errorElement.previousElementSibling;
        if (input && input.tagName === 'INPUT') {
            input.style.borderColor = '#f56565';
        }
    }
}

function clearErrors(formType) {
    const prefix = formType === 'login' ? 'login' : 'register';
    const errorElements = document.querySelectorAll(`#${formType}Form .error-message`);
    
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    // Reset input borders
    const inputs = document.querySelectorAll(`#${formType}Form input`);
    inputs.forEach(input => {
        input.style.borderColor = '';
    });
}

function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    return strength;
}

function updatePasswordStrengthUI(strength) {
    // This could be expanded to show a visual strength meter
    const passwordInput = document.getElementById('registerPassword');
    
    if (strength <= 2) {
        passwordInput.style.borderColor = '#f56565'; // Weak - Red
    } else if (strength <= 4) {
        passwordInput.style.borderColor = '#ed8936'; // Medium - Orange
    } else {
        passwordInput.style.borderColor = '#48bb78'; // Strong - Green
    }
}

// Add input focus effects
document.querySelectorAll('.auth-form input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.querySelector('label')?.style.color = '#667eea';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.querySelector('label')?.style.color = '';
    });
});