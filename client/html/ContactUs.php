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
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/ContactUs.css"> 
    <link rel="stylesheet" href="../CSS/header.css">
    <script src="../javascript/header.js" defer></script>
    <title>Vitapets Animal Clinic</title>
</head>
<body>
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
                            <li><a href="../html/OurServices.php" class="services-link">Services</a></li>
                            <li><a href="../html/aboutUs.php" class="about-link">About Us</a></li>
                            <li><a href="../html/ContactUs.php" class="contact-link" style="color:#FFBE28;">Contact Us</a></li>
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

    <section class="contact">
        <div class="contact_us">
            <h1>Contact Us</h1>
            <p>Use the form below to send us a quick messsage,
                <br> and we'll get back to you as soon as possible. 
                <br> Prefer to talk? Feel free to call us with your questions.
                <br> To book an appointment, simply click the <span class="highlight">"Book Schedule"</span> button.
                <br> We look forward to seeing you at VitaPets Animal Clinic in Davao City</p>
            </p>
        </div>
    </section>

    <section class="ContactUS"><img src="../img/logobgremove 2.png" alt="" class="faded3">
        <div class="ContactUS-Container2">
            <div class="ContactDetails">
                <div class="location">
                    <img src="../img/location pink.png" class="icon" alt="">
                    <span>1 Matina Aplaya Road, Talomo, <br> Davao City, 8000 Davao del Sur</span>
                </div>
                <div class="telephone">
                    <img src="../img/telephone-call green 1.png" class="icon" alt="">
                    <span>+63 932 184 1256</span>
                </div>
                <div class="facebook">
                    <img src="../img/facebook skybleu.png" class="icon" alt="">
                  <span><a href="https://www.facebook.com/vitapetsanimalclinic">https://www.facebook.com/vitapetsanimalclinic</a></span>
                </div>
                <div class="clock">
                    <img src="../img/clock pink.png" class="icon" alt="">
                    <span class="next-clock" style="color: #F182E8;
                    font-family: 'Poppins', sans-serif;
                    font-size: 20px;
                    font-style: normal;
                    font-weight: 600;
                    line-height: normal;">Monday - Saturday</span>
                </div>
                <div class="clock-details">
                    <p class="under" style="color: #82F0EB;
                    font-family: 'Poppins', sans-serif;
                    font-size: 20px;
                    font-style: normal;
                    font-weight: 600;
                    line-height: normal;">&nbsp;&nbsp;&nbsp;&nbsp;Clinic Hours: 7:00am - 7:00pm</p>
                    <p class="next-clock" style="color: #F182E8;
                    font-family: 'Poppins', sans-serif;
                    font-size: 20px;
                    font-style: normal;
                    font-weight: 600;
                    line-height: normal;">Sunday</p>
                    <p class="under" style="color: #82F0EB;
                    font-family: 'Poppins', sans-serif;
                    font-size: 20px;
                    font-style: normal;
                    font-weight: 600;
                    line-height: normal;">&nbsp;&nbsp;&nbsp;&nbsp;Clinic Hours: 8:00am - 5:00pm</p>
                </div>
            </div>
        </div>
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.598048219275!2d125.56486957584926!3d7.056425516739241!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32f972ace8c4b849%3A0x6bb201b4589157fa!2sVitapets%20Animal%20Clinic%20And%20Pet%20Supplies!5e0!3m2!1sen!2sph!4v1745842073860!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>
    <section class="fillout"> <img src="../img/clinic.png" alt="" class="faded4">
        <div class="fillout-Container">
            <div class="tagilid">
                 <form id="contactForm" method="POST" action="../php/quickmessage_handler.php">
                <div class="tagilid">
                    <input type="text" placeholder="Last Name" class="name" name="last_name" required value="<?php echo htmlspecialchars($user['last_name']); ?>">
                    <input type="text" placeholder="First Name" class="name" name="first_name" required value="<?php echo htmlspecialchars($user['first_name']); ?>">
                </div>
                <div class="tagilid">
                    <input type="text" placeholder="Pet's Name" id="petname" name="pet_name" class="name" required>
                    <input type="text" placeholder="Phone Number" class="name" name="phone_number" required value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                </div>
                <div>
                    <input type="text" placeholder="Email Address" class="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                <div class="last">
                    <textarea name="message" id="message" class="Message" placeholder="Message" required></textarea>
                    <button type="submit" class="Submit" id="submitinfo">Submit Info</button>
                </div>
             </form>
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
    </section>
    <script>
        const isLoggedIn = <?php echo json_encode($is_logged_in); ?>;
        document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        
        // Create popup element
        const popupEl = document.createElement('div');
        popupEl.className = 'popup';
        popupEl.innerHTML = `
            <div class="popup-content">
                <p id="popup-message"></p>
                <button class="close">Close</button>
            </div>
        `;
        document.body.appendChild(popupEl);
        
        // Function to show popup
        function showPopup(message, type) {
            const popupMessage = document.getElementById('popup-message');
            popupMessage.textContent = message;
            popupMessage.className = type; // 'success' or 'error'
            popupEl.classList.add('show');
            
            // Close popup when button is clicked
            popupEl.querySelector('.close').onclick = function() {
                popupEl.classList.remove('show');
            };
            
            // Auto close after 5 seconds
            setTimeout(() => {
                popupEl.classList.remove('show');
            }, 5000);
        }
        
        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!isLoggedIn) {
                showPopup("You must be logged in to submit a message. Please log in or sign up first.", 'error');
                return;
            }
            
            // Submit via AJAX
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showPopup(data.message, 'success');
                    form.reset();
                } else {
                    showPopup(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showPopup("There was an error sending your message. Please try again later.", 'error');
            });        });
    });
    </script>
    <script src="../javascript/ContactUs.js"></script>
</body>
</html>