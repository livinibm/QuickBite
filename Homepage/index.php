<?php
session_start();

// Check if the user is logged in.
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page.
    header('Location: ../reglogin/auth.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>QuickBite - Home</title>
    <link rel="stylesheet" href="index.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <header class="navbar">
        <div class="logo">QuickBite</div>
        <nav>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="../cart/cart.php">View Cart</a></li>
                <li><a href="#" onclick="confirmLogout()" class="logout-btn">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="banner">
        <section class="hero" id="home">
            <h1>Fast & Fresh Food Delivered to You</h1>
            <p>Delicious meals just a click away!</p>
        </section>
    </div>

    <section class="dishes" id="dishes">
        <h2>Popular Dishes</h2>
        <div class="dish-list">
            <div class="dish">
                <img src="assests/cheeseburger.jpeg" alt="Cheese Burger" />
                <h3>Cheese Burger</h3>
                <p>Rs. 950</p>
                <div class="add-to-cart-controls">
                    <div class="quantity-control">
                        <button class="quantity-btn minus-btn">-</button>
                        <span class="quantity-value">1</span>
                        <button class="quantity-btn plus-btn">+</button>
                    </div>
                    <button class="add-to-cart-btn" data-id="1">Add to Cart</button>
                </div>
                </div>
            <div class="dish">
                <img src="assests/chickenpizza.jpeg" alt="Chicken Pizza" />
                <h3>Chicken Pizza</h3>
                <p>Rs. 1100</p>
                <div class="add-to-cart-controls">
                    <div class="quantity-control">
                        <button class="quantity-btn minus-btn">-</button>
                        <span class="quantity-value">1</span>
                        <button class="quantity-btn plus-btn">+</button>
                    </div>
                    <button class="add-to-cart-btn" data-id="2">Add to Cart</button>
                </div>
                </div>
            <div class="dish">
                <img src="assests/vegnoodles.jpeg" alt="Veg Noodles" />
                <h3>Veg Noodles</h3>
                <p>Rs. 850</p>
                <div class="add-to-cart-controls">
                    <div class="quantity-control">
                        <button class="quantity-btn minus-btn">-</button>
                        <span class="quantity-value">1</span>
                        <button class="quantity-btn plus-btn">+</button>
                    </div>
                    <button class="add-to-cart-btn" data-id="3">Add to Cart</button>
                </div>
                </div>
        </div>
    </section>

    <div class="main-content">
        <h2>Our Menu</h2>
        <div class="container">
            <div class="category-filter">
    <div class="select-wrapper">
        <select id="categoryFilter">
            <option value="all">All Categories</option>
            <option value="Pizza">Pizza</option>
            <option value="Burgers">Burgers</option>
            <option value="Salads">Salads</option>
            <option value="Appetizers">Appetizers</option>
            <option value="Desserts">Desserts</option>
            <option value="Beverages">Beverages</option>
        </select>
    </div>
    <button onclick="filterMenu()">Filter</button>
</div>
            
            <div class="menu-grid" id="menuGrid">
            </div>
            
            <div id="loader" style="text-align: center; margin-top: 2rem; display: none;">
                <p>Loading menu items...</p>
            </div>
        </div>
    </div>

    <section class="about" id="about">
        <h2>About Us</h2>
        <p>At QuickBite, we deliver hot, delicious food to your doorstep with speed and care. Experience flavor and convenience like never before.</p>
    </section>

    <section class="contact" id="contact">
        <section class="contact" id="contact">
    <h2>Contact Us</h2>
    <form id="contactForm">
        <input type="text" name="name" placeholder="Your Name" required />
        <input type="email" name="email" placeholder="Your Email" required />
        <textarea rows="4" name="message" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
    </form>
    <p id="form-message" style="text-align: center; margin-top: 1rem;"></p>
    </section>

    <footer>
        <p>&copy; 2025 QuickBite. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </footer>
    <script src="script.js"></script>
</body>

</html>