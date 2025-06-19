<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitapets Animal Clinic - Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/register.css">
    <style>
       .error-message {
            color: #e74c3c;
            background-color: #fadbd8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
        }
        
        .success-message {
                    color: #2ecc71;
                    background-color: #d4efdf;
                    padding: 10px;
                    border-radius: 5px;
                    margin-bottom: 15px;
                    font-size: 14px;
                    text-align: center;
        }
                
        .form-validation-error {
                    border: 1px solid #e74c3c !important;
                }
                
        .small-text {
                    font-size: 12px;
                    color: #7f8c8d;
                    margin-top: 5px;
        }     
        </style>
</head>
<body>
    <div class="main-container">
        <div class="logo-container">
            <img src="../img/logo.png" alt="Vitapets Logo" class="form-logo">
        </div>
        <div class="form-container">
            <h1>Sign Up</h1>
            
            <!-- Error message display -->
            <div id="errorContainer" class="error-message" style="display: none;"></div>
            
            <!-- Success message display -->
            <div id="successContainer" class="success-message" style="display: none;"></div>
            
            <form id="registrationForm" action="../php/register_handler.php" method="POST" onsubmit="return validateForm()">
                <input type="email" id="useremail" name="email" placeholder="Email" required>
                <div class="name-fields">
                    <input type="text" id="userfname" name="first_name" placeholder="First Name" required>
                    <input type="text" id="userlname" name="last_name" placeholder="Last Name" required>
                </div>             
                <input type="text" id="userphone" name="phone" placeholder="Phone Number" required>
                                  
                <input type="password" id="userpassword" name="password" placeholder="Password" required>
                <input type="password" id="userconfirmpassword" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" id="createaccount">Create Account</button>
            </form>
            <p>Already have an account? <a href="../html/login.php">Login Here</a></p>
        </div>
    </div>
    
    <script>
        // Display PHP session error/success messages on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Process URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const errorParam = urlParams.get('error');
            const successParam = urlParams.get('success');
            
            // Check for PHP error messages in URL parameters
            if (errorParam) {
                showError(decodeURIComponent(errorParam));
            }
            
            // Check for PHP success messages in URL parameters
            if (successParam) {
                showSuccess(decodeURIComponent(successParam));
            }
            
            // Restore form data from PHP session if available
            <?php
            session_start();
            if (isset($_SESSION['form_data'])) {
                echo "
                document.getElementById('useremail').value = '" . htmlspecialchars($_SESSION['form_data']['email']) . "';
                document.getElementById('userfname').value = '" . htmlspecialchars($_SESSION['form_data']['first_name']) . "';
                document.getElementById('userlname').value = '" . htmlspecialchars($_SESSION['form_data']['last_name']) . "';
                document.getElementById('userphone').value = '" . htmlspecialchars($_SESSION['form_data']['phone']) . "';
                ";
                // Clear the session data after using it
                unset($_SESSION['form_data']);
            }
            
            // Display any error messages from session
            if (isset($_SESSION['error_message'])) {
                echo "showError('" . htmlspecialchars($_SESSION['error_message']) . "');";
                unset($_SESSION['error_message']);
            }
            
            // Display any success messages from session
            if (isset($_SESSION['success_message'])) {
                echo "showSuccess('" . htmlspecialchars($_SESSION['success_message']) . "');";
                unset($_SESSION['success_message']);
            }
            ?>
        });
        
        // Form validation function
        function validateForm() {
            // Reset previous error states
            clearErrors();
            
            let isValid = true;
            const email = document.getElementById('useremail').value;
            const firstName = document.getElementById('userfname').value;
            const lastName = document.getElementById('userlname').value;
            const phone = document.getElementById('userphone').value;
            const password = document.getElementById('userpassword').value;
            const confirmPassword = document.getElementById('userconfirmpassword').value;
            
            // Check required fields
            if (!email || !firstName || !lastName || !phone || !password || !confirmPassword) {
                showError('All fields are required');
                isValid = false;
            }
            
            // Validate email format
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                document.getElementById('useremail').classList.add('form-validation-error');
                showError('Invalid email format');
                isValid = false;
            }
            
            // Validate phone number format
            const phonePattern = /^[0-9\-\(\)\/\+\s]*$/;
            if (!phonePattern.test(phone)) {
                document.getElementById('userphone').classList.add('form-validation-error');
                showError('Invalid phone number format');
                isValid = false;
            }
            
            // Check password length
            if (password.length < 8) {
                document.getElementById('userpassword').classList.add('form-validation-error');
                showError('Password must be at least 8 characters long');
                isValid = false;
            }
            
            // Check if passwords match
            if (password !== confirmPassword) {
                document.getElementById('userpassword').classList.add('form-validation-error');
                document.getElementById('userconfirmpassword').classList.add('form-validation-error');
                showError('Passwords do not match');
                isValid = false;
            }
            
            return isValid;
        }
        
        // Show error message
        function showError(message) {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
            
            // Hide success message if shown
            document.getElementById('successContainer').style.display = 'none';
        }
        
        // Show success message
        function showSuccess(message) {
            const successContainer = document.getElementById('successContainer');
            successContainer.textContent = message;
            successContainer.style.display = 'block';
            
            // Hide error message if shown
            document.getElementById('errorContainer').style.display = 'none';
        }
        function showSuccessAndRedirect(message) {
            showSuccess(message);
            // Disable the form
            document.getElementById('registrationForm').style.pointerEvents = 'none';
            document.getElementById('registrationForm').style.opacity = '0.7';
            
            // Redirect to login page after 3 seconds
            setTimeout(function() {
                window.location.href = 'login.html';
            }, 3000);
        }
        // Clear all error indicators
        function clearErrors() {
            document.getElementById('errorContainer').style.display = 'none';
            
            // Remove error class from all inputs
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.classList.remove('form-validation-error');
            });
        }
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "showSuccessAndRedirect('" . htmlspecialchars($_SESSION['success_message']) . "');";
            unset($_SESSION['success_message']);
        }
        ?>
    </script>
</body>
</html>