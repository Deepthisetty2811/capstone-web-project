// ===================================
// Productivity Calculator
// Dynamic Feature using JavaScript
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    // Update efficiency slider value display
    const efficiencySlider = document.getElementById('efficiencyGain');
    const efficiencyValue = document.getElementById('efficiencyValue');
    
    if (efficiencySlider && efficiencyValue) {
        efficiencySlider.addEventListener('input', function() {
            efficiencyValue.textContent = this.value + '%';
        });
    }
});

function calculateSavings() {
    // Get input values
    const tasksPerDay = parseInt(document.getElementById('tasksPerDay').value) || 0;
    const avgTimePerTask = parseInt(document.getElementById('avgTimePerTask').value) || 0;
    const efficiencyGain = parseInt(document.getElementById('efficiencyGain').value) || 0;
    
    // Validate inputs
    if (tasksPerDay <= 0 || avgTimePerTask <= 0) {
        alert('Please enter valid values for tasks and time.');
        return;
    }
    
    // Calculate daily time spent
    const dailyTimeMinutes = tasksPerDay * avgTimePerTask;
    
    // Calculate time saved with efficiency gain
    const dailySavingsMinutes = (dailyTimeMinutes * efficiencyGain) / 100;
    const weeklySavingsMinutes = dailySavingsMinutes * 5; // 5 working days
    const monthlySavingsMinutes = dailySavingsMinutes * 22; // ~22 working days
    const yearlySavingsMinutes = dailySavingsMinutes * 260; // ~260 working days
    
    // Convert to hours and minutes
    const formatTime = (minutes) => {
        const hours = Math.floor(minutes / 60);
        const mins = Math.round(minutes % 60);
        
        if (hours > 0 && mins > 0) {
            return `${hours}h ${mins}m`;
        } else if (hours > 0) {
            return `${hours}h`;
        } else {
            return `${mins}m`;
        }
    };
    
    // Update results with animation
    const results = document.getElementById('results');
    results.style.opacity = '0';
    
    setTimeout(() => {
        document.getElementById('dailySavings').textContent = formatTime(dailySavingsMinutes);
        document.getElementById('weeklySavings').textContent = formatTime(weeklySavingsMinutes);
        document.getElementById('monthlySavings').textContent = formatTime(monthlySavingsMinutes);
        document.getElementById('yearlySavings').textContent = formatTime(yearlySavingsMinutes);
        
        results.style.transition = 'opacity 0.5s ease';
        results.style.opacity = '1';
        
        // Show success notification
        if (window.appUtils) {
            const yearlyHours = Math.floor(yearlySavingsMinutes / 60);
            window.appUtils.showNotification(
                `You could save ${yearlyHours} hours per year!`, 
                'success'
            );
        }
    }, 200);
    
    // Add visual feedback to button
    const button = event.target;
    button.textContent = 'Calculating...';
    button.disabled = true;
    
    setTimeout(() => {
        button.textContent = 'Calculate Savings';
        button.disabled = false;
    }, 500);
}

// Interactive input feedback
document.addEventListener('DOMContentLoaded', function() {
    const numberInputs = document.querySelectorAll('input[type="number"]');
    
    numberInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Add visual feedback for input
            this.style.borderColor = '#667eea';
            
            setTimeout(() => {
                this.style.borderColor = '';
            }, 300);
        });
        
        // Prevent negative values
        input.addEventListener('change', function() {
            if (parseInt(this.value) < parseInt(this.min)) {
                this.value = this.min;
            }
            if (parseInt(this.value) > parseInt(this.max)) {
                this.value = this.max;
            }
        });
    });
});