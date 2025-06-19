 <?php
    // Start session at the beginning
    session_start();
    // Check if user is logged in
    $is_logged_in = isset($_SESSION['user_id']);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/OurServices.css"> 
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
            margin-left: 300px; /* Adjusted margin for better alignment */
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
                            <li><a href="../html/mainpage.php" class="home-link">Home</a></li>
                            <li><a href="../html/OurServices.php" class="services-link" style="color:#FFBE28;">Services</a></li>
                            <li><a href="../html/aboutUs.php" class="about-link">About Us</a></li>
                            <li><a href="../html/ContactUs.php" class="contact-link">Contact Us</a></li>
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
                    <?php else: ?>
                    <!-- Show signup/login buttons if not logged in -->
                    <div class="buttons">
                        <a href="../html/register.php">
                        <button class="signup">Sign Up</button>
                        </a>
                        <a href="../html/login.php">
                        <button class="login">Log In</button>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="services">
        <div class="our_services">
            <h1>Our Services</h1>
        </div>
    </section>

    <section class="Services">
        <div class="Services-Container">
            <div class="Services-Container1">
                <div class="tagilid">
                    <img src="../img/syringep.png" alt="">
                    <h1>Vaccinations</h1>
                </div>
                <div class="tagilid">
                    <img src="../img/toothp.png" alt="">
                    <h1>Dental Care</h1>
                </div>
                <div class="tagilid">
                    <img src="../img/eyep.png" alt="">
                    <h1>Cataract Surgery</h1>
                </div>
                <div class="tagilid">
                    <img src="../img/bonep.png" alt="">
                    <h1>Orthopedic Surgery</h1>
                </div>
                <div class="tagilid">
                    <img src="../img/surgeryp.png" alt="">
                    <h1>Routine Surgery</h1>
                </div>
                <div class="tagilid">
                    <img src="../img/scissorsp.png" alt="">
                    <h1>Desexing Procedures</h1>
                </div> <div class="tagilid">
                    <img src="../img/petp.png" alt="">
                    <h1>Grooming</h1>
                </div> <div class="tagilid">
                    <img src="../img/medicinesp.png" alt="">
                    <h1>Internal Medicine</h1>
                </div>
            </div>
            <div class="Services-Container2">
                <div class="right"><img src="../img/Right.png" alt="">
                    <button class="book" id="bookschedule" onclick="location.href='../html/BookSchedule.php'">Book Schedule</button>
                </div>
            </div>
        </div>
    </section>

    <section class="ContactUS"><img src="../img/logobgremove 2.png" alt="" class="faded3">
        <div class="ContactUS-Container">
            <h1>Contact Us</h1>
            <h2>Need an appointment or want to know more?</h2>
            <p>Use the form below to send us a quick message, and we’ll get back to you as soon as possible. Prefer to talk? Feel free to call us with your questions. To book an appointment, simply click the <a href="#top">"Book Schedule"</a> button . We look forward to seeing you at VitaPets Animal Clinic in Davao City.</p>
        </div>
        <div class="ContactUS-Container2">
            <div class="ContactDetails">
                <div class="ContactDetails-column1">
                    <img src="../img/maps-and-flags.png" alt="">
                    <img src="../img/telephone.png" alt="">
                    <img src="../img/facebook 1.png" alt="">
                    <img src="../img/clock.png" alt="">
                </div>
                <div class="ContactDetails-column2">
                    <p class="abovers">1 Matina Aplaya Road, Talomo, Davao City, 8000 Davao del Sur</p>
                    <p class="next">+63 932 184 1256</p>
                    <a href="https://www.facebook.com/vitapetsanimalclinic">https://www.facebook.com/vitapetsanimalclinic</a>
                    <p class="next-clock">Monday - Saturday</p>
                    <p class="under">&nbsp;&nbsp;&nbsp;&nbsp;Clinic Hours: 7:00am - 7:00pm</p>
                    <p class="next-clock">Sunday</p>
                    <p class="under">&nbsp;&nbsp;&nbsp;&nbsp;Clinic Hours: 8:00am - 5:00pm</p>
                </div>
            </div>
        </div>
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.598048219275!2d125.56486957584926!3d7.056425516739241!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32f972ace8c4b849%3A0x6bb201b4589157fa!2sVitapets%20Animal%20Clinic%20And%20Pet%20Supplies!5e0!3m2!1sen!2sph!4v1745842073860!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>
   <section class="footer">
        <div class="footer-container">
            <div class="logo"><img src="../img/logobgremove 4.png" alt=""></div>
            <div class="footer-icons">
                <img src="../img/location pink.png" alt="" class="pink">
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
                    <li><a href="../html/OurServices.php">Vaccinations</a></li>
                    <li><a href="../html/OurServices.php">Desexing procedures</a></li>
                    <li><a href="../html/OurServices.php">Grooming</a></li>
                    <li><a href="../html/OurServices.php">Internal Medicine</a></li>
                    <li><a href="../html/OurServices.php">Routine Surgery</a></li>
                </ul>
            </div>
            
            <div class="footer-services2">
                <ul>
                    <li><a href="../html/OurServices.php">Orthopedic Surgery</a></li>
                    <li><a href="../html/OurServices.php">Cataract Services</a></li>
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
    </section>    <script>
        const isLoggedIn = <?php echo json_encode($is_logged_in); ?>;
    </script>
    <script src="../javascript/OurServices.js"></script>
</body>
</html>