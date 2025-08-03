<?php
// Start the session to access session variables
session_start();

header('Content-Type: application/json');

// Connect to database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'quickbite';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]));
}

// Check if the user is logged in by looking for a user_id in the session
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "error" => "User not logged in."]));
}

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Get the POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['item_id']) || !isset($data['quantity'])) {
    die(json_encode(["success" => false, "error" => "Invalid data received."]));
}

$itemId = (int)$data['item_id'];
$quantity = (int)$data['quantity'];

// 1. Fetch item details from menu_items table
$sql_fetch = "SELECT name, price, image FROM menu_items WHERE id = ?";
$stmt_fetch = $conn->prepare($sql_fetch);
$stmt_fetch->bind_param("i", $itemId);
$stmt_fetch->execute();
$result = $stmt_fetch->get_result();

if ($result->num_rows === 0) {
    die(json_encode(["success" => false, "error" => "Item not found in menu."]));
}

$menuItem = $result->fetch_assoc();
$name = $menuItem['name'];
$price = $menuItem['price'];
$image = $menuItem['image'];

$stmt_fetch->close();

// 2. Insert all details into cart_items table
$imagePath = htmlspecialchars($image);

$sql_insert = "INSERT INTO cart_items (user_id, item_id, quantity, price, name, image) VALUES (?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("iiidss", $userId, $itemId, $quantity, $price, $name, $imagePath);

if ($stmt_insert->execute()) {
    echo json_encode(["success" => true, "message" => "Item added to cart."]);
} else {
    echo json_encode(["success" => false, "error" => "Execute failed: " . $stmt_insert->error]);
}

$stmt_insert->close();
$conn->close();
?>