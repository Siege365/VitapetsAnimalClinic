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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/mainpage.css">  
    <script src="../javascript/mainpage.js" defer></script>
    <title>Vitapets Animal Clinic</title>
     
</head>
    <body id="top">
    <?php include_once '../html/includes/sticky_navbar.php'; ?>
    <section class="head"> <img src="../img/companyhome.png" alt="" class="faded">
        <div class="navbar">
            <h1>VITAPETS</h1>
            <ul>
                <li><a href="../html/mainpage.php">Home</a></li>
                <li><a href="../html/OurServices.php">Services</a></li>
                <li><a href="../html/aboutUs.php">About Us</a></li>
                <li><a href="../html/ContactUs.php">Contact Us</a></li>
            </ul>
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
        <div class="quote">
            <div class="quoteimg"><img src="../img/logomainpage.png" alt=""></div>
            <div class="quote-text">
                <h1>LET US LOOK AFTER </h1>
                <h2>YOUR FURBABY</h2>
                <h3>Welcome to Vitapets Animal Clinic</h3>
                <div class="booking">
                    <button class="book" id="bookschedule" onclick="location.href='../html/BookSchedule.php'">Book Schedule</button>
                    <button class="services" onclick="location.href='../html/OurServices.php'">Our Services</button>       
                </div>
            </div>
        </div>
        <div class="Contact">
            <img src="../img/facebook2.png" alt=""><a href="">https://www.facebook.com/vitapetsanimalclinic</a>
            <img src="../img/telephone1.png" alt=""> <a href="" class="pakilid">+63 917 123 4567</a>
        </div>
    </section>
    <section class="Our-Services"> 
        <h1>Our Services</h1>
        <div class="services-container">

            <div class="column1">

                <div class="services-box">
                    <h2>Vaccination</h2>
                    <div class="imagebox">
                        <img src="../img/syringe.png" alt="">
                    </div> 
                </div>
                <div class="services-box">
                    <h2>Desexing Procedures</h2>
                    <div class="imagebox">
                        <img src="../img/scissors 1.png" alt="">
                    </div> 
                </div>
                <div class="services-box">
                    <h2>Grooming</h2>
                    <div class="imagebox">
                        <img src="../img/pet 1.png" alt="">
                    </div> 
                </div>
                <div class="services-box">
                    <h2>Internal Medicine</h2>
                    <div class="imagebox">
                        <img src="../img/medicines 1.png" alt="">
                    </div> 
                </div>
            </div>

        <div class="column2">
            <div class="services-box">
                <h2>Routine Surgery</h2>
                <div class="imagebox">
                    <img src="../img/syringe.png" alt="">
                </div> 
            </div>
            <div class="services-box">
                <h2>Orthopedic Surgery</h2>
                <div class="imagebox">
                    <img src="../img/surgery 1.png" alt="">
                </div> 
            </div>
            <div class="services-box">
                <h2>Cataract Surgery</h2>
                <div class="imagebox">
                    <img src="../img/eye.png" alt="">
                </div> 
            </div>
            <div class="services-box">
                <h2>Dental Care</h2>
                <div class="imagebox">
                    <img src="../img/tooth.png" alt="">
                </div> 
            </div>
        </div>
        </div>
    </section>
    <section class="We-Care"><img src="../img/animals.png" alt="" class="faded2"> 
        <div class="We-Care-Container">

            <div><h1>We care for all animals</h1></div>
           <div class="tapadsila">
            <div class="group1-wecare">
                <div class="column1-wecare">   
                    <img src="../img/check (1) 1.png" alt="">
                    <img src="../img/check (1) 1.png" alt="">
                    <img src="../img/check (1) 1.png" alt="">
                    <img src="../img/check (1) 1.png" alt="">
                </div> 
                <div class="column2-wecare">  
                    <h1 class="first-element">Dogs</h1>
                    <h1 class="elements">Cats</h1>
                    <h1 class="elements">Birds</h1>
                    <h1 class="elements">Rabbit</h1>
                </div>        
            </div>
            <div class="group2-wecare">
                <div class="column1-wecare">   
                    <img src="../img/check (1) 1.png" alt="">
                    <img src="../img/check (1) 1.png" alt="">
                    <img src="../img/check (1) 1.png" alt="">
                    <img src="../img/check (1) 1.png" alt="">
                </div> 
                <div class="column2-wecare">  
                    <h1 class="first-element">Goats</h1>
                    <h1 class="elements">Rats/Mice</h1>
                    <h1 class="elements">Fishes</h1>
                    <h1 class="elements">Other Animals</h1>
                </div>        
            </div>
           </div>
        </div>
    </section>  

    <section class="We-Care2">
        <div class="We-Care2-Quote">
            <p>VitaPets Animal Clinic and Pet Supplies has proudly cared for pets and their families in Davao City for over 5 years. As a family-owned and operated practice, we're dedicated to providing the highest quality care for your furbabies because they’re family too.</p>
        </div>

        <div class="We-Care2-logo">
            <hr>
            <img src="../img/logobgremove 3.png" alt="">
            <hr>
        </div>
        <div class="We-Care2-Dog"><img src="../img/dawg2 1.png" alt=""></div>
        <div class="We-Care2-Cat"><img src="../img/pus.png" alt=""></div>
        <div class="We-Care2-Quote2">
            <p>At VitaPets Animal Clinic, we’re passionate about your pet’s health and happiness. From routine check-ups to advanced care, we proudly serve Davao City and nearby areas with a full range of veterinary services.</p>
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
    <section class="fillout"> <img src="../img/clinic.png" alt="" class="faded4">
        <div class="fillout-Container">
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
            });
        });    });
    </script>
    <script src="../javascript/mainpage.js"></script>
</body>
</html>
