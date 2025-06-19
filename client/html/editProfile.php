<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;

$user = [
    'email' => '',
    'phone_number' => '',
    'last_name' => '',
    'first_name' => ''
];

if ($is_logged_in && $user_id) {
    require_once '../php/db_connection.php';
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT email, phone_number, last_name, first_name FROM clients WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $user = $row;
    }
    $stmt->close();
    $conn->close();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile']) && $is_logged_in && $user_id) {
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $last = $_POST['last_name'];
    $first = $_POST['first_name'];    require_once '../php/db_connection.php';
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE clients SET email=?, phone_number=?, last_name=?, first_name=? WHERE id=?");
    $stmt->bind_param("ssssi", $email, $phone, $last, $first, $user_id);
    if ($stmt->execute()) {
        header("Location: editProfile.php?success=1");
    } else {
        header("Location: editProfile.php?success=0");
    }
    $stmt->close();
    $conn->close();
    exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/editProfile.css"> 
    <link rel="stylesheet" href="../CSS/header.css">
    <script src="../javascript/header.js" defer></script>
    <title>Vitapets Animal Clinic</title>
</head>
<body>
    <style>
    .profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-left: 60px; /* Adjusted margin for better alignment */
            margin-top: 50px;
            gap: 0px; /* Add spacing between the icon and the arrow */
        }
    </style>
    <section class="navbar">
        <div class="navbar-container">
            <div class="brand">
                <img src="../img/logobgremove 3.png" class="navbarlogo" alt="Logo">
                <h1>VITAPETS ANIMAL CLINIC <br> AND PET SUPPLIES</h1>
            </div>
            <div class="navigation">
                <div class="contact-info">
                    <div class="location">
                    <img src="../img/location pink.png" alt="Location Icon" class="icon">
                    <span>1 Matina Aplaya Road, Talomo, <br> Davao City, 8000 Davao del Sur</span>
                    </div>
                    <div class="phone">
                    <img src="../img/telephone-call green 1.png" alt="Phone Icon" class="icon">
                    <span>+63 932 184 1256</span>
                    </div>
                </div>
                <div class="nav-row">
                    <div class="navButtons">
                        <ul>
                            <li><a href="../html/mainpage.php">Home</a></li>
                            <li><a href="../html/OurServices.php">Services</a></li>
                            <li><a href="../html/aboutUs.php">About Us</a></li>
                            <li><a href="../html/ContactUs.php">Contact Us</a></li>
                        </ul>
                    </div>
                    <?php if($is_logged_in): ?>
                    <!-- Show profile section if logged in -->
                    <div class="profile" id="profileToggle">
                        <img src="../img/right-arrow.png" alt="Arrow" class="arrow" id="arrowIcon">
                        <img
                        src="<?php echo (!empty($_SESSION['avatar_url'])) ? htmlspecialchars($_SESSION['avatar_url'], ENT_QUOTES, 'UTF-8') : '../img/profile-user.png'; ?>"
                        alt="Profile"
                        class="profilepic"
                        referrerpolicy="no-referrer"
                        onerror="this.onerror=null;this.src='../img/profile-user.png';">
                    </div>
                    <!-- Profile dropdown menu -->
                    <div class="profile-dropdown" id="profileDropdown">
                        <ul>
                            <li><a href="../html/editProfile.php">Account</a></li>
                            <li><a href="../html/userappointments.php">Appointments</a></li>
                            <li><a href="../php/logout.php">Logout</a></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="contact">
        <div class="contact_us">
            <h1>Edit Profile</h1>
        </div>
    </section>

    <section class="fillout" alt="">
        <form method="POST">
           <?php if($is_logged_in): ?>
            <div class="fillout-Container">
                <div class="tagilid">
                    <input type="text" placeholder="Email Address" class="name" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    <input type="text" placeholder="Phone Number" class="name" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                </div>
                <div class="tagilid">
                    <input type="text" placeholder="Last Name" class="name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                    <input type="text" placeholder="First Name" class="name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                </div>
            </div>
            <div class="submitbutton">
                <button class="Cancel" type="button" onclick="window.location.href='../html/mainpage.php'">Cancel</button>
                <button class="Submit" type="submit" name="save_profile">Save Info</button>
            </div>
          <?php endif; ?>
        </form>
    </section>
    <section class="footer">
        <div class="footer-container">
            <div class="logo"><img src="../img/logobgremove 4.png" alt=""></div>
            <div class="footer-icons">
                <img src="../img/location pink.png" alt="" class="pink2">
                <img src="../img/telephone-call green 1.png" alt="" class="green">
                <img src="../img/facebook skybleu.png" alt="" class="skybleu">
            </div>
            <div class="footer-details">
                <p class="footer-address">1 Matina Aplaya Road, Talomo, Davao City, 8000 Davao del Sur</p>
                <p class="footer-cp">+63 932 184 1256</p>
                <a href="http://" class="footer-fb">https://www.facebook.com/vitapetsanimalclinic</a>
            </div>
            <div class="footer-quicklinks">
                <h1>Quick Links</h1>
                <ul>
                    <li><a href="../html/mainpage.php">Home</a></li>
                    <li><a href="../html/OurServices.php">Our Services</a></li>
                    <li><a href="../html/aboutUs.php">About Us</a></li>
                    <li><a href="../html/ContactUs.php">Contact Us</a></li>
                    <li><a href="../html/BookSchedule.php">Book Schedule</a></li>
                </ul>
            </div>
            <div class="footer-services">
                <h1>Our Services</h1>
                <ul>
                    <li><a href="#">Vaccinations</a></li>
                    <li><a href="#">Desexing procedures</a></li>
                    <li><a href="#">Grooming</a></li>
                    <li><a href="#">Internal Medicine</a></li>
                    <li><a href="#">Routine Surgery</a></li>
                </ul>
            </div>
            
            <div class="footer-services2">
                <ul>
                    <li><a href="#">Orthopedic Surgery</a></li>
                    <li><a href="#">Cataract Services</a></li>
                </ul>
            </div>
        </div>
        <div class="foooter-line"></div>
        <div class="copyright">
            <div class="copyright-container">
                <p class="isbogens">Copyright © 2025. VitaPets Animal Clinic and Pet Supplies</p>
                <p>Follow us on: </p>
                <img src="../img/facebook 3.png" alt="" class="fb">
                <a href="#top" class="scroll-to-top"> <img src="../img/right-arrow.png" alt="" class="arrow"></a>
            </div>
        </div>
    </section>
    <script>
        const isLoggedIn = <?php echo json_encode($is_logged_in); ?>;
        document.addEventListener('DOMContentLoaded', function() {
        // Profile update popup
        function showProfilePopup(msg, isError = false) {
            const popup = document.getElementById('profilePopup');
            const popupBox = document.getElementById('popupBox');
            const popupMsg = document.getElementById('popupMsg');
            popupMsg.textContent = msg;
            if (isError) {
                popupBox.classList.add('error');
            } else {
                popupBox.classList.remove('error');
            }
            popup.style.display = 'flex';
            setTimeout(() => {
                popup.style.display = 'none';
            }, 2000);
        }

        // Check for ?success=1 or ?success=0 in URL
        const params = new URLSearchParams(window.location.search);
        if (params.get('success') === '1') {
            showProfilePopup('Profile updated successfully!');
            // Remove the param from URL after showing
            setTimeout(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 2100);
        } else if (params.get('success') === '0') {
            showProfilePopup('Failed to update profile.', true);
            setTimeout(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 2100);
        }
    });    </script>
    <script src="../javascript/editProfile.js"></script>
    <div id="profilePopup" class="popup-overlay" style="display:none;">
    <div class="popup-box" id="popupBox">
        <span id="popupMsg"></span>
    </div>
</div>
</body>
</html>