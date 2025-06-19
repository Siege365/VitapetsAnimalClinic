<?php
    session_start();
    $is_logged_in = isset($_SESSION['user_id']);
    $user_id = $_SESSION['user_id'] ?? null;

    $user = [
        'email' => '',
        'phone_number' => '',
        'last_name' => '',
        'first_name' => ''
    ];    if ($is_logged_in && $user_id) {
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
    <link rel="stylesheet" href="../CSS/BookSchedule.css"> 
    <link rel="stylesheet" href="../CSS/header.css">
    <script src="../javascript/header.js" defer></script>
    <script src="../javascript/bookschedule.js" defer></script>
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

    <section class="booking">
        <div class="book_schedule">
            <h1>Book Schedule</h1>
        </div>
    </section>
    <section class="fillout">
       <?php if (isset($_GET['success'])): ?>
        <div id="popup-success" class="popup-container">
            <div class="popup-content success">
                <div class="checkmark">&#10004;</div>
                <p>Booking submitted successfully!</p>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'login'): ?>
        <div id="popup-error" class="popup-container">
            <div class="popup-content error">
                <div class="crossmark">&#10006;</div>
                <p>You need to log in first.</p>
            </div>
        </div>
        <?php endif; ?>
      <form action="../php/book_schedule_handler.php" method="POST">
        <div class="fillout-Container">

            <div class="fillout-storya">
                <h2>Fill out the form</h2>
                <p>We will contact you to confirm the time of your appointment.</p>
            </div>
            <div class="fillout_contents">
                <div class="FirstQ">
                    <p>How many Pets? (If one only, leave blank)</p>
                    <input type="number" placeholder="" class="number" name="num_pets" min="1">
                    <button class="addinput" type="button">Add Input</button>
                </div>
                <div class="SecondQ">
                    <div class="lastname"> 
                        <p class="lahiernss">Last Name</p>
                        <input type="text" placeholder="Last Name" class="name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                    </div>
                    <div class="firstname"> 
                        <p class="lahiernss">First Name</p>
                        <input type="text" placeholder="First Name" class="name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                    </div>
                </div>
                <div class="ThirdQ">
                    <div class="petsname"> 
                        <p class="lahiernss">Pet's Name</p>
                        <input type="text" placeholder=" " class="name" name="pet_name" required>
                    </div>
                    <div class="species"> 
                        <p class="lahiernss">Pet's Species</p>
                        <select id="species" name="species" required>
                        <option value="dog">Dog</option>
                        <option value="cat">Cat</option>
                        <option value="bird">Bird</option>
                        <option value="rabbit">Rabbit</option>
                        <option value="goat">Goats</option>
                        <option value="rat/mouse">Rats/Mice</option>
                        <option value="fish">Fishes</option>
                        <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="gender">
                        <p class="lahiernss">Pet's Gender</p>
                        <select class="name" name="pet_gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="FourthQ">
                    <div class="age"> 
                        <p class="lahiernss">Pet's Age</p>
                        <input type="text" placeholder="" class="name" name="pet_age" required>
                    </div>
                    <div class="bday"> 
                        <p class="lahiernss">Pet's Birth Date</p>
                        <input type="date" placeholder="" class="name" name="pet_birthdate">
                    </div>
                    <div class="marking"> 
                        <p class="lahiernss">Pet's Color Marking</p>
                        <input type="text" placeholder="" class="name" name="pet_color">
                    </div>
                </div>
                <div id="petsFields"></div>
                <div class="FifthQ">
                    <div class="porpose"> 
                        <p class="lahiernss">Purpose of Appointment</p>
                    <select id="appointments" name="appointment" required>
                        <option value="vaccinations">Vaccinations</option>
                        <option value="desexing procedures">Desexing procedures</option>
                        <option value="grooming">Grooming</option>
                        <option value="internal medicine">Internal Medicine</option>
                        <option value="routine surgery">Routine Surgery</option>
                        <option value="orthopedic surgery">Orthopedic Surgery</option>
                        <option value="cataract surgery">Cataract Surgery</option>
                        <option value="dental care">Dental Care</option>
                        <option value="other">Other</option>
                    </select>
                    </div>
                    <div class="preferred"> 
                        <p class="lahiernss">Preferred Time</p>
                    <select class="name" name="preferred_time" required>
                        <option value="7:00am - 8:00am">7:00am - 8:00am</option>
                        <option value="8:00am - 9:00am">8:00am - 9:00am</option>
                        <option value="9:00am - 10:00am">9:00am - 10:00am</option>
                        <option value="10:00am - 11:00am">10:00am - 11:00am</option>
                        <option value="11:00am - 12:00pm">11:00am - 12:00pm</option>
                        <option value="12:00pm - 1:00pm">12:00pm - 1:00pm</option>
                        <option value="1:00pm - 2:00pm">1:00pm - 2:00pm</option>
                        <option value="2:00pm - 3:00pm">2:00pm - 3:00pm</option>
                        <option value="3:00pm - 4:00pm">3:00pm - 4:00pm</option>
                        <option value="4:00pm - 5:00pm">4:00pm - 5:00pm</option>
                        <option value="5:00pm - 6:00pm">5:00pm - 6:00pm</option>
                        <option value="6:00pm - 7:00pm">6:00pm - 7:00pm</option>            
                    </select>
                    </div>
                    <div class="date"> 
                        <p class="lahiernss">Select Date</p>
                        <input type="date" class="date" placeholder="Date" name="appointment_date" required>
                    </div>
                </div>
                <div class="SixthQ"> 
                    <div class="address"> 
                        <p class="lahierns2">Email Address</p>
                        <input type="text" placeholder="Email Address" class="name" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    <div class="phone">
                        <p>Phone Number</p>
                        <input type="text" placeholder="Phone Number" class="name" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                    </div>
                </div>
                <div class="details"> 
                    <p>What is your reason for your visit? (optional)</p>
                    <textarea class="textarea" id="" name="reason"></textarea>
                </div>
            </div>
            <div class="submiters"><button class="submit" type="submit">Submit Info</button></div>
         </div>
        </form>
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
                    <a href="">https://www.facebook.com/vitapetsanimalclinic</a>
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
    </section>    <script>
        const isLoggedIn = <?php echo json_encode($is_logged_in); ?>;
    </script>
    <script src="../javascript/bookschedule.js"></script>
</body>
</html>