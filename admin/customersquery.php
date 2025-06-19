<?php
// Start session for authentication
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection file
require_once '../client/php/db_connection.php';

// Create connection
$conn = getConnection();

// Check connection
if (!$conn) {
    die("Database connection failed");
}

// Process status update if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'], $_POST['msg_id'], $_POST['status'])) {
    $msg_id = filter_var($_POST['msg_id'], FILTER_VALIDATE_INT);
    $new_status = $conn->real_escape_string($_POST['status']);

    if ($msg_id && in_array($new_status, ['unread', 'read', 'replied'])) {
        $update_stmt = $conn->prepare("UPDATE quick_message SET status = ? WHERE msg_id = ?");
        $update_stmt->bind_param("si", $new_status, $msg_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Redirect to avoid form resubmission
        header("Location: customersquery.php");
        exit;
    }
}


 // Process deletion if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'], $_POST['delete_id'])) {
    $delete_id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);

    if ($delete_id) {
        $delete_stmt = $conn->prepare("DELETE FROM quick_message WHERE msg_id = ?");
        $delete_stmt->bind_param("i", $delete_id);

        if ($delete_stmt->execute()) {
            $_SESSION['delete_success'] = "Message deleted successfully.";
        } else {
            $_SESSION['delete_error'] = "Failed to delete message: " . $conn->error;
        }

        $delete_stmt->close();

        // Redirect to avoid form resubmission
        header("Location: customersquery.php");
        exit;
    } else {
        $_SESSION['delete_error'] = "Invalid message ID.";
        header("Location: customersquery.php");
        exit;
    }
}
// Build query for messages
$where = [];
$params = [];
$types = '';

if (!empty($_GET['status_filter'])) {
    $where[] = "status = ?";
    $params[] = $_GET['status_filter'];
    $types .= 's';
}
if (!empty($_GET['pet_filter'])) {
    $where[] = "pet_name LIKE ?";
    $params[] = '%' . $_GET['pet_filter'] . '%';
    $types .= 's';
}
if (!empty($_GET['client_filter'])) {
    $where[] = "client_name LIKE ?";
    $params[] = '%' . $_GET['client_filter'] . '%';
    $types .= 's';
}

// Use the clients and quick_message tables directly
$sql = "SELECT 
            m.msg_id,
            c.id AS client_id,
            CONCAT(c.first_name, ' ', c.last_name) AS client_name,
            c.email,
            c.phone_number,
            m.pet_name,
            m.message,
            m.status,
            m.created_at
        FROM quick_message m
        JOIN clients c ON m.client_id = c.id";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY m.msg_id DESC";

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
    die("Error fetching messages: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitapets Animal Clinic Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="adminCSS/customersquery.css">
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
                        <span style="color:#F182E8;">1 Matina Aplaya Road, Talomo, Davao City, 8000 Davao del Sur</span>
                    </div>
                    <div class="phone">
                        <img src="adminIMG/telephone-call green 1.png" alt="Phone Icon" class="icon">
                        <span style="color:#B8DB0C">+63 932 184 1256</span>
                    </div>
                </div>
                <div class="navButtons">
                    <ul>
                        <li><a href="admin.html">Home</a></li>
                        <li><a href="appointment.php">Appointment Request</a></li>
                        <li><a href="#" style="color:#FFBE28;" class="hovbtn">Customer's Query</a></li>
                        <li><a href="../client/php/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    
    <section class="customer">
        <div class="customer_query">
            <h1>Customer's Query</h1>
        </div>
        
        <!-- Filter Form -->
        <form method="GET" class="msg-filter">
            <label>
                Status:
                <select name="status_filter">
                    <option value="">All</option>
                    <option value="unread" <?php if(isset($_GET['status_filter']) && $_GET['status_filter']=='unread') echo 'selected'; ?>>Unread</option>
                    <option value="read" <?php if(isset($_GET['status_filter']) && $_GET['status_filter']=='read') echo 'selected'; ?>>Read</option>
                    <option value="replied" <?php if(isset($_GET['status_filter']) && $_GET['status_filter']=='replied') echo 'selected'; ?>>Replied</option>
                </select>
            </label>          
            <label>
                Client Name:
                <input type="text" name="client_filter" value="<?php echo isset($_GET['client_filter']) ? htmlspecialchars($_GET['client_filter']) : ''; ?>">
            </label>
            <button type="submit">Filter</button>
        </form>
    </section>
    
    <section class="customer_table">
        <table>
            <thead>
                <tr>
                    <th>Message ID</th>
                    <th>Client Name</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Pet Name</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['msg_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['pet_name']); ?></td>
                            <td>
                                <button type="button" class="view-reason-btn"
                                            data-reason="<?php echo htmlspecialchars($row['message'], ENT_QUOTES); ?>">
                                            View
                                </button>
                            </td>
                            <td>
                                <span class="status-indicator <?php echo strtolower($row['status']); ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <!-- Add actions like reply, mark as read, or delete here -->
                                <form method="POST" style="display:inline; margin-right:5px;">
                                    <input type="hidden" name="msg_id" value="<?php echo $row['msg_id']; ?>">
                                    <select name="status">
                                        <option value="unread" <?php echo ($row['status'] == 'unread') ? 'selected' : ''; ?>>Unread</option>
                                        <option value="read" <?php echo ($row['status'] == 'read') ? 'selected' : ''; ?>>Read</option>
                                        <option value="replied" <?php echo ($row['status'] == 'replied') ? 'selected' : ''; ?>>Replied</option>
                                    </select>
                                    <button class="updatebtn" type="submit" name="update_status">Update</button>
                                </form>
                                <form method="POST" class="delete-form" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['msg_id']; ?>">
                                    <button class = "deletebtn"type="button" onclick="confirmDelete(this.form, <?php echo $row['msg_id']; ?>)" style="background-color:red;color:white;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>  
                    <tr>
                        <td colspan="9">No messages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
    
    <section class="reasonOfVisit">
        <div class="reason-box">
            <textarea id="msg" readonly placeholder="Customer's Message"></textarea>
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
    
    <!-- Delete confirmation popup -->
    <div id="deleteMsgPopup" class="popup-overlay" style="display:none;">
        <div class="popup-box">
            <p>Are you sure you want to delete this message?</p>
            <button id="confirmMsgDeleteBtn" class="confirm-btn">Yes, Delete</button>
            <button id="cancelMsgDeleteBtn" class="cancel-btn">Cancel</button>
        </div>
    </div>
    
    <script>
         function confirmDelete(form, id) {
            const popup = document.getElementById('deleteMsgPopup');
            popup.style.display = 'flex';
            
            document.getElementById('confirmMsgDeleteBtn').onclick = function() {
                // Add the delete_message field dynamically
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_message';
                input.value = '1';
                form.appendChild(input);
                
                form.submit();
                popup.style.display = 'none';
            };
            
            document.getElementById('cancelMsgDeleteBtn').onclick = function() {
                popup.style.display = 'none';
            };
        }
            // View message functionality
            document.querySelectorAll('.view-reason-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const reason = this.getAttribute('data-reason');
                    document.getElementById('msg').value = reason;
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
            // View message functionality
            document.querySelectorAll('.view-reason-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const reason = this.getAttribute('data-reason');
                    document.getElementById('msg').value = reason;
                });
            });
            // Reason of visit popup functionality
            const reasonTextarea = document.getElementById('msg');
            document.querySelectorAll('.view-reason-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    reasonTextarea.value = btn.getAttribute('data-reason');
                    reasonTextarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
        });
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>