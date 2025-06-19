<?php
// Include database connection
require_once 'db_connection.php';

$conn = getConnection();
if (!$conn) {
    die("Database connection failed");
}

session_start(); 
if (!isset($_SESSION['user_id'])) {
    // Redirect or error for not logged in
    header("Location: ../html/BookSchedule.php?error=login");
    exit;
}
$client_id = $_SESSION['user_id'];

// Get main pet (Pet 1) data
$pets = [];
$pets[] = [
    'name' => $_POST['pet_name'] ?? '',
    'species' => $_POST['species'] ?? '',
    'gender' => $_POST['pet_gender'] ?? '',
    'birthdate' => $_POST['pet_birthdate'] ?? '',
    'age' => $_POST['pet_age'] ?? '',
    'color' => $_POST['pet_color'] ?? ''
];

// Get additional pets
$i = 2;
while (isset($_POST["pet_name_$i"])) {
    $pets[] = [
        'name' => $_POST["pet_name_$i"] ?? '',
        'species' => $_POST["species_$i"] ?? '',
        'gender' => $_POST["pet_gender_$i"] ?? '',
        'birthdate' => $_POST["pet_birthdate_$i"] ?? '',
        'age' => $_POST["pet_age_$i"] ?? '',
        'color' => $_POST["pet_color_$i"] ?? ''
    ];
    $i++;
}

// Get appointment info
$purpose = $_POST['appointment'] ?? '';
$preferred_time = $_POST['preferred_time'] ?? '';
$selected_date = $_POST['appointment_date'] ?? '';
$reason = $_POST['reason'] ?? '';
$status = 'Pending';

// Insert each pet as a separate booking
$stmt = $conn->prepare("INSERT INTO requestappointment (client_id, `Pet's_Name`, `Pet's_Species`, PetGender, PetBirthDate, PetAge, PetColorMarking, PurposeOfAppointment, PreferredTime, SelectedDate, ReasonOfVisit, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($pets as $pet) {
    $stmt->bind_param(
        "isssssssssss",
        $client_id,
        $pet['name'],
        $pet['species'],
        $pet['gender'],
        $pet['birthdate'],
        $pet['age'],
        $pet['color'],
        $purpose,
        $preferred_time,
        $selected_date,
        $reason,
        $status
    );
    $stmt->execute();
}
$stmt->close();
$conn->close();

header("Location: ../html/BookSchedule.php?success=1");
exit;
?>