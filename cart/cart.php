<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page.
    header('Location: ../reglogin/auth.php');
    exit();
}

// Connect to DB
$conn = new mysqli("localhost", "root", "", "quickbite");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- UPDATED LOGIC: FETCH CART ITEMS DIRECTLY FROM cart_items TABLE ---
$cart = [];
$subtotal = 0;
$totalItems = 0;

// The user ID should be retrieved from the session in a live environment.
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect or handle unauthenticated access
    header('Location: auth.php');
    exit();
}


// Ensure a user is logged in
if (isset($user_id)) {
    // SQL query to get cart items for the logged-in user
    // Now selecting item_id and description, which are needed for the page to function
    $stmt = $conn->prepare("
        SELECT 
            id, 
            item_id,
            name, 
            price, 
            quantity,
            image
        FROM 
            cart_items 
        WHERE 
            user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Construct the image path based on the item_id
            $image_path = "../admin/uploads/" . htmlspecialchars($row['item_id']) . "/" . htmlspecialchars($row['image']);

            $cart[$row['id']] = [
                'name'     => $row['name'],
                'price'    => $row['price'],
                'quantity' => $row['quantity'],
                'image'    => $image_path, // Storing the full path here
            ];
        }
    }
    $stmt->close();
}
// --- END OF UPDATED LOGIC ---

// Calculate totals based on the fetched cart data
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    $totalItems += $item['quantity'];
}

$deliveryFee = 200;
$total = $subtotal + $deliveryFee;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Food Delivery</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>

<?php require_once 'header/header.php'; ?>

    <div class="container">
        <div class="cart-header">
            <h1>Your Cart</h1>
            <p>Review your items before checkout</p>
        </div>

        <?php if (empty($cart)): ?>
            <div class="empty-cart">
                <div class="empty-cart-content">
                    <div class="empty-cart-icon">🛒</div>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added any items to your cart yet.</p>
                    <a href="../Homepage/index.php"><button class="browse-menu-btn">Browse Menu</button></a>
                </div>
            </div>
        <?php else: ?>
        <form method="post" action="update_cart.php">
        <div class="cart-content">
            <div class="cart-items">
                <?php foreach ($cart as $id => $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        <div class="item-details">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <span class="item-price">Rs <?= number_format($item['price'], 2) ?></span>
                        </div>
                        <div class="item-controls">
                            <div class="quantity-controls">
                                <input type="number" name="quantities[<?= $id ?>]" value="<?= $item['quantity'] ?>" min="1" class="quantity-input">
                            </div>
                            <a href="remove_from_cart.php?item_id=<?= $id ?>">
                                <button 
                                    type="button" 
                                    class="remove-btn" 
                                    onclick="return confirm('Are you sure you want to remove this item?');">
                                    Remove
                                </button>
                            </a>
                        </div>
                        <div class="item-total">
                            <span>Rs <?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="summary-card">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal (<?= $totalItems ?> items)</span>
                        <span>Rs <?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee</span>
                        <span>Rs <?= number_format($deliveryFee, 2) ?></span>
                    </div>
                    <hr class="summary-divider">
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>Rs <?= number_format($total, 2) ?></span>
                    </div>
                    <div class="cart-actions">
                        <a href="../Homepage/index.php"><button type="button" class="continue-shopping-btn">Update Cart</button></a>
                        <a href="../order/order.php"><button type="button" class="proceed-btn">Proceed to Order</button></a>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>FoodExpress</h3>
                <p>Fast & Fresh Food Delivered!</p>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p>📞 (555) 123-4567</p>
                <p>📧 info@foodexpress.com</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#">Facebook</a>
                    <a href="#">Instagram</a>
                    <a href="#">Twitter</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 FoodExpress. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
