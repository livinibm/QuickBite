<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

// Connect to DB
$conn = new mysqli("localhost", "root", "", "quickbite");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Get the cart item ID to be removed from the URL
$cart_item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;

if ($cart_item_id > 0) {
    // Prepare a secure statement to delete the item from the database.
    // The WHERE clause is crucial: it checks both the item ID AND the user ID
    // to prevent a user from deleting another user's cart items.
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_item_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redirect back to the cart page to show the updated list
header('Location: cart.php');
exit;
?>