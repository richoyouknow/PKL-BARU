// Get elements
const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');
const mobileToggle = document.getElementById('mobileToggle');
const toggleText = document.getElementById('toggleText');

// Desktop/Tablet Register Button
if (registerBtn) {
    registerBtn.addEventListener('click', () => {
        container.classList.add('right-panel-active');
    });
}

// Desktop/Tablet Login Button
if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        container.classList.remove('right-panel-active');
    });
}

// Mobile Toggle Button
if (mobileToggle) {
    mobileToggle.addEventListener('click', () => {
        container.classList.toggle('right-panel-active');

        // Update button text based on active panel
        if (container.classList.contains('right-panel-active')) {
            toggleText.textContent = 'Already have an account? Login';
        } else {
            toggleText.textContent = "Don't have an account? Register";
        }
    });
}

// Auto-show register form if there are validation errors
document.addEventListener('DOMContentLoaded', () => {
    // Check if register form has errors
    const registerForm = document.querySelector('.register-container form');
    const registerErrors = registerForm.querySelectorAll('.text-danger, .alert-danger');

    let hasRegisterErrors = false;
    registerErrors.forEach(error => {
        if (error.textContent.trim() !== '') {
            hasRegisterErrors = true;
        }
    });

    // Check if login form has errors
    const loginForm = document.querySelector('.login-container form');
    const loginErrors = loginForm.querySelectorAll('.text-danger, .alert-danger');

    let hasLoginErrors = false;
    loginErrors.forEach(error => {
        if (error.textContent.trim() !== '') {
            hasLoginErrors = true;
        }
    });

    // Show appropriate form based on errors
    if (hasRegisterErrors) {
        container.classList.add('right-panel-active');
        if (mobileToggle && toggleText) {
            toggleText.textContent = 'Already have an account? Login';
        }
    } else if (hasLoginErrors) {
        container.classList.remove('right-panel-active');
        if (mobileToggle && toggleText) {
            toggleText.textContent = "Don't have an account? Register";
        }
    }
});

// Prevent double submission on mobile
const forms = document.querySelectorAll('form');
forms.forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Please wait...';

            // Re-enable after 3 seconds as fallback
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = this.closest('.login-container') ? 'Login' : 'Register';
            }, 3000);
        }
    });
});

// Handle responsive behavior on window resize
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        const width = window.innerWidth;

        // Reset to login form on desktop if on register
        if (width > 480 && container.classList.contains('right-panel-active')) {
            // Keep the state, just ensure proper display
            console.log('Desktop mode: Maintaining current state');
        }
    }, 250);
});

// Smooth scroll for mobile landscape
if (window.innerWidth <= 900 && window.innerHeight <= 500) {
    window.addEventListener('load', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}
