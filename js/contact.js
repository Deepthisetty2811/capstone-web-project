// ===================================
// Contact Form
// Form Validation & Submission
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('contactName').value.trim();
            const email = document.getElementById('contactEmail').value.trim();
            const phone = document.getElementById('contactPhone').value.trim();
            const subject = document.getElementById('contactSubject').value;
            const message = document.getElementById('contactMessage').value.trim();
            
            // Clear previous errors
            clearContactErrors();
            
            let isValid = true;
            
            // Name validation
            if (!name) {
                showContactError('nameError', 'Name is required');
                isValid = false;
            } else if (name.length < 2) {
                showContactError('nameError', 'Name must be at least 2 characters');
                isValid = false;
            } else if (!/^[a-zA-Z\s]+$/.test(name)) {
                showContactError('nameError', 'Name can only contain letters and spaces');
                isValid = false;
            }
            
            // Email validation
            if (!email) {
                showContactError('emailError', 'Email is required');
                isValid = false;
            } else if (!window.appUtils.validateEmail(email)) {
                showContactError('emailError', 'Please enter a valid email address');
                isValid = false;
            }
            
            // Phone validation (optional but must be valid if provided)
            if (phone && !window.appUtils.validatePhone(phone)) {
                showContactError('phoneError', 'Please enter a valid phone number');
                isValid = false;
            }
            
            // Subject validation
            if (!subject) {
                showContactError('subjectError', 'Please select a subject');
                isValid = false;
            }
            
            // Message validation
            if (!message) {
                showContactError('messageError', 'Message is required');
                isValid = false;
            } else if (message.length < 10) {
                showContactError('messageError', 'Message must be at least 10 characters');
                isValid = false;
            } else if (message.length > 1000) {
                showContactError('messageError', 'Message must not exceed 1000 characters');
                isValid = false;
            }
            
            if (isValid) {
                // Create contact data object
                const contactData = {
                    name: name,
                    email: email,
                    phone: phone || 'Not provided',
                    subject: subject,
                    message: message,
                    timestamp: new Date().toISOString()
                };
                
                // Simulate form submission
                submitContactForm(contactData);
            }
        });
    }
    
    // Character counter for message
    const messageTextarea = document.getElementById('contactMessage');
    if (messageTextarea) {
        const counterDiv = document.createElement('div');
        counterDiv.className = 'char-counter';
        counterDiv.style.cssText = 'text-align: right; color: #718096; font-size: 0.85rem; margin-top: 0.25rem;';
        messageTextarea.parentElement.appendChild(counterDiv);
        
        messageTextarea.addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = 1000;
            counterDiv.textContent = `${length}/${maxLength} characters`;
            
            if (length > maxLength) {
                counterDiv.style.color = '#f56565';
            } else if (length > maxLength * 0.9) {
                counterDiv.style.color = '#ed8936';
            } else {
                counterDiv.style.color = '#718096';
            }
        });
        
        // Trigger initial count
        messageTextarea.dispatchEvent(new Event('input'));
    }
    
    // Real-time validation feedback
    setupRealtimeValidation();
});

function submitContactForm(data) {
    const form = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');
    
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Sending...';
    submitButton.disabled = true;
    
    // Simulate API call (in real app, this would be AJAX to PHP)
    setTimeout(() => {
        // Hide form and show success message
        form.style.display = 'none';
        successMessage.style.display = 'block';
        
        // Log data (in real app, this would be sent to server)
        console.log('Contact form submitted:', data);
        
        // Save to session storage
        sessionStorage.setItem('lastContactSubmission', JSON.stringify(data));
        
        // Show notification
        if (window.appUtils) {
            window.appUtils.showNotification('Message sent successfully!', 'success');
        }
        
        // Reset form after 3 seconds
        setTimeout(() => {
            form.reset();
            form.style.display = 'block';
            successMessage.style.display = 'none';
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }, 5000);
    }, 1000);
}

function showContactError(elementId, message) {
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

function clearContactErrors() {
    const errorElements = document.querySelectorAll('#contactForm .error-message');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    const inputs = document.querySelectorAll('#contactForm input, #contactForm select, #contactForm textarea');
    inputs.forEach(input => {
        input.style.borderColor = '';
    });
}

function setupRealtimeValidation() {
    // Name validation
    const nameInput = document.getElementById('contactName');
    if (nameInput) {
        nameInput.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value && value.length >= 2 && /^[a-zA-Z\s]+$/.test(value)) {
                this.style.borderColor = '#48bb78';
            }
        });
    }
    
    // Email validation
    const emailInput = document.getElementById('contactEmail');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            if (this.value && window.appUtils.validateEmail(this.value)) {
                this.style.borderColor = '#48bb78';
            }
        });
    }
    
    // Phone validation
    const phoneInput = document.getElementById('contactPhone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            // Auto-format phone number (basic)
            let value = this.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            
            if (value.length >= 10) {
                this.style.borderColor = '#48bb78';
            }
        });
    }
    
    // Subject validation
    const subjectSelect = document.getElementById('contactSubject');
    if (subjectSelect) {
        subjectSelect.addEventListener('change', function() {
            if (this.value) {
                this.style.borderColor = '#48bb78';
            }
        });
    }
    
    // Message validation
    const messageTextarea = document.getElementById('contactMessage');
    if (messageTextarea) {
        messageTextarea.addEventListener('input', function() {
            const length = this.value.trim().length;
            if (length >= 10 && length <= 1000) {
                this.style.borderColor = '#48bb78';
            } else if (length > 1000) {
                this.style.borderColor = '#f56565';
            } else {
                this.style.borderColor = '';
            }
        });
    }
}

// FAQ Accordion functionality
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        item.style.cursor = 'pointer';
        item.style.transition = 'all 0.3s ease';
        
        item.addEventListener('click', function() {
            // Toggle highlight
            this.style.background = this.style.background === 'rgb(102, 126, 234)' ? '' : '#667eea';
            this.style.color = this.style.color === 'white' ? '' : 'white';
            
            // Reset after 2 seconds
            setTimeout(() => {
                this.style.background = '';
                this.style.color = '';
            }, 2000);
        });
        
        item.addEventListener('mouseenter', function() {
            if (this.style.background !== 'rgb(102, 126, 234)') {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});
