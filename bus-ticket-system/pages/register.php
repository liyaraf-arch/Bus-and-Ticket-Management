<?php
require_once '../includes/config.php';

if(isLoggedIn()) {
    redirect('index.php');
}

$page_title = 'Register';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="form-container" style="max-width: 500px; margin: 50px auto;">
            <div style="text-align: center; margin-bottom: 30px;">
                <i class="fas fa-user-plus" style="font-size: 50px; color: var(--primary-green);"></i>
                <h2 style="color: var(--dark-green); margin-top: 10px;">Create New Account</h2>
                <p style="color: #666;">Join thousands of happy travelers</p>
            </div>
            
            <?php displayMessage(); ?>
            
            <form id="registerForm" method="POST" action="../process-register.php">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user" style="color: var(--primary-green);"></i>
                        Full Name
                    </label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Enter your full name"
                           style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 5px;">
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope" style="color: var(--primary-green);"></i>
                        Email Address
                    </label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Enter your email"
                           style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 5px;">
                </div>
                
                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone" style="color: var(--primary-green);"></i>
                        Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone" required 
                           placeholder="01XXXXXXXXX"
                           pattern="01[3-9][0-9]{8}"
                           style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 5px;">
                    <small style="color: #999;">Bangladeshi number (01XXXXXXXXX)</small>
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock" style="color: var(--primary-green);"></i>
                        Password
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" required 
                               placeholder="Minimum 6 characters"
                               minlength="6"
                               style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 5px;">
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" 
                              onclick="togglePassword('password', 'toggleIcon1')">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </span>
                    </div>
                    <div class="password-strength" style="margin-top: 5px;">
                        <div style="height: 5px; background: #ddd; border-radius: 3px;">
                            <div id="strengthBar" style="height: 100%; width: 0; background: red; border-radius: 3px;"></div>
                        </div>
                        <small id="strengthText" style="color: #999;">Enter password</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock" style="color: var(--primary-green);"></i>
                        Confirm Password
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               placeholder="Re-enter password"
                               style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 5px;">
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" 
                              onclick="togglePassword('confirm_password', 'toggleIcon2')">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </span>
                    </div>
                    <small id="matchMessage" style="color: #999;"></small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="terms" required style="accent-color: var(--primary-green);">
                        I agree to the <a href="terms.php" style="color: var(--primary-green);">Terms & Conditions</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-green" style="width: 100%; padding: 12px; font-size: 16px;">
                    <i class="fas fa-user-plus"></i>
                    Register
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <p>Already have an account? 
                    <a href="login.php" style="color: var(--primary-green); font-weight: bold;">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            
            if(field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            
            if(password.length >= 6) strength += 25;
            if(password.match(/[a-z]+/)) strength += 25;
            if(password.match(/[A-Z]+/)) strength += 25;
            if(password.match(/[0-9]+/)) strength += 25;
            
            strengthBar.style.width = strength + '%';
            
            if(strength < 50) {
                strengthBar.style.background = 'red';
                strengthText.textContent = 'Weak password';
            } else if(strength < 75) {
                strengthBar.style.background = 'orange';
                strengthText.textContent = 'Medium password';
            } else {
                strengthBar.style.background = 'green';
                strengthText.textContent = 'Strong password';
            }
        });
        
        // Password match checker
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const matchMessage = document.getElementById('matchMessage');
            
            if(password === confirm) {
                matchMessage.textContent = '✓ Passwords match';
                matchMessage.style.color = 'green';
            } else {
                matchMessage.textContent = '✗ Passwords do not match';
                matchMessage.style.color = 'red';
            }
        });
        
        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            const phone = document.getElementById('phone').value;
            const terms = document.querySelector('input[name="terms"]').checked;
            
            if(password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
            
            if(!terms) {
                e.preventDefault();
                alert('You must agree to the terms and conditions');
            }
            
            const phoneRegex = /^01[3-9][0-9]{8}$/;
            if(!phoneRegex.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid Bangladeshi phone number');
            }
        });
    </script>
    
    <?php include '../includes/footer.php'; ?>

    <!-- pages/register.php - Add this at the end of file -->

<script>
    // Check if registration was successful from URL
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('registered') === 'success') {
        showToast('✓ Registration successful! Please login to continue.', 'success');
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 2000);
    }
    
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast-notification ' + (type === 'error' ? 'error' : '');
        toast.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 4000);
    }
</script>
</body>
</html>