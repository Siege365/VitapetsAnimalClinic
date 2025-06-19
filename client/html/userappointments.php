<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$user_email = $_SESSION['email'] ?? '';

// Redirect if not logged in
if (!$is_logged_in) {
    header("Location: ../html/login.php");
    exit();
}

require_once '../php/db_connection.php';
$conn = getConnection();

$result = false;

// Make sure we have the user's email before querying
if ($user_email) {
    $where = ["email = ?"];
    $params = [$user_email];
    $types = 's';

    if (isset($_GET['today_only']) && $_GET['today_only'] == '1') {
        $where[] = "SelectedDate = CURDATE()";
    }
    if (!empty($_GET['status_filter'])) {
        $where[] = "Status = ?";
        $params[] = $_GET['status_filter'];
        $types .= 's';
    }

    $sql = "SELECT booked_id, client_name, email, phone_number, pet_name, pet_species, PetGender, PetAge, PetColorMarking, PurposeOfAppointment, PreferredTime, SelectedDate, ReasonOfVisit, Status
            FROM appointment_view WHERE " . implode(' AND ', $where) . " ORDER BY booked_id DESC";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }
}

// Process form submission for updating status or deleting appointment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update appointment status
    if (isset($_POST['update_status']) && isset($_POST['booked_id']) && isset($_POST['status'])) {
        $booked_id = $_POST['booked_id'];
        $status = $_POST['status'];
        
        // Only allow updates to the user's own appointments
        $check_sql = "SELECT 1 FROM requestappointment WHERE booked_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $booked_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $update_sql = "UPDATE requestappointment SET Status = ? WHERE booked_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $status, $booked_id);
            
            if ($update_stmt->execute()) {
                // Redirect to refresh the page with updated data
                header("Location: " . $_SERVER['PHP_SELF'] . (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ? "?" . $_SERVER['QUERY_STRING'] : ""));
                exit();
            } else {
                echo "Error updating status: {$conn->error}";
            }
            $update_stmt->close();
        } else {
            echo "Unauthorized action";
        }
        $check_stmt->close();
    }
    
    // Process form submission for deleting appointment
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_appointment']) && isset($_POST['delete_id'])) {
        // For debugging
        error_log("Delete request received for ID: " . $_POST['delete_id']);
        
        $delete_id = intval($_POST['delete_id']);
        
        // Validate that the appointment exists and get the email from the appointment_view
        $check_sql = "SELECT email FROM appointment_view WHERE booked_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $delete_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $row = $check_result->fetch_assoc();

            // Verify the appointment belongs to the logged-in user
            if ($row['email'] === $_SESSION['email']) {
                // Execute the delete query
                $delete_sql = "DELETE FROM requestappointment WHERE booked_id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $delete_id);

                if ($delete_stmt->execute()) {
                    error_log("Appointment deleted successfully: {$delete_id}");
                    // Set a success message in session
                    $_SESSION['delete_success'] = "Appointment deleted successfully.";
                } else {
                    error_log("Error deleting appointment: {$conn->error}");
                    $_SESSION['delete_error'] = "Failed to delete appointment: {$conn->error}";
                }

                $delete_stmt->close();
            } else {
                error_log("Unauthorized delete attempt for appointment ID: {$delete_id}");
                $_SESSION['delete_error'] = "You are not authorized to delete this appointment.";
            }
        } else {
            error_log("Appointment not found for ID: {$delete_id}");
            $_SESSION['delete_error'] = "Appointment not found.";
        }

        $check_stmt->close();

        // Redirect to refresh the page and prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF'] .
            (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ? "?" . $_SERVER['QUERY_STRING'] : ""));
        exit();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - Vitapets Animal Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/appointment.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <script src="../javascript/header.js" defer></script>
    <script src="../javascript/userappointment.js" defer></script>

</head>
    <style>
        .profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-left: 770px; /* Adjusted margin for better alignment */
            margin-top: -75px;
            gap: 0px; /* Add spacing between the icon and the arrow */
        }

        .appointment-filter {
                display: flex;
                gap: 16px;
                align-items: center;
                justify-content: flex-start;
                margin: 10px 0;
                margin-left: 1375px;
                margin-top: -25px; /* Move upward */
            }
        .appointment-filter label {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 8px;
            margin: 0;
            white-space: nowrap; /* Prevents label text from wrapping */
        }
        
        .appointment-filter button {
            padding: 5px 15px;
            background-color: #ff719b;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .appointment-filter button:hover {
            background-color: #e65a84;
        }
        .no-appointments {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #555;
        }
    </style>
<body>
    <section class="head">
        <div class="navbar">
            <div class="brand">
                <img src="../img/logobgremove 3.png" class="navbarlogo" alt="Logo">
                <h1>VITAPETS ANIMAL CLINIC <br> AND PET SUPPLIES</h1>
            </div>
            <div class="navigation">
                <div class="contact-info">
                    <div class="location">
                    <img src="../img/location pink.png" alt="Location Icon" class="icon">
                    <span>1 Matina Aplaya Road, Talomo, Davao City, 8000 Davao del Sur</span>
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
        </div>
    </section>
    <section class="appointment">
        <div class="appointment_requests">
            <h1>My Appointments</h1>
        </div>
         <!-- Filter Form Start -->
        <form method="GET" class="appointment-filter">
            <label>
                <input type="checkbox" name="today_only" value="1" <?php if(isset($_GET['today_only'])); ?>>
                Show only today's appointments
            </label>
            <label>
                Status:
                <select name="status_filter">
                    <option value="">All</option>
                    <option value="Pending" <?php if(isset($_GET['status_filter']) && $_GET['status_filter']=='Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Done" <?php if(isset($_GET['status_filter']) && $_GET['status_filter']=='Done') echo 'selected'; ?>>Done</option>
                    <option value="Cancelled" <?php if(isset($_GET['status_filter']) && $_GET['status_filter']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
            </label>
            <button type="submit">Filter</button>
        </form>
        <!-- Filter Form End -->
    </section>
   <section class="appointment_table">
    <div class="table-slider-wrapper">
          <div class="table-slider" id="tableSlider">
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Pet Name</th>
                        <th>Species</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Purpose of Appointment</th>
                        <th>Preferred Time</th>
                        <th>Appointment Date</th>
                        <th>Reason of Visit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="appointmentTableBody">
                    <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['booked_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pet_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pet_species']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PetGender']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PetAge']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PurposeOfAppointment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PreferredTime']); ?></td>
                                    <td><?php echo htmlspecialchars($row['SelectedDate']); ?></td>
                                    <td>
                                        <button type="button" class="view-reason-btn"
                                            data-reason="<?php echo htmlspecialchars($row['ReasonOfVisit'], ENT_QUOTES); ?>">
                                            View
                                        </button>
                                    </td>
                                    <td>
                                        <span class="status-indicator <?php echo strtolower($row['Status']); ?>">
                                            <?php echo ucfirst($row['Status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['Status'] == 'Pending'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="booked_id" value="<?php echo $row['booked_id']; ?>">
                                            <input type="hidden" name="status" value="Cancelled">
                                            <button class="updatebtn" type="submit" name="update_status">Cancel</button>
                                        </form>
                                        <?php endif; ?>
                                        
                                        <form method="POST" class="delete-form" style="display:inline;">
                                                    <input type="hidden" name="delete_id" value="<?php echo $row['booked_id']; ?>">
                                            <button class="deletebtn" type="submit" name="delete_appointment" style="background:#e74c3c;color:#fff;border:none;border-radius:5px;padding:5px 10px;margin-left:5px;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="no-appointments">No appointments found. <a href="../html/BookSchedule.php">Book an appointment now</a>.</td>
                            </tr>
                        <?php endif; ?>
                </tbody>
            </table>
      </div>
    </div>
    <!-- Add this slider under the table -->
    <div class="table-slider-controls" style="text-align:center; margin-top:10px;">
        <button class="slider-btn left" id="slideLeftBottom" aria-label="Scroll left">&#8592;</button>
        <button class="slider-btn right" id="slideRightBottom" aria-label="Scroll right">&#8594;</button>
    </div>
</section>
    </section>
    <section class="reasonOfVisit">
        <div class="reason-box">
            <textarea id="visitReason" readonly placeholder="Appointment details will appear here when you click 'View'"></textarea>
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
                <a href="http://www.facebook.com/vitapetsanimalclinic" class="footer-fb">https://www.facebook.com/vitapetsanimalclinic</a>
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
    <div id="deletePopup" class="popup-overlay" style="display:none;">
        <div class="popup-box">
            <p>Are you sure you want to delete this appointment?</p>
            <button id="confirmDeleteBtn" class="popup-btn confirm">Yes, Delete</button>
            <button id="cancelDeleteBtn" class="popup-btn cancel">Cancel</button>
        </div>    </div>
    <script src="../javascript/userappointment.js"></script>
</body>
</html>