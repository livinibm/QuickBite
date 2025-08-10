<?php
session_start();

// The user ID should be retrieved from the session in a live environment.
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect or handle unauthenticated access
    header('Location: ../reglogin/auth.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "quickbite");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cartQuery = "SELECT * FROM cart_items WHERE user_id = ?";
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = "";
$subtotal = 0;
$cartHtml = "";

while ($row = $result->fetch_assoc()) {
    $itemName = htmlspecialchars($row["name"]);
    
    // Check if the 'size' key exists before using it.
    // This prevents the "Undefined array key" warning.
    $size = htmlspecialchars($row["size"] ?? 'N/A');
    
    $quantity = intval($row["quantity"]);
    $price = floatval($row["price"]);
    $totalItemPrice = $quantity * $price;
    $subtotal += $totalItemPrice;

    $image_path = "../admin/uploads/" . htmlspecialchars($row['item_id']) . "/" . htmlspecialchars($row['image']);

    $items .= "{$quantity} x {$itemName}, ";

    $cartHtml .= "
        <div class='item'>
            <img src='{$image_path}'>
            <div class='details'>
                <p><strong>{$itemName}</strong></p>
                <p>Price: Rs {$price}</p>
                <p>Quantity: {$quantity}</p>
            </div>
        </div>
    ";
}

$deliveryFee = 200;
$grandTotal = $subtotal + $deliveryFee;
$items = rtrim($items, ", ");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email   = filter_var(trim($_POST["email"] ?? ''), FILTER_VALIDATE_EMAIL);
    $address = htmlspecialchars(trim($_POST["address"] ?? ''));
    $contact = preg_replace("/[^0-9]/", "", $_POST["contact"] ?? '');
    $order_items = $_POST["order_items"] ?? '';
    $total = floatval($_POST["total"] ?? 0);

    if (!$email || strlen($contact) != 10) {
        echo "<script>alert('Invalid input.'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, address, contact_number, order_items, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $name, $address, $contact, $order_items, $total);

    if ($stmt->execute()) {
        echo "<script>alert('Order placed successfully!');</script>";
    } else {
        echo "<script>alert('Order failed.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Place Your Order - QuickBite</title>
    <link rel="stylesheet" href="../Homepage/index.css" />
    <link rel="stylesheet" href="order.css" />
</head>
<body>

<?php require_once 'header/header.php'; ?>

    <div class="container">
        <h1 class="main-heading">Place Your Order</h1>

        <form method="POST" action="order.php" onsubmit="return validateForm()">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />

            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3" required></textarea>

            <label for="contact">Contact Number</label>
            <input type="tel" id="contact" name="contact" required pattern="[0-9]{10}" placeholder="0712345678" />

            <div class="summary-box">
                <h2>Your Cart</h2>
                <?= $cartHtml ?>

                <label for="order_items">Order Summary</label>
                <textarea id="order_items" name="order_items" rows="4" readonly><?= $items ?></textarea>

                <div class="bill">
                    <p>Subtotal: Rs <?= $subtotal ?></p>
                    <p>Delivery Fee: Rs <?= $deliveryFee ?></p>
                    <p class="total">Total: Rs <?= $grandTotal ?></p>
                </div>

                <input type="hidden" name="total" value="<?= $grandTotal ?>">
            </div>

            <button type="submit">Submit Order</button>
        </form>
    </div>

    <script>
        function validateForm() {
            const email = document.getElementById("email").value.trim();
            const contact = document.getElementById("contact").value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            if (!/^\d{10}$/.test(contact)) {
                alert("Contact number must be 10 digits.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>