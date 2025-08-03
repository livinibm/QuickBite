-- phpMyAdmin SQL Dump
-- QuickBite Database Structure
-- Corrected version based on main database
--
-- Database: `quickbite`
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `size` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `item_id`, `quantity`, `size`, `price`, `name`, `user_id`, `image`) VALUES
(1, 1, 2, 'Large', 8.50, 'Cheeseburger', NULL, NULL),
(2, 5, 1, 'Standard', 3.50, 'French Fries', NULL, NULL),
(3, 2, 1, 'Medium', 12.00, 'Pepperoni Pizza', '1', 'pepperoni_pizza.jpg'),
(4, 7, 3, 'Regular', 5.00, 'Chocolate Milkshake', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `popularity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `category`, `price`, `image`, `popularity`) VALUES
(1, 'Cheeseburger', 'Burgers', 650.00, 'cheeseburger.jpg', 95),
(2, 'Pepperoni Pizza', 'Pizza', 850.00, 'pepperoni_pizza.jpg', 88),
(3, 'Chicken Wings (6 pcs)', 'Appetizers', 525.00, 'chicken_wings.jpg', 91),
(4, 'Caesar Salad', 'Salads', 550.00, 'caesar_salad.jpg', 75),
(5, 'French Fries', 'Appetizers', 300.00, 'french_fries.jpg', 98),
(6, 'Vegetable Lasagna', 'Pasta', 750.00, 'veg_lasagna.jpg', 80),
(7, 'Chocolate Milkshake', 'Beverages', 450.00, 'choc_milkshake.jpg', 93);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `order_items` text DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `address`, `contact_number`, `order_items`, `total`, `order_date`) VALUES
(1, 'Livini Budara', '664, Galawila Road, Homagama', '0778313255', '2 x Burger, 1 x Coke', 1350.00, '2025-07-31 00:38:58'),
(2, 'John Doe', '123 Main Street, Colombo', '0771234567', '1 x Pizza, 2 x French Fries', 1450.00, '2025-08-01 12:30:00'),
(3, 'Jane Smith', '456 Park Avenue, Kandy', '0779876543', '1 x Caesar Salad, 1 x Milkshake', 1000.00, '2025-08-01 18:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `user_role` enum('customer','admin','delivery') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `contact_number`, `address`, `user_role`, `created_at`) VALUES
(1, 'Administrator', 'admin@quickbite.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0771234567', 'QuickBite Head Office, Colombo', 'admin', '2024-07-30 10:00:00'),
(2, 'Livini Budara', 'livinibudara2002@gmail.com', '$2y$10$Zx.ZY0KuDuSXzkyQP46Ji.Iw8IEuivifMkAmt08t3axzluFOdbxgm', '0778313255', '664, Galawila Road, Homagama', 'customer', '2025-08-02 08:21:46'),
(3, 'John Doe', 'john.doe@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890', '0771234567', '123 Main Street, Colombo', 'customer', '2025-08-01 10:00:00'),
(4, 'Jane Smith', 'jane.smith@example.com', '$2y$10$1234567890abcdefghijklmnopqrstuvwxyz', '0779876543', '456 Park Avenue, Kandy', 'customer', '2025-08-01 11:00:00');

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
