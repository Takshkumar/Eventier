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
    // Get email from POST request
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        // Execute
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Thank you for subscribing!']);
        } else {
            // Check for duplicate entry
            if ($conn->errno == 1062) {
                echo json_encode(['status' => 'error', 'message' => 'This email is already subscribed.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
            }
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>
