<?php
// filepath: c:\xampp\htdocs\Vitapets\client\includes\sticky_navbar.php

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get login status from session
$is_logged_in = isset($_SESSION['user_id']);
?>
<link rel="stylesheet" href="../CSS/includes/sticky_navbar.css">

<!-- Sticky Navigation Bar - Hidden initially, appears on scroll -->
<div class="sticky-navbar" id="stickyNav">
    <div class="contact-info">
        <span class="location">
            <img src="../img/maps-and-flags.png" alt="Location">
            1 Matina Aplaya Road, Talomo, Davao City, 8000 Davao del Sur
        </span>
        <span class="phone">
            <img src="../img/telephone.png" alt="Phone">
            Call us today! +63 932 184 1256
        </span>
    </div>    <div class="nav-container">
        <!-- Logo and Nav Menu in a centered grouping -->
        <div class="logo">
            <img src="../img/logobgremove 3.png" alt="Vitapets Logo">
            <span class="brand-name">VITAPETS ANIMAL CLINIC AND PET SUPPLIES</span>
        </div>
        <div class="nav-menu">
            <ul>
                <li><a href="../html/mainpage.php">Home</a></li>
                <li><a href="../html/OurServices.php">Services</a></li>
                <li><a href="../html/aboutUs.php">About Us</a></li>
                <li><a href="../html/ContactUs.php">Contact Us</a></li>
            </ul>
        </div>
        
        <!-- Action buttons positioned to the right -->
        <div class="action-buttons">
            <button class="book-btn" onclick="location.href='../html/BookSchedule.php'">
                Book Schedule
            </button>
            <?php if($is_logged_in): ?>
                <div class="profile" id="stickyProfileToggle">
                    <img src="../img/right-arrow.png" alt="Arrow" class="arrow">
                    <img src="<?php echo (!empty($_SESSION['avatar_url'])) ? htmlspecialchars($_SESSION['avatar_url'], ENT_QUOTES, 'UTF-8') : '../img/profile-user.png'; ?>"
                        alt="Profile" class="profilepic"
                        referrerpolicy="no-referrer"
                        onerror="this.onerror=null;this.src='../img/profile-user.png';">                  
                </div>
                <!-- Sticky Profile dropdown menu -->
                <div class="profile-dropdown" id="stickyProfileDropdown">
                    <ul>
                        <li><a href="../html/editProfile.php">Account</a></li>
                        <li><a href="../html/userappointments.php">Appointments</a></li>
                        <li><a href="../php/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <button class="login-btn" onclick="location.href='../html/login.php'">
                    Login / Sign Up
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
// Sticky Navigation Script
document.addEventListener('DOMContentLoaded', function() {
    const stickyNav = document.getElementById('stickyNav');
    const headerSection = document.querySelector('.head');
    const stickyProfileToggle = document.getElementById('stickyProfileToggle');
    const stickyProfileDropdown = document.getElementById('stickyProfileDropdown');
    
    // Calculate when to show the sticky nav (after header is scrolled past)
    function handleStickyNav() {
        if (!stickyNav || !headerSection) return;
        const headerBottom = headerSection.offsetTop + headerSection.offsetHeight;
        
        if (window.pageYOffset > headerBottom - 100) { // Adjust this value to control when navbar appears
            // Show sticky navbar with fade-in animation
            if (!stickyNav.classList.contains('visible')) {
                stickyNav.classList.add('visible');
            }
        } else {
            // Hide sticky navbar
            if (stickyNav.classList.contains('visible')) {
                stickyNav.classList.remove('visible');
            }
        }
    }
      // Toggle profile dropdown in sticky navbar - handled by mainpage.js with hover
    if (stickyProfileToggle && stickyProfileDropdown) {
        const stickyArrow = stickyProfileToggle.querySelector('.arrow');
        
        // Remove click events as they're handled via hover in mainpage.js
        // This prevents conflicts between click and hover behaviors
    }
    
    // Listen for scroll events
    window.addEventListener('scroll', handleStickyNav);
    
    // Initial check
    handleStickyNav();
});
</script>