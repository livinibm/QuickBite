<?php

// This header is essential for telling the browser to expect a JSON response.
header('Content-Type: application/json');

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quickbite";

// Check if the request method is POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Connect to the database.
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for a connection error.
    if ($conn->connect_error) {
        // Return a JSON error message if the connection fails.
        echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    // Sanitize and get form data from the POST request.
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation to ensure required fields are not empty.
    if (empty($name) || empty($email) || empty($message)) {
        // Return a JSON error message if fields are empty.
        echo json_encode(['success' => false, 'message' => "Please fill out all required fields."]);
        $conn->close();
        exit();
    }

    // SQL query to insert data into the contact_us table.
    $sql = "INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)";

    // Prepare the statement.
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared successfully.
    if ($stmt === false) {
        // Return a JSON error if the statement preparation fails.
        echo json_encode(['success' => false, 'message' => "Prepare failed: " . $conn->error]);
        $conn->close();
        exit();
    }
    
    // Bind parameters and execute.
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // Return a JSON success message.
        echo json_encode(['success' => true, 'message' => "Thank you for your message! We will get back to you shortly."]);
    } else {
        // Return a JSON error message on execution failure.
        echo json_encode(['success' => false, 'message' => "Error: " . $stmt->error]);
    }

    // Close the statement and the database connection.
    $stmt->close();
    $conn->close();

} else {
    // If the request method is not POST, return a JSON error.
    echo json_encode(['success' => false, 'message' => "Invalid request method."]);
    exit();
}
?>