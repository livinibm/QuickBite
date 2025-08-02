<?php
header('Content-Type: application/json');

// Connect to database
$conn = new mysqli("localhost", "root", "", "your_database_name");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}


$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : 'all';

if ($category === "all") {
    $sql = "SELECT name, price, category, image FROM menu_items ORDER BY popularity DESC";
} else {
    $sql = "SELECT name, price, category, image FROM menu_items WHERE category = '$category' ORDER BY popularity DESC";
}

$result = $conn->query($sql);

$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode($items);
$conn->close();
