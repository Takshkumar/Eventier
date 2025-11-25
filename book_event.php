<?php
header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password
$dbname = 'eventier_db';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $event_title = isset($_POST['event_title']) ? trim($_POST['event_title']) : '';
    $ticket_name = isset($_POST['ticket_name']) ? trim($_POST['ticket_name']) : '';
    $ticket_price = isset($_POST['ticket_price']) ? floatval($_POST['ticket_price']) : 0.0;
    $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    $card_number = isset($_POST['card_number']) ? str_replace(' ', '', $_POST['card_number']) : '';
    $card_expiry = isset($_POST['card_expiry']) ? trim($_POST['card_expiry']) : '';
    $card_cvv = isset($_POST['card_cvv']) ? trim($_POST['card_cvv']) : '';

    // Basic validation
    if (empty($event_title) || empty($ticket_name) || empty($user_name) || empty($user_email) || empty($card_number) || empty($card_expiry) || empty($card_cvv)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit();
    }

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit();
    }

    // Dummy Payment Validation
    if (strlen($card_number) != 16 || !is_numeric($card_number)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid card number. Please enter a 16-digit card number.']);
        exit();
    }

    if (strlen($card_cvv) < 3 || !is_numeric($card_cvv)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid CVV.']);
        exit();
    }

    // Store only last 4 digits of card for security
    $card_last4 = substr($card_number, -4);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO bookings (event_id, event_title, ticket_name, ticket_price, user_name, user_email, card_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdsss", $event_id, $event_title, $ticket_name, $ticket_price, $user_name, $user_email, $card_last4);

    // Execute
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Booking confirmed! Thank you for your purchase.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>
