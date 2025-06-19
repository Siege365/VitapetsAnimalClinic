<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitapets Animal Clinic - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/loginpage.css">
    <script src="../javascript/googlelogin.js" defer type="module"></script>
    <style>
        .message-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 300px;
            transition: all 0.3s ease;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
            padding: 12px 16px;
            margin-bottom: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: none;
        }
        
        .success-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
            padding: 12px 16px;
            margin-bottom: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: none;
        }
        
        .close-btn {
            float: right;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            margin-left: 10px;
            color: inherit;
        }
    </style>
</head>
<body>
    <?php
    // Start session at the very beginning of the file
    session_start();
    ?>

    <div class="message-container">
        <div id="errorMessage" class="error-message">
            <button class="close-btn" onclick="closeMessage('errorMessage')">×</button>
            <span id="errorText"></span>
        </div>
        <div id="successMessage" class="success-message">
            <button class="close-btn" onclick="closeMessage('successMessage')">×</button>
            <span id="successText"></span>
        </div>
    </div>
    <div class="main-container">
        <div class="logo-container">
            <img src="../img/logo.png" alt="Vitapets Logo" class="form-logo">
        </div>
        <div class="overlay">
            <div class="login-form">
                <h2>Login</h2>
                <form id="loginForm" method="post" action="../php/login_handler.php">
                    <input type="text" id="useremail" name="email" placeholder="Email" required>
                    <input type="password" id="userpassword" name="password" placeholder="Password" required>
                    <div class="show-pass">
                        <label>
                            <input id="togglePass" type="checkbox"> Show Password
                        </label>
                    </div>
                    <a href="#" class="forgot">Forgot Username/Password</a>
                    <button type="submit" id="login" class="login-btn">Login</button>
                </form>
                <p class="signup-text">Don't have an account? <a href="../html/register.php">Sign up</a></p>
                <div class="social-login">
                    <p>Or continue with</p>
                    <div class="social-icons">
                        <img src="../img/search.png" alt="Google" id="googleLogin" class="social-icon">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Toggle password visibility
        const passwordInput = document.getElementById('userpassword');
        const toggleCheckbox = document.getElementById('togglePass');

        toggleCheckbox.addEventListener('change', () => {
            passwordInput.type = toggleCheckbox.checked ? 'text' : 'password';
        });
        
        // Function to show error message
        function showError(message) {
            const errorElement = document.getElementById('errorMessage');
            document.getElementById('errorText').textContent = message;
            errorElement.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                errorElement.style.display = 'none';
            }, 5000);
        }
        
        // Function to show success message
        function showSuccess(message) {
            const successElement = document.getElementById('successMessage');
            document.getElementById('successText').textContent = message;
            successElement.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                successElement.style.display = 'none';
            }, 5000);
        }
        
        // Function to close message
        function closeMessage(elementId) {
            document.getElementById(elementId).style.display = 'none';
        }
        
        // Check for session messages immediately when page loads
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            // Display any error messages from session
            if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
                echo "showError('" . addslashes($_SESSION['error_message']) . "');";
                unset($_SESSION['error_message']);
            }
            
            // Display any success messages from session
            if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                echo "showSuccess('" . addslashes($_SESSION['success_message']) . "');";
                unset($_SESSION['success_message']);
            }
            ?>
            
            // Also check URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const errorMsg = urlParams.get('error');
            const successMsg = urlParams.get('success');
            
            if (errorMsg) {
                showError(decodeURIComponent(errorMsg));
            }
            
            if (successMsg) {
                showSuccess(decodeURIComponent(successMsg));
            }
        });
    </script>
</body>
</html>