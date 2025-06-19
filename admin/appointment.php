<?php
// Start session for authentication
session_start();

// Include database connection file
require_once '../client/php/db_connection.php';

// Create connection
$conn = getConnection();

// Check connection
if (!$conn) {
    die("Database connection failed");
}

// Process status update if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'], $_POST['booked_id'], $_POST['status'])) {
    $booked_id = filter_var($_POST['booked_id'], FILTER_VALIDATE_INT);
    $new_status = $conn->real_escape_string($_POST['status']);

    if ($booked_id && in_array($new_status, ['Pending', 'Done', 'Cancelled'])) {
        $update_stmt = $conn->prepare("UPDATE requestappointment SET Status = ? WHERE booked_id = ?");
        $update_stmt->bind_param("si", $new_status, $booked_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Redirect to avoid form resubmission
        header("Location: appointment.php");
        exit;
    }
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

            // Always allow delete in admin panel
            $delete_sql = "DELETE FROM requestappointment WHERE booked_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $delete_id);

            if ($delete_stmt->execute()) {
                error_log("Appointment deleted successfully: {$delete_id}");
                $_SESSION['delete_success'] = "Appointment deleted successfully.";
            } else {
                error_log("Error deleting appointment: {$conn->error}");
                $_SESSION['delete_error'] = "Failed to delete appointment: {$conn->error}";
            }

            $delete_stmt->close();
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

// Fetch appointment data from the view
$where = [];
$params = [];
$types = '';

if (isset($_GET['today_only']) && $_GET['today_only'] == '1') {
    $where[] = "SelectedDate = CURDATE()";
}
if (!empty($_GET['status_filter'])) {
    $where[] = "Status = ?";
    $params[] = $_GET['status_filter'];
    $types .= 's';
}

$sql = "SELECT booked_id, client_name, email, phone_number, pet_name, pet_species, PetGender, PetAge, PetColorMarking, PurposeOfAppointment, PreferredTime, SelectedDate, ReasonOfVisit, Status
        FROM appointment_view";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY booked_id DESC";

if ($types) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query($sql);
}

if (!$result) {
    die("Error fetching appointments: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitapets Animal Clinic Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="adminCSS/appointment.css">
    <script src="../client/javascript/userappointment.js"></script>
</head>
<body>
    
    <section class="head">
        <div class="navbar">
            <div class="brand">
                <img src="adminIMG/logobgremove 3.png" class="navbarlogo" alt="Logo">
                <h1>VITAPETS ANIMAL CLINIC <br> AND PET SUPPLIES</h1>
            </div>
            <div class="navigation">
                <div class="contact-info">
                    <div class="location">
                    <img src="adminIMG/location pink.png" alt="Location Icon" class="icon">
                    <span>1 Matina Aplaya Road, Talomo, Davao City, 8000 Davao del Sur</span>
                    </div>
                    <div class="phone">
                    <img src="adminIMG/telephone-call green 1.png" alt="Phone Icon" class="icon">
                    <span>+63 932 184 1256</span>
                    </div>
                </div>
                <div class="navButtons">
                    <ul>
                        <li><a href="admin.html">Home</a></li>
                        <li><a href="#" style="color:#FFBE28;"class="appointmentbtn" >Appointment Request</a></li>
                        <li><a href="../admin/customersquery.php">Customer's Query</a></li>
                        <li><a href="../client/php/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
            </div>
        </div>
    </section>
    <section class="appointment">
        <div class="appointment_requests">
            <h1>Appointment Request</h1>
        </div>
         <!-- Filter Form Start -->
        <form method="GET" class="appointment-filter">
            <label>
                <input type="checkbox" name="today_only" value="1" <?php if(isset($_GET['today_only'])) echo 'checked'; ?>>
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
                        <th>Customer Name</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Pet Name</th>
                        <th>Species</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Color Marking</th>
                        <th>Purpose of Appointment</th>
                        <th>Preferred Time</th>
                        <th>Appointment Date</th>
                        <th>Reason of Visit</th>
                        <th>Status</th>
                        <th>Mark As Done</th>
                    </tr>
                </thead>
                <tbody id="appointmentTableBody">
                    <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['booked_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pet_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pet_species']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PetGender']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PetAge']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PetColorMarking']); ?></td>
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
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="booked_id" value="<?php echo $row['booked_id']; ?>">
                                            <select name="status">
                                                <option value="Pending" <?php echo ($row['Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Done" <?php echo ($row['Status'] == 'Done') ? 'selected' : ''; ?>>Done</option>
                                                <option value="Cancelled" <?php echo ($row['Status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <button class="updatebtn" type="submit" name="update_status">Update</button>
                                        </form>
                                        <form method="POST" class="delete-form" style="display:inline;">
                                                    <input type="hidden" name="delete_id" value="<?php echo $row['booked_id']; ?>">
                                            <button class="deletebtn" type="submit" name="delete_appointment" style="background:#e74c3c;color:#fff;border:none;border-radius:5px;padding:5px 10px;margin-left:5px;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="15">No appointments found.</td>
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
            <textarea id="visitReason" readonly placeholder="Customer's Reason of Visit"></textarea>
        </div>        
    </section>    
    <section class="footer">
        <div class="footer-container">
            <div class="logo"><img src="adminIMG/logobgremove 4.png" alt=""></div>
            <div class="footer-icons">
                <img src="adminIMG/location pink.png" alt="" class="pink">
                <img src="adminIMG/telephone-call green 1.png" alt="" class="green">
                <img src="adminIMG/facebook skybleu.png" alt="" class="skybleu">
            </div>
            <div class="footer-details">
                <p class="footer-address">1 Matina Aplaya Road, Talomo, Davao City, 8000 Davao del Sur</p>
                <p class="footer-cp">+63 932 184 1256</p>
                <a href="http://www.facebook.com/vitapetsanimalclinic" class="footer-fb">https://www.facebook.com/vitapetsanimalclinic</a>
            </div>
            <div class="footer-quicklinks">
                <h1>Quick Links</h1>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="appointment.php">Appointment Request</a></li>
                    <li><a href="customersquery.php">Customer's Query</a></li>
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
                <img src="adminIMG/facebook 3.png" alt="" class="fb">
                <a href="#top" class="scroll-to-top"> <img src="adminIMG/right-arrow.png" alt="" class="arrow"></a>

            </div>
        </div>
    </section>  
    <div id="deletePopup" class="popup-overlay" style="display:none;">
        <div class="popup-box">
            <p>Are you sure you want to delete this appointment?</p>
            <button id="confirmDeleteBtn" class="popup-btn confirm">Yes, Delete</button>
            <button id="cancelDeleteBtn" class="popup-btn cancel">Cancel</button>
        </div>
    </div>
</body>
</html>
