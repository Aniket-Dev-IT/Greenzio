-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 05, 2025 at 03:35 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`, `admin_password`) VALUES
(1, 'Shivanjali Chaurasia', 'Shivanjali012@gmail.com', '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `billing_details`
--

CREATE TABLE `billing_details` (
  `bill_id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` int NOT NULL,
  `state` varchar(255) NOT NULL,
  `mobile` int NOT NULL,
  `delivery_method` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `billing_details`
--

INSERT INTO `billing_details` (`bill_id`, `user_id`, `name`, `email`, `street`, `city`, `zip`, `state`, `mobile`, `delivery_method`, `payment_method`) VALUES
(1, 4, 'Shivanjali Chaurasia', 'shivanjali012@gmail.com', '910/A Chandapur Ka Hata,', 'Prayagaraj', 211003, 'Uttar Pradesh', 1234567890, 'normal', 'cod');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `ip_address` varchar(255) NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int NOT NULL,
  `cnt_fname` varchar(100) NOT NULL,
  `cnt_lname` varchar(100) NOT NULL,
  `cnt_email` varchar(255) NOT NULL,
  `cnt_message` text NOT NULL,
  `cnt_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`, `cnt_fname`, `cnt_lname`, `cnt_email`, `cnt_message`, `cnt_date`) VALUES
(1, 'Shivanjali', 'Chaurasiaa', 'shivanjali012@gmail.com', 'ok products', '0000-00-00'),
(2, 'Shivanjali', 'Chaurasiaa', 'shivanjali012@gmail.com', 'best website', '0000-00-00'),
(3, 'dkjv;', 'slfgvh;o', 'shivanjali012@gmail.com', 'Good one Greenzio', '0000-00-00'),
(4, 'Shivanjali', 'Chaurasiaa', 'shivanjali012@gmail.com', 'greenzio is the best', '0000-00-00'),
(5, 'Shivanjali', 'Chaurasiaa', 'shivanjali012@gmail.com', 'better products', '0000-00-00'),
(6, 'Shivanjali', 'Chaurasiaa', 'shivanjali012@gmail.com', 'Good One keep it up', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_total` int NOT NULL,
  `order_status` varchar(20) NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_total`, `order_status`, `order_date`) VALUES
(9, 4, 7549, 'confirm', '2019-05-20'),
(13, 4, 1049, 'confirm', '2019-05-20'),
(14, 4, 800, 'confirm', '2019-05-20'),
(17, 4, 1049, 'confirm', '2019-05-20'),
(18, 4, 1049, 'confirm', '2019-05-20'),
(19, 4, 11082, 'confirm', '2019-05-20');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_price` int NOT NULL,
  `product_quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pid` int NOT NULL,
  `pname` text NOT NULL,
  `category` text NOT NULL,
  `subcategory` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` int NOT NULL DEFAULT '0',
  `weight` varchar(50) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `pimage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pid`, `pname`, `category`, `subcategory`, `price`, `discount`, `weight`, `unit`, `brand`, `expiry_date`, `stock_quantity`, `pimage`) VALUES
(1, 'Fresh Red Apple', 'Fruits & Vegetables', 'Fruits', '120.00', 5, '1', 'kg', 'Farm Fresh', '2024-02-15', 50, 'assets/images/products/fruits-vegetables/Apple.jpg'),
(2, 'Premium Banana', 'Fruits & Vegetables', 'Fruits', '60.00', 0, '1', 'dozen', 'Farm Fresh', '2024-02-10', 30, 'assets/images/products/fruits-vegetables/Banana.jpg'),
(3, 'Sweet Grapes', 'Fruits & Vegetables', 'Fruits', '180.00', 10, '500', 'grams', 'Farm Fresh', '2024-02-12', 25, 'assets/images/products/fruits-vegetables/Grapes.jpg'),
(4, 'Fresh Strawberry', 'Fruits & Vegetables', 'Fruits', '250.00', 15, '250', 'grams', 'Farm Fresh', '2024-02-08', 20, 'assets/images/products/fruits-vegetables/Strawberry.jpg'),
(5, 'Pomegranate', 'Fruits & Vegetables', 'Fruits', '200.00', 0, '500', 'grams', 'Farm Fresh', '2024-02-20', 35, 'assets/images/products/fruits-vegetables/Pomegranate.jpg'),
(6, 'Fresh Lemon', 'Fruits & Vegetables', 'Fruits', '80.00', 0, '500', 'grams', 'Farm Fresh', '2024-02-18', 40, 'assets/images/products/fruits-vegetables/Lemon.jpg'),
(7, 'Fresh Potato', 'Fruits & Vegetables', 'Vegetables', '30.00', 0, '1', 'kg', 'Farm Fresh', '2024-03-01', 100, 'assets/images/products/fruits-vegetables/Potato.jpg'),
(8, 'Red Tomato', 'Fruits & Vegetables', 'Vegetables', '40.00', 0, '1', 'kg', 'Farm Fresh', '2024-02-15', 80, 'assets/images/products/fruits-vegetables/Tomato.jpg'),
(9, 'Green Capsicum', 'Fruits & Vegetables', 'Vegetables', '60.00', 0, '500', 'grams', 'Farm Fresh', '2024-02-12', 45, 'assets/images/products/fruits-vegetables/Capsicum.jpg'),
(10, 'Ladies Finger (Okra)', 'Fruits & Vegetables', 'Vegetables', '50.00', 0, '500', 'grams', 'Farm Fresh', '2024-02-14', 35, 'assets/images/products/fruits-vegetables/Ladies_Finger.jpg'),
(11, 'Basmati Rice Premium', 'Grains & Rice', 'Rice', '180.00', 0, '1', 'kg', 'India Gate', '2025-12-31', 200, 'assets/images/products/grains-rice/Basmati_Rice.jpg'),
(12, 'Tata Sampann Toor Dal', 'Grains & Rice', 'Dal & Pulses', '120.00', 5, '1', 'kg', 'Tata Sampann', '2025-06-15', 150, 'assets/images/products/grains-rice/Tata_Sampann_Toor_Dal.jpg'),
(13, 'Tata Sampann Masoor Dal', 'Grains & Rice', 'Dal & Pulses', '110.00', 0, '1', 'kg', 'Tata Sampann', '2025-06-20', 130, 'assets/images/products/grains-rice/Tata_Sampann_Masoor_Dal.jpg'),
(14, 'Aashirvaad Atta', 'Grains & Rice', 'Flour', '220.00', 0, '5', 'kg', 'Aashirvaad', '2024-08-30', 80, 'assets/images/products/grains-rice/Aashirvaad_Atta.jpg'),
(15, 'Pure Honey', 'Dairy & Bakery', 'Spreads', '280.00', 0, '500', 'grams', 'Dabur', '2025-12-31', 60, 'assets/images/products/dairy-bakery/Honey.jpg'),
(16, 'Kissan Mixed Fruit Jam', 'Dairy & Bakery', 'Spreads', '150.00', 10, '400', 'grams', 'Kissan', '2025-04-15', 45, 'assets/images/products/dairy-bakery/kissan_jam.jpg'),
(17, 'Peanut Butter', 'Dairy & Bakery', 'Spreads', '320.00', 0, '500', 'grams', 'Sundrop', '2025-03-20', 30, 'assets/images/products/dairy-bakery/Peanut_butter.jpg'),
(18, 'Chocolate Cookies', 'Dairy & Bakery', 'Bakery', '80.00', 0, '200', 'grams', 'Britannia', '2024-04-30', 70, 'assets/images/products/dairy-bakery/Cookie.jpg'),
(19, 'Everest Garam Masala', 'Spices & Seasonings', 'Spices', '45.00', 0, '100', 'grams', 'Everest', '2025-12-31', 90, 'assets/images/products/spices-seasonings/Everest_Garam_Masala.jpg'),
(20, 'Tata Salt', 'Spices & Seasonings', 'Salt', '25.00', 0, '1', 'kg', 'Tata', '2026-01-01', 200, 'assets/images/products/spices-seasonings/Tata_Salt.jpg'),
(21, 'Tata Sampann Turmeric Powder', 'Spices & Seasonings', 'Spices', '65.00', 0, '200', 'grams', 'Tata Sampann', '2025-10-15', 75, 'assets/images/products/spices-seasonings/Tata_Sampann_Powder_Turmeric.jpg'),
(22, 'Fortune Mustard Oil', 'Cooking Oils', 'Mustard Oil', '180.00', 5, '1', 'litre', 'Fortune', '2025-08-30', 60, 'assets/images/products/cooking-oils/fortune_mustard_oil.jpg'),
(23, 'Fortune Vivo Oil', 'Cooking Oils', 'Refined Oil', '220.00', 0, '1', 'litre', 'Fortune', '2025-09-15', 55, 'assets/images/products/cooking-oils/Fortune_Vivo_Oil.jpg'),
(24, 'Real Mixed Fruit Juice', 'Beverages', 'Juices', '45.00', 0, '200', 'ml', 'Real', '2024-06-30', 100, 'assets/images/products/beverages/real_juice.jpg'),
(25, 'Madhur Sugar', 'Beverages', 'Sugar & Sweeteners', '50.00', 0, '1', 'kg', 'Madhur', '2025-12-31', 150, 'assets/images/products/beverages/Madhur_Sugar.jpg'),
(26, 'Maggi Masala Noodles', 'Snacks & Instant Food', 'Noodles', '15.00', 0, '70', 'grams', 'Maggi', '2024-10-15', 200, 'assets/images/products/snacks-instant-food/maggi-masala-noodles.jpg'),
(27, 'Corn Flakes', 'Snacks & Instant Food', 'Breakfast Cereals', '180.00', 10, '500', 'grams', 'Kelloggs', '2024-12-31', 40, 'assets/images/products/snacks-instant-food/corn_flakes.jpg'),
(28, 'Saffola Masala Oats', 'Snacks & Instant Food', 'Breakfast Cereals', '220.00', 0, '500', 'grams', 'Saffola', '2025-03-15', 35, 'assets/images/products/snacks-instant-food/saffola_masala_oats.jpg'),
(29, 'Aloo Bhujia', 'Snacks & Instant Food', 'Namkeen', '120.00', 0, '400', 'grams', 'Haldiram', '2024-08-30', 80, 'assets/images/products/snacks-instant-food/aloo-bhujia.jpg'),
(30, 'Mixed Namkeen', 'Snacks & Instant Food', 'Namkeen', '150.00', 5, '500', 'grams', 'Haldiram', '2024-09-15', 65, 'assets/images/products/snacks-instant-food/Namkeen.jpg'),
(31, 'Fresh Butter', 'Dairy & Bakery', 'Dairy', '45.00', 4, '100', 'grams', 'Amul', '2025-09-12', 50, 'assets/images/products/dairy-bakery/Butter.jpg'),
(32, 'Fresh Buttermilk', 'Dairy & Bakery', 'Dairy', '20.00', 3, '200', 'ml', 'Mother Dairy', '2025-11-20', 40, 'assets/images/products/dairy-bakery/Buttermilk.jpg'),
(33, 'Cheese Slice', 'Dairy & Bakery', 'Dairy', '120.00', 20, '200', 'grams', 'Amul', '2025-10-13', 35, 'assets/images/products/dairy-bakery/Cheese.jpg'),
(34, 'Vanilla Custard Powder', 'Dairy & Bakery', 'Bakery', '85.00', 13, '100', 'grams', 'Weikfield', '2025-11-23', 45, 'assets/images/products/dairy-bakery/custard.jpg'),
(35, 'Vanilla Ice Cream', 'Dairy & Bakery', 'Dairy', '250.00', 6, '500', 'ml', 'Kwality Walls', '2025-09-29', 25, 'assets/images/products/dairy-bakery/Ice Cream.jpg'),
(36, 'Fresh Milk', 'Dairy & Bakery', 'Dairy', '56.00', 12, '1', 'litre', 'Mother Dairy', '2025-11-17', 100, 'assets/images/products/dairy-bakery/Milk.jpg'),
(37, 'Fresh Yogurt', 'Dairy & Bakery', 'Dairy', '30.00', 2, '200', 'grams', 'Nestle', '2025-12-03', 60, 'assets/images/products/dairy-bakery/Yogurt.jpg'),
(38, 'Fresh Artichokes', 'Fruits & Vegetables', 'Vegetables', '150.00', 1, '500', 'grams', '', '2025-11-04', 15, 'assets/images/products/fruits-vegetables/Artichokes.jpg'),
(39, 'Fresh Avocados', 'Fruits & Vegetables', 'Fruits', '200.00', 11, '500', 'grams', '', '2025-11-08', 20, 'assets/images/products/fruits-vegetables/avocados.jpg'),
(40, 'Fresh Blueberries', 'Fruits & Vegetables', 'Fruits', '300.00', 3, '125', 'grams', '', '2025-12-04', 10, 'assets/images/products/fruits-vegetables/blueberry.jpg'),
(41, 'Fresh Broccoli', 'Fruits & Vegetables', 'Vegetables', '50.00', 4, '250', 'grams', '', '2025-10-28', 35, 'assets/images/products/fruits-vegetables/broccoli.jpg'),
(42, 'Fresh Cabbage', 'Fruits & Vegetables', 'Vegetables', '30.00', 8, '500', 'grams', '', '2025-11-19', 50, 'assets/images/products/fruits-vegetables/Cabbage.jpg'),
(43, 'Fresh Carrots', 'Fruits & Vegetables', 'Vegetables', '40.00', 9, '500', 'grams', '', '2025-11-18', 60, 'assets/images/products/fruits-vegetables/carrots.jpg'),
(44, 'Fresh Cauliflower', 'Fruits & Vegetables', 'Vegetables', '40.00', 15, '500', 'grams', '', '2025-11-14', 45, 'assets/images/products/fruits-vegetables/cauliflower.jpg'),
(45, 'Fresh Garlic', 'Fruits & Vegetables', 'Vegetables', '60.00', 2, '250', 'grams', '', '2025-11-06', 80, 'assets/images/products/fruits-vegetables/Garlic.jpg'),
(46, 'Fresh Leeks', 'Fruits & Vegetables', 'Vegetables', '80.00', 4, '250', 'grams', '', '2025-10-09', 25, 'assets/images/products/fruits-vegetables/leeks.jpg'),
(47, 'Fresh Mangoes', 'Fruits & Vegetables', 'Fruits', '120.00', 14, '1', 'kg', '', '2025-11-27', 40, 'assets/images/products/fruits-vegetables/mangoes.jpg'),
(48, 'Fresh Onions', 'Fruits & Vegetables', 'Vegetables', '35.00', 6, '1', 'kg', '', '2025-11-10', 100, 'assets/images/products/fruits-vegetables/onions.jpg'),
(49, 'Fresh Oranges', 'Fruits & Vegetables', 'Fruits', '60.00', 4, '1', 'kg', '', '2025-10-08', 55, 'assets/images/products/fruits-vegetables/oranges.jpg'),
(50, 'Ambipur Air Freshener', 'Household Items', 'Cleaning', '299.00', 17, '275', 'ml', 'Ambipur', NULL, 30, 'assets/images/products/household-items/Air_Freshener_Ambipur.jpg'),
(51, 'Surf Excel Detergent', 'Household Items', 'Cleaning', '185.00', 3, '1', 'kg', 'Surf Excel', NULL, 50, 'assets/images/products/household-items/Detergent_Surf.jpg'),
(52, 'Vim Dish Soap', 'Household Items', 'Cleaning', '99.00', 16, '500', 'ml', 'Vim', NULL, 60, 'assets/images/products/household-items/Dish_Soap_Vim.jpg'),
(53, 'Lizol Floor Cleaner', 'Household Items', 'Cleaning', '169.00', 9, '975', 'ml', 'Lizol', NULL, 40, 'assets/images/products/household-items/Floor_Cleaner_Lizol.jpg'),
(54, 'Colin Glass Cleaner', 'Household Items', 'Cleaning', '115.00', 13, '500', 'ml', 'Colin', NULL, 35, 'assets/images/products/household-items/Glass_Cleaner_Colin.jpg'),
(55, 'Charmin Toilet Paper', 'Household Items', 'Paper Products', '250.00', 12, '4', 'rolls', 'Charmin', NULL, 25, 'assets/images/products/household-items/Toilet_Paper_Charmin.jpg'),
(56, 'Nivea Body Lotion', 'Personal Care', 'Skin Care', '299.00', 11, '400', 'ml', 'Nivea', NULL, 40, 'assets/images/products/personal-care/Body_Lotion_Nivea.jpg'),
(57, 'Axe Deodorant', 'Personal Care', 'Body Care', '199.00', 1, '150', 'ml', 'Axe', NULL, 50, 'assets/images/products/personal-care/Deodorant_Axe.jpeg'),
(58, 'Parachute Hair Oil', 'Personal Care', 'Hair Care', '135.00', 12, '300', 'ml', 'Parachute', NULL, 45, 'assets/images/products/personal-care/Hair_Oil_Parachute.jpg'),
(59, 'Head & Shoulders Shampoo', 'Personal Care', 'Hair Care', '345.00', 2, '650', 'ml', 'Head & Shoulders', NULL, 35, 'assets/images/products/personal-care/Shampoo_Head_Shoulders.png'),
(60, 'Dove Soap', 'Personal Care', 'Bath & Body', '55.00', 16, '100', 'grams', 'Dove', NULL, 80, 'assets/images/products/personal-care/Soap_Dove.jpg'),
(61, 'Colgate Toothpaste', 'Personal Care', 'Oral Care', '95.00', 18, '200', 'grams', 'Colgate', NULL, 70, 'assets/images/products/personal-care/Toothpaste_Colgate.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `mobile` int DEFAULT NULL,
  `password` varchar(256) NOT NULL,
  `gender` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `email`, `mobile`, `password`, `gender`) VALUES
(4, 'shivanjali012@gmail.com', NULL,'$2y$10$51AS8glELEzDpAl1kcEXGOrn3EiHunxerDWA9t0V3zlzB65hr/SPW', 'f'),
(8, 'shivansh@gmail.com', NULL,'$2y$10$51AS8glELEzDpAl1kcEXGOrn3EiHunxerDWA9t0V3zlzB65hr/SPW', 'm'),
(9, 'Kabir012@gmail.com', NULL,'$2y$10$51AS8glELEzDpAl1kcEXGOrn3EiHunxerDWA9t0V3zlzB65hr/SPW', 'm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `idx_admin_email` (`admin_email`);

--
-- Indexes for table `billing_details`
--
ALTER TABLE `billing_details`
  ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_ip_address` (`ip_address`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_order_date` (`order_date`),
  ADD KEY `idx_order_status` (`order_status`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `fk_order_details_order_id` (`order_id`),
  ADD KEY `fk_order_details_product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `idx_category` (`category`(50)),
  ADD KEY `idx_subcategory` (`subcategory`(50)),
  ADD KEY `idx_brand` (`brand`),
  ADD KEY `idx_expiry_date` (`expiry_date`),
  ADD KEY `idx_stock_quantity` (`stock_quantity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `idx_email` (`email`),
  ADD KEY `idx_mobile` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `billing_details`
--
ALTER TABLE `billing_details`
  MODIFY `bill_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_details_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
