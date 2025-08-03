<?php
header('Content-Type: application/json');

// Connect to database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'quickbite'; // CHANGE THIS TO YOUR ACTUAL DATABASE NAME

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$category = isset($_GET['category']) ? $_GET['category'] : 'all';

$items = [];
$sql = "";

if ($category === "all") {
    // No ORDER BY clause, as there is no 'popularity' column
    $sql = "SELECT id, name, price, category, image FROM menu_items";
    $result = $conn->query($sql);
} else {
    // Use a prepared statement to prevent SQL injection
    $sql = "SELECT id, name, price, category, image FROM menu_items WHERE category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
}

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Construct the correct image path
        $row['image'] = "../admin/uploads/" . htmlspecialchars($row['id']) . "/" . htmlspecialchars($row['image']);
        $items[] = $row;
    }
}

echo json_encode($items);
$conn->close();
?>