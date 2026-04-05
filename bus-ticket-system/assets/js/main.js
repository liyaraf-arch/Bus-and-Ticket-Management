// ========================================
// Bus Ticket Management System
// Main JavaScript File
// Version: 2.0
// ========================================

// Global Variables

const siteConfig = {
    name: 'TR OTTER.com',
    currency: '৳',
    apiUrl: 'http://localhost/bus-ticket-system/',
    debug: true
};

let currentUser = null;
let selectedLanguage = 'bn';
let cart = [];

// ===== DOCUMENT READY =====
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    setupEventListeners();
    checkUserSession();
    initializeTooltips();
    setupDatePickers();
    loadUserPreferences();
    initializeChat();
});

// ===== INITIALIZATION =====
function initializeApp() {
    console.log('🚀 Bus Ticket System Initialized');
    applyGreenTheme();
    setupMobileMenu();
    initializeSearchForm();
    loadTrendingDestinations();
    setupFormValidation();
}

function applyGreenTheme() {
    const root = document.documentElement;
    root.style.setProperty('--primary-green', '#2ecc71');
    root.style.setProperty('--dark-green', '#27ae60');
    root.style.setProperty('--light-green', '#a8e6cf');
}

// ===== EVENT LISTENERS =====
function setupEventListeners() {
    // Search Form
    const searchForm = document.getElementById('searchForm');
    if(searchForm) {
        searchForm.addEventListener('submit', handleSearchSubmit);
    }

    // Login Form
    const loginForm = document.getElementById('loginForm');
    if(loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    // Registration Form
    const registerForm = document.getElementById('registerForm');
    if(registerForm) {
        registerForm.addEventListener('submit', handleRegistration);
    }

    // Mobile Menu
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    if(mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
    }

    // Back to Top
    const backToTop = document.getElementById('backToTop');
    if(backToTop) {
        window.addEventListener('scroll', toggleBackToTop);
        backToTop.addEventListener('click', scrollToTop);
    }

    // Search Input with debounce
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.addEventListener('input', debounce(handleSearchInput, 500));
    }
}

// ===== SESSION MANAGEMENT =====
function checkUserSession() {
    fetch('../includes/check-session.php')
        .then(response => response.json())
        .then(data => {
            if(data.logged_in) {
                currentUser = data.user;
                updateUIForLoggedInUser();
                showNotification(`Welcome back, ${data.user.name}!`, 'success');
            }
        })
        .catch(error => {
            if(siteConfig.debug) console.error('Session check failed:', error);
        });
}

function updateUIForLoggedInUser() {
    const loginBtn = document.querySelector('.login-btn');
    const userMenu = document.querySelector('.user-menu');
    const userNameSpan = document.getElementById('userName');
    
    if(loginBtn) loginBtn.style.display = 'none';
    if(userMenu) userMenu.style.display = 'block';
    if(userNameSpan && currentUser) userNameSpan.textContent = currentUser.name;
}

// ===== SEARCH FUNCTIONALITY =====
function handleSearchSubmit(event) {
    event.preventDefault();
    
    const from = document.getElementById('from')?.value;
    const to = document.getElementById('to')?.value;
    const date = document.getElementById('date')?.value;
    const tripType = document.querySelector('input[name="tripType"]:checked')?.value || 'oneway';
    
    if(!validateSearchInput(from, to, date)) return;
    
    showLoadingSpinner();
    
    fetch(`pages/search.php?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&date=${date}&type=${tripType}`)
        .then(response => response.text())
        .then(data => {
            hideLoadingSpinner();
            document.getElementById('searchResults').innerHTML = data;
            showNotification('Search completed!', 'success');
        })
        .catch(error => {
            hideLoadingSpinner();
            showNotification('Search failed. Please try again.', 'error');
            if(siteConfig.debug) console.error('Search error:', error);
        });
}

function validateSearchInput(from, to, date) {
    if(!from || !to || !date) {
        showNotification('Please fill in all fields', 'error');
        return false;
    }
    
    if(from === to) {
        showNotification('From and To cities cannot be same', 'error');
        return false;
    }
    
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if(selectedDate < today) {
        showNotification('Please select a future date', 'error');
        return false;
    }
    
    return true;
}

// ===== AUTHENTICATION =====
function handleLogin(event) {
    event.preventDefault();
    
    const email = document.getElementById('email')?.value;
    const password = document.getElementById('password')?.value;
    const remember = document.getElementById('remember')?.checked || false;
    
    if(!email || !password) {
        showNotification('Please enter email and password', 'error');
        return;
    }
    
    showLoadingSpinner();
    
    fetch('../process-login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&remember=${remember}`
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingSpinner();
        
        if(data.success) {
            showNotification('Login successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '../index.php';
            }, 1500);
        } else {
            showNotification(data.message || 'Login failed', 'error');
        }
    })
    .catch(error => {
        hideLoadingSpinner();
        showNotification('Login failed. Please try again.', 'error');
        if(siteConfig.debug) console.error('Login error:', error);
    });
}

function handleRegistration(event) {
    event.preventDefault();
    
    const formData = {
        name: document.getElementById('name')?.value,
        email: document.getElementById('email')?.value,
        phone: document.getElementById('phone')?.value,
        password: document.getElementById('password')?.value,
        confirm_password: document.getElementById('confirm_password')?.value
    };
    
    if(!validateRegistrationForm(formData)) return;
    
    showLoadingSpinner();
    
    fetch('../process-register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingSpinner();
        
        if(data.success) {
            showNotification('Registration successful! Please login.', 'success');
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 1500);
        } else {
            showNotification(data.message || 'Registration failed', 'error');
        }
    })
    .catch(error => {
        hideLoadingSpinner();
        showNotification('Registration failed. Please try again.', 'error');
        if(siteConfig.debug) console.error('Registration error:', error);
    });
}

function validateRegistrationForm(data) {
    if(!data.name || !data.email || !data.phone || !data.password) {
        showNotification('Please fill in all fields', 'error');
        return false;
    }
    
    if(data.password !== data.confirm_password) {
        showNotification('Passwords do not match', 'error');
        return false;
    }
    
    if(data.password.length < 6) {
        showNotification('Password must be at least 6 characters', 'error');
        return false;
    }
    
    if(!validateEmail(data.email)) {
        showNotification('Please enter a valid email address', 'error');
        return false;
    }
    
    if(!validateBangladeshPhone(data.phone)) {
        showNotification('Please enter a valid Bangladeshi phone number', 'error');
        return false;
    }
    
    return true;
}

// ===== NOTIFICATION SYSTEM =====
function showNotification(message, type = 'info', duration = 5000) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${icons[type] || icons.info}"></i>
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    notification.querySelector('.notification-close').addEventListener('click', () => {
        notification.remove();
    });
    
    setTimeout(() => {
        if(notification.parentNode) {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, duration);
}

// ===== LOADING SPINNER =====
function showLoadingSpinner() {
    if(document.getElementById('globalSpinner')) return;
    
    const spinner = document.createElement('div');
    spinner.className = 'loading-spinner';
    spinner.id = 'globalSpinner';
    spinner.innerHTML = `
        <div class="spinner"></div>
        <p style="color: var(--primary-green); font-weight: bold;">Loading...</p>
    `;
    
    document.body.appendChild(spinner);
}

function hideLoadingSpinner() {
    const spinner = document.getElementById('globalSpinner');
    if(spinner) {
        spinner.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => spinner.remove(), 300);
    }
}

// ===== MOBILE MENU =====
function setupMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    if(mobileMenu) {
        mobileMenu.style.display = 'none';
    }
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    if(mobileMenu) {
        if(mobileMenu.style.display === 'none' || !mobileMenu.style.display) {
            mobileMenu.style.display = 'block';
            mobileMenu.style.animation = 'slideDown 0.3s ease';
        } else {
            mobileMenu.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => {
                mobileMenu.style.display = 'none';
            }, 300);
        }
    }
}

// ===== BACK TO TOP =====
function toggleBackToTop() {
    const backToTop = document.getElementById('backToTop');
    if(backToTop) {
        if(window.scrollY > 300) {
            backToTop.style.display = 'flex';
        } else {
            backToTop.style.display = 'none';
        }
    }
}

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// ===== DATE PICKER =====
function setupDatePickers() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    
    dateInputs.forEach(input => {
        if(!input.value) {
            input.min = today;
        }
        
        input.addEventListener('change', function() {
            validateDate(this);
        });
    });
}

function validateDate(input) {
    const selectedDate = new Date(input.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if(selectedDate < today) {
        input.value = today.toISOString().split('T')[0];
        showNotification('Please select a future date', 'warning');
    }
}

// ===== FORM VALIDATION =====
function setupFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    });
}

function validateField(field) {
    if(!field.value) {
        field.classList.add('error');
        return false;
    }
    
    if(field.type === 'email' && !validateEmail(field.value)) {
        field.classList.add('error');
        return false;
    }
    
    if(field.id === 'phone' && !validateBangladeshPhone(field.value)) {
        field.classList.add('error');
        return false;
    }
    
    field.classList.remove('error');
    return true;
}

// ===== VALIDATION HELPERS =====
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validateBangladeshPhone(phone) {
    const re = /^01[3-9]\d{8}$/;
    return re.test(phone);
}

// ===== FORMATTING HELPERS =====
function formatCurrency(amount) {
    return `${siteConfig.currency} ${Number(amount).toLocaleString('bn-BD')}`;
}

function formatDate(dateString, format = 'dd MMM, yyyy') {
    const date = new Date(dateString);
    const options = {
        'dd MMM, yyyy': { day: '2-digit', month: 'short', year: 'numeric' },
        'dd/mm/yyyy': { day: '2-digit', month: '2-digit', year: 'numeric' },
        'yyyy-mm-dd': { year: 'numeric', month: '2-digit', day: '2-digit' }
    };
    
    return date.toLocaleDateString('en-GB', options[format] || options['dd MMM, yyyy']);
}

function formatTime(timeString, format = '12h') {
    const time = new Date(`2000-01-01T${timeString}`);
    
    if(format === '12h') {
        return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }
    
    return time.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
}

// ===== DEBOUNCE FUNCTION =====
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

// ===== SEARCH SUGGESTIONS =====
function handleSearchInput(event) {
    const query = event.target.value;
    
    if(query.length > 2) {
        fetchSuggestions(query);
    }
}

function fetchSuggestions(query) {
    fetch(`api/search-suggestions.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySuggestions(data);
        })
        .catch(error => {
            if(siteConfig.debug) console.error('Suggestion fetch failed:', error);
        });
}

function displaySuggestions(suggestions) {
    const suggestionBox = document.getElementById('searchSuggestions');
    if(!suggestionBox) return;
    
    if(suggestions.length > 0) {
        suggestionBox.innerHTML = suggestions.map(s => 
            `<div class="suggestion-item" onclick="selectSuggestion('${s}')">${s}</div>`
        ).join('');
        suggestionBox.style.display = 'block';
    } else {
        suggestionBox.style.display = 'none';
    }
}

function selectSuggestion(value) {
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.value = value;
        document.getElementById('searchSuggestions').style.display = 'none';
    }
}

// ===== TRENDING DESTINATIONS =====
function loadTrendingDestinations() {
    const trendingContainer = document.getElementById('trendingDestinations');
    if(!trendingContainer) return;
    
    const destinations = [
        { from: 'Dhaka', to: 'Chittagong', price: 1200 },
        { from: 'Dhaka', to: 'Sylhet', price: 800 },
        { from: 'Dhaka', to: 'Cox\'s Bazar', price: 1500 },
        { from: 'Chittagong', to: 'Dhaka', price: 1200 },
        { from: 'Sylhet', to: 'Dhaka', price: 800 }
    ];
    
    trendingContainer.innerHTML = destinations.map(dest => `
        <div class="route-card" onclick="searchRoute('${dest.from}', '${dest.to}')">
            <h4>${dest.from} → ${dest.to}</h4>
            <p>Starting from ${formatCurrency(dest.price)}</p>
        </div>
    `).join('');
}

function searchRoute(from, to) {
    window.location.href = `pages/search.php?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}`;
}

// ===== USER PREFERENCES =====
function loadUserPreferences() {
    const theme = localStorage.getItem('theme') || 'green';
    const language = localStorage.getItem('language') || 'bn';
    
    selectedLanguage = language;
    applyTheme(theme);
}

function applyTheme(theme) {
    if(theme === 'green') {
        applyGreenTheme();
    }
}

// ===== CHAT FUNCTIONALITY =====
function initializeChat() {
    const chatWidget = document.getElementById('chatWidget');
    if(!chatWidget) return;
    
    window.toggleChat = function() {
        const chatBox = document.getElementById('chatBox');
        if(chatBox) {
            chatBox.style.display = chatBox.style.display === 'none' ? 'block' : 'none';
        }
    };
    
    window.sendChatMessage = function() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        
        if(message) {
            const messages = document.getElementById('chatMessages');
            messages.innerHTML += `
                <div style="background: var(--primary-green); color: white; padding: 10px; border-radius: 10px; margin: 10px 0; text-align: right;">
                    ${message}
                </div>
            `;
            input.value = '';
            messages.scrollTop = messages.scrollHeight;
            
            // Simulate response
            setTimeout(() => {
                messages.innerHTML += `
                    <div style="background: #f0f0f0; padding: 10px; border-radius: 10px; margin: 10px 0;">
                        Thank you for your message. A support agent will respond shortly.
                    </div>
                `;
                messages.scrollTop = messages.scrollHeight;
            }, 1000);
        }
    };
}

// ===== BOOKING FUNCTIONS =====
function addToCart(item) {
    cart.push(item);
    updateCartCounter();
    showNotification('Item added to cart', 'success');
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartCounter();
}

function updateCartCounter() {
    const counter = document.getElementById('cartCounter');
    if(counter) {
        counter.textContent = cart.length;
        counter.style.display = cart.length > 0 ? 'flex' : 'none';
    }
}

// ===== PDF GENERATION =====
function downloadTicket(bookingId) {
    showLoadingSpinner();
    
    fetch(`download-ticket.php?booking_id=${bookingId}`)
        .then(response => response.json())
        .then(data => {
            hideLoadingSpinner();
            
            if(data.success) {
                generatePDF(data.booking);
            } else {
                showNotification('Failed to generate ticket', 'error');
            }
        })
        .catch(error => {
            hideLoadingSpinner();
            showNotification('Download failed', 'error');
            if(siteConfig.debug) console.error('PDF download error:', error);
        });
}

function generatePDF(bookingData) {
    // PDF generation logic here
    console.log('Generating PDF for:', bookingData);
}

// ===== EXPORT FUNCTIONS =====
window.busTicketSystem = {
    showNotification,
    formatCurrency,
    formatDate,
    formatTime,
    validateEmail,
    validateBangladeshPhone,
    addToCart,
    downloadTicket,
    searchRoute
};