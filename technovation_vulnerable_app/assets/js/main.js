/**
 * TechNovation Solutions - Main JavaScript
 * Contains client-side functionality and some vulnerabilities
 */

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize app
    initializeApp();
});

function initializeApp() {
    console.log('TechNovation Solutions - Initialized');
    
    // Add event listeners
    addEventListeners();
    
    // VULNERABILITY: Sensitive data in console
    if (window.location.search.includes('debug=1')) {
        console.log('Debug mode enabled');
        console.log('Session data:', document.cookie);
    }
}

function addEventListeners() {
    // Search form auto-submit
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        // VULNERABILITY: XSS through auto-search
        searchInput.addEventListener('input', debounce(function(e) {
            if (e.target.value.length > 3) {
                // Auto-search without sanitization
                window.location.href = `index.php?search=${e.target.value}`;
            }
        }, 500));
    }
    
    // Cart quantity validation
    const quantityInputs = document.querySelectorAll('input[name="quantity"]');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 1) this.value = 1;
            if (this.value > 10) this.value = 10;
        });
    });
    
    // Price manipulation detection (client-side only - easily bypassed)
    const priceInputs = document.querySelectorAll('input[name="price"]');
    priceInputs.forEach(input => {
        // VULNERABILITY: Client-side validation only
        const originalPrice = input.value;
        input.addEventListener('change', function() {
            console.warn('Price modification detected!');
            // But we don't actually prevent it...
        });
    });
}

// Utility: Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// VULNERABILITY: Exposed API endpoint in client code
const API_CONFIG = {
    baseURL: '/api',
    endpoints: {
        products: '/api/products.php',
        users: '/api/users.php',  // May not exist but exposed
        admin: '/api/admin.php'    // May not exist but exposed
    },
    // VULNERABILITY: API key in frontend code
    apiKey: 'technovation_2026_key_12345'
};

// Function to fetch products via API
async function fetchProducts() {
    try {
        const response = await fetch(API_CONFIG.endpoints.products);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching products:', error);
    }
}

// VULNERABILITY: Eval usage with user input
function executeCode(code) {
    // Extremely dangerous - for demonstration only
    if (window.location.search.includes('dev=1')) {
        eval(code);
    }
}

// VULNERABILITY: localStorage for sensitive data
function storeUserData(userData) {
    localStorage.setItem('user', JSON.stringify(userData));
    localStorage.setItem('session', document.cookie);
}

// Admin panel functions
if (window.location.pathname.includes('/admin/')) {
    // VULNERABILITY: Admin detection through client-side code
    console.log('Admin panel loaded');
    
    // Backup function with command injection vulnerability
    window.createBackup = function(filename) {
        // This would be exploitable on the server side
        fetch(`dashboard.php?backup=1&filename=${filename}`)
            .then(response => response.text())
            .then(result => console.log('Backup created:', result));
    };
}

// VULNERABILITY: DOM-based XSS
function displayMessage(message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'alert alert-info';
    // Unsafe: directly inserting HTML
    messageDiv.innerHTML = message;
    document.body.insertBefore(messageDiv, document.querySelector('main'));
}

// Get URL parameters (vulnerable to XSS)
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    // VULNERABILITY: No sanitization
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

// Check if there's a message parameter and display it
if (getUrlParameter('msg')) {
    displayMessage(getUrlParameter('msg'));
}

// VULNERABILITY: CSRF token generation (weak)
function generateCSRFToken() {
    // Predictable token generation
    return Math.random().toString(36).substring(7);
}

// Form validation (weak client-side only)
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    // VULNERABILITY: Client-side validation only
    const inputs = form.querySelectorAll('input[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value) {
            isValid = false;
            input.style.borderColor = 'red';
        } else {
            input.style.borderColor = '';
        }
    });
    
    return isValid;
}

// Password strength checker (inadequate)
function checkPasswordStrength(password) {
    // VULNERABILITY: Weak password requirements
    if (password.length >= 4) {
        return 'strong'; // Obviously not strong!
    }
    return 'weak';
}

// Export for testing
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        fetchProducts,
        getUrlParameter,
        checkPasswordStrength
    };
}

console.log('Main.js loaded - Version 1.0');
