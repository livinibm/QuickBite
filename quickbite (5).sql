-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2025 at 07:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quickbite`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `item_id`, `quantity`, `price`, `name`, `image`, `created_at`) VALUES
(17, 9, 22, 1, 1350.00, 'Veggie Supreme', 'menu_1754236036_1778.jpeg', '2025-08-03 16:51:31'),
(18, 10, 19, 1, 1200.00, 'Margherita Pizza', 'menu_1754235487_3607.jpeg', '2025-08-04 16:39:04'),
(19, 10, 24, 2, 1200.00, 'Classic Cheeseburger', 'menu_1754236197_5276.jpeg', '2025-08-04 16:39:10'),
(20, 10, 38, 1, 1250.00, 'Chocolate Lava Cake', 'menu_1754236982_9294.jpeg', '2025-08-04 16:39:18'),
(21, 10, 38, 1, 1250.00, 'Chocolate Lava Cake', 'menu_1754236982_9294.jpeg', '2025-08-04 16:39:21'),
(22, 10, 46, 1, 120.00, 'Bottled Water', 'menu_1754237458_4368.jpeg', '2025-08-04 16:39:28'),
(23, 10, 48, 1, 600.00, 'Milkshakes', 'menu_1754237605_1576.jpeg', '2025-08-04 16:39:33'),
(24, 11, 41, 1, 1200.00, 'Cheesecake', 'menu_1754237143_6772.jpeg', '2025-08-08 19:06:41'),
(25, 11, 25, 2, 1400.00, 'Smoky BBQ Bacon Burger', 'menu_1754236268_9690.jpeg', '2025-08-08 19:06:50'),
(29, 12, 36, 3, 1450.00, 'Mozzarella Sticks', 'menu_1754236886_6618.jpeg', '2025-08-10 08:23:01'),
(30, 12, 52, 1, 1550.00, 'Fettuccine Alfredo', 'menu_1754238158_1713.jpeg', '2025-08-10 10:16:03'),
(31, 12, 32, 1, 1450.00, 'Quinoa Bowl', 'menu_1754236690_9578.jpeg', '2025-08-10 10:16:16'),
(33, 15, 42, 2, 990.00, 'Brownie Sundae', 'menu_1754237190_3492.jpeg', '2025-08-11 02:12:14'),
(34, 13, 20, 1, 1400.00, 'Pepperoni Passion', 'menu_1754235862_5936.jpeg', '2025-08-11 02:25:56'),
(35, 13, 32, 2, 1450.00, 'Quinoa Bowl', 'menu_1754236690_9578.jpeg', '2025-08-11 02:26:01'),
(46, 17, 21, 1, 1550.00, 'BBQ Chicken', 'menu_1754235938_6981.jpeg', '2025-08-11 03:01:01'),
(47, 17, 21, 1, 1550.00, 'BBQ Chicken', 'menu_1754235938_6981.jpeg', '2025-08-11 03:02:21'),
(48, 17, 20, 1, 1400.00, 'Pepperoni Passion', 'menu_1754235862_5936.jpeg', '2025-08-11 03:02:24'),
(54, 22, 37, 1, 950.00, 'Garlic Bread', 'menu_1754236938_2989.jpeg', '2025-08-11 03:33:23'),
(55, 22, 25, 1, 1400.00, 'Smoky BBQ Bacon Burger', 'menu_1754236268_9690.jpeg', '2025-08-11 03:34:22'),
(56, 22, 26, 1, 1600.00, 'Spicy Jalapeño Burger', 'menu_1754236319_6477.jpeg', '2025-08-11 03:34:25'),
(58, 24, 30, 1, 1100.00, 'Garden Fresh Salad', 'menu_1754236563_9107.jpeg', '2025-08-11 04:14:45'),
(59, 24, 21, 1, 1550.00, 'BBQ Chicken', 'menu_1754235938_6981.jpeg', '2025-08-11 04:15:47'),
(60, 24, 20, 1, 1400.00, 'Pepperoni Passion', 'menu_1754235862_5936.jpeg', '2025-08-11 04:15:50');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `message`, `submission_date`) VALUES
(11, 'thanuj', 'livbudara@gmail.com', 'hgsdzsgd', '2025-08-11 02:56:08'),
(14, 'tahnuj', 'tahnuj@gmail.com', 'hello', '2025-08-11 03:31:59'),
(15, 'thanuj', 'tharaka@gmail.com', 'hello', '2025-08-11 04:13:29');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `popularity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `category`, `price`, `image`, `popularity`) VALUES
(19, 'Margherita Pizza', 'Pizza', 1200.00, 'menu_1754235487_3607.jpeg', 0),
(20, 'Pepperoni Passion', 'Pizza', 1400.00, 'menu_1754235862_5936.jpeg', 0),
(21, 'BBQ Chicken', 'Pizza', 1550.00, 'menu_1754235938_6981.jpeg', 0),
(22, 'Veggie Supreme', 'Pizza', 1350.00, 'menu_1754236036_1778.jpeg', 0),
(23, 'Meat Feast', 'Pizza', 1700.00, 'menu_1754236132_6111.jpeg', 0),
(24, 'Classic Cheeseburger', 'Burgers', 1200.00, 'menu_1754236197_5276.jpeg', 0),
(25, 'Smoky BBQ Bacon Burger', 'Burgers', 1400.00, 'menu_1754236268_9690.jpeg', 0),
(26, 'Spicy Jalapeño Burger', 'Burgers', 1600.00, 'menu_1754236319_6477.jpeg', 0),
(27, 'Grilled Chicken Burger', 'Burgers', 1550.00, 'menu_1754236370_8365.jpeg', 0),
(28, 'Mushroom Swiss Burger', 'Burgers', 1250.00, 'menu_1754236453_9601.jpeg', 0),
(29, 'Caesar Salad', 'Salads', 950.00, 'menu_1754236513_1565.jpeg', 0),
(30, 'Garden Fresh Salad', 'Salads', 1100.00, 'menu_1754236563_9107.jpeg', 0),
(31, 'Greek Salad', 'Salads', 1050.00, 'menu_1754236620_4719.jpeg', 0),
(32, 'Quinoa Bowl', 'Salads', 1450.00, 'menu_1754236690_9578.jpeg', 0),
(33, 'French Fries', 'Appetizers', 850.00, 'menu_1754236725_8206.jpeg', 0),
(34, 'Onion Rings', 'Appetizers', 800.00, 'menu_1754236765_1310.jpeg', 0),
(35, 'Chicken Wings', 'Appetizers', 1500.00, 'menu_1754236823_9713.jpeg', 0),
(36, 'Mozzarella Sticks', 'Appetizers', 1450.00, 'menu_1754236886_6618.jpeg', 0),
(37, 'Garlic Bread', 'Appetizers', 950.00, 'menu_1754236938_2989.jpeg', 0),
(38, 'Chocolate Lava Cake', 'Desserts', 1250.00, 'menu_1754236982_9294.jpeg', 0),
(41, 'Cheesecake', 'Desserts', 1200.00, 'menu_1754237143_6772.jpeg', 0),
(42, 'Brownie Sundae', 'Desserts', 990.00, 'menu_1754237190_3492.jpeg', 0),
(44, 'Ice Cream', 'Desserts', 700.00, 'menu_1754237328_2781.jpeg', 0),
(45, 'Soft Drinks', 'Beverages', 200.00, 'menu_1754237392_1583.jpeg', 0),
(46, 'Bottled Water', 'Beverages', 120.00, 'menu_1754237458_4368.jpeg', 0),
(47, 'Freshly Squeezed Juice', 'Beverages', 400.00, 'menu_1754237563_1178.jpeg', 0),
(48, 'Milkshakes', 'Beverages', 600.00, 'menu_1754237605_1576.jpeg', 0),
(49, 'Iced Tea', 'Beverages', 650.00, 'menu_1754237850_3052.jpeg', 0),
(50, 'Spaghetti Bolognese', 'Pasta', 1200.00, 'menu_1754237983_3259.jpeg', 0),
(51, 'Lasagna', 'Pasta', 1300.00, 'menu_1754238103_4779.jpeg', 0),
(52, 'Fettuccine Alfredo', 'Pasta', 1550.00, 'menu_1754238158_1713.jpeg', 0),
(53, 'Spaghetti Carbonara', 'Pasta', 1250.00, 'menu_1754238211_3717.jpeg', 0),
(54, 'Spaghetti Aglio e Olio', 'Pasta', 1450.00, 'menu_1754238255_5408.jpeg', 0),
(55, 'Macaroni and Cheese', 'Pasta', 1250.00, 'menu_1754238304_5619.jpeg', 0),
(59, 'Chocolate Cake', 'Desserts', 500.00, 'menu_1754886075_7814.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `order_items` text DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `address`, `contact_number`, `order_items`, `total`, `order_date`) VALUES
(15, 'thanuj', 'dfgjhk', '0778313255', '2 x Brownie Sundae', 2180.00, '2025-08-11 07:44:49'),
(16, 'Tharaka', '665, Galle Road ,Aluthgama', '0779648563', '1 x Pepperoni Passion, 2 x Quinoa Bowl, 1 x Chocolate Lava Cake', 5750.00, '2025-08-11 07:57:05'),
(17, 'Budara', '664, galawila road, homagama', '0778313255', '1 x Pepperoni Passion, 2 x Quinoa Bowl, 1 x Chocolate Lava Cake', 5750.00, '2025-08-11 07:58:21'),
(18, 'Gayan', '665, New Town, Mahiyanganaya', '0763598512', '1 x Spicy Jalapeño Burger, 1 x Caesar Salad, 2 x Greek Salad', 4850.00, '2025-08-11 08:00:39'),
(19, 'Hashan', 'No,21, Horana, Mathugama', '0778944744', '2 x Brownie Sundae, 1 x BBQ Chicken, 1 x Veggie Supreme', 5080.00, '2025-08-11 08:14:35'),
(20, 'Ruwan', 'No 32, Nawinna, Homagama', '0778457544', '2 x Brownie Sundae, 1 x BBQ Chicken, 1 x Meat Feast', 5430.00, '2025-08-11 08:15:40'),
(21, 'Livini', '664, Galawila road, Homagama', '0778313255', '1 x BBQ Chicken, 1 x BBQ Chicken, 1 x Pepperoni Passion', 4700.00, '2025-08-11 08:33:42'),
(22, 'thanuj', '664, galawila road, homagama', '0778313255', '1 x Chicken Wings, 1 x BBQ Chicken, 1 x Pepperoni Passion', 4650.00, '2025-08-11 08:47:17'),
(23, 'Dahanajaya', '664fcgvhbjnkm', '0778313255', '1 x Garlic Bread, 1 x Smoky BBQ Bacon Burger, 1 x Spicy Jalapeño Burger', 4150.00, '2025-08-11 09:05:34'),
(24, 'Livini', '664, Galawila Rd, Homagama', '0778313255', '1 x Garden Fresh Salad, 1 x BBQ Chicken, 1 x Pepperoni Passion', 4250.00, '2025-08-11 09:47:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `user_role` enum('customer','admin','delivery') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `nic` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `contact_number`, `address`, `user_role`, `created_at`, `nic`) VALUES
(1, 'Administrator', 'admin@quickbite.lk', '$2y$10$rqCoUI2N06XT.0H0jCoYY.tU5GGnDqVrUDi8uX3ZeXwmyntt3DRKG', '0771234567', 'QuickBite Head Office, Colombo', 'admin', '2024-07-30 10:00:00', NULL),
(13, 'Tharaka', 'tharaka@gmail.com', '$2y$10$AtNITz2Rd1d0ydA0q2KI/.CAzhG4fFhn34a6e4jFfYBYmtzn5UNq2', '0775545243', '', 'customer', '2025-08-11 02:08:19', '200295867423'),
(14, 'Thanuj', 'thanuj@gmail.com', '$2y$10$bYyW6Q9uX6932s4.Hgn4JOFWQH8cPmxHATcaRHUrPOj6/dF7qjqcO', '0761977690', '', 'customer', '2025-08-11 02:09:24', '200117302289'),
(24, 'Livini', 'user@gmail.com', '$2y$10$uBGfv0qmPWiruLXSwJp2hOHDCXz/bCrTD/wpmD0bHGmngbg3zn3ja', '0778313255', '', 'customer', '2025-08-11 04:12:39', '200273204482');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
