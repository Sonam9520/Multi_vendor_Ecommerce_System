-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 10:28 AM
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
-- Database: `ecommerce_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(16, 22, 11, 1, '2025-04-21 16:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `contact_form_submissions`
--

CREATE TABLE `contact_form_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_form_submissions`
--

INSERT INTO `contact_form_submissions` (`id`, `name`, `email`, `message`, `submission_date`) VALUES
(1, 'gautam', 'uttkarash777@gmail.com', 'xdr esr', '2024-12-30 17:41:05');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `total_price`, `status`, `created_at`) VALUES
(27, 28, 0, 150.00, 'pending', '2025-01-03 19:56:43'),
(28, 15, 0, 5850.00, 'pending', '2025-01-03 22:05:11'),
(29, 22, 0, 350.00, 'pending', '2025-04-21 16:04:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(9, 27, 16, 1, 150.00),
(10, 28, 10, 1, 500.00),
(11, 28, 11, 1, 350.00),
(12, 28, 12, 1, 5000.00),
(13, 29, 11, 1, 350.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `vendor_id`, `name`, `description`, `price`, `quantity`, `image`, `status`) VALUES
(10, 11, 'Web Development', 'Full-stack web development services. Specializing in custom websites, e-commerce solutions, and responsive designs.', 500.00, 10, '6776edb8cc12e9.34057755.webp', 'published'),
(11, 11, ' Digital Marketing', 'Social media marketing, email campaigns, and online advertising to grow your brand and customer base.', 350.00, 56, '6776ee3e1f91b8.46530664.webp', 'pending'),
(12, 11, 'Event Planning and Management', 'Wedding Planning: Offering full-service wedding planning or specific services like coordination, decoration, or design.\r\nParty/Event Planning: Planning corporate events, birthday parties, or other special occasions.', 5000.00, 50, '6776ee9b8df045.82052254.webp', 'pending'),
(13, 12, ' Mobile App Development', ' Custom mobile app development for iOS and Android platforms, with a focus on user experience.', 800.00, 979, '6776f126538836.75611325.webp', 'pending'),
(14, 12, 'Video Editing', ' Professional video editing services for YouTube, social media, and corporate videos. Includes color correction and audio mixing.', 250.00, 87, '6776f1beaebec6.65060311.webp', 'pending'),
(15, 13, 'Virtual Assistant', 'Remote administrative support including email management, data entry, scheduling, and customer service.', 897.00, 89, '6776f304359ca1.00322196.webp', 'pending'),
(16, 18, 'Graphic Design', 'Professional logo design, branding, and marketing materials. Tailored to suit your business style.', 150.00, 34, '67781eab4edd85.15015920.jpeg', 'pending'),
(17, 18, 'Photography', 'Professional photography services for weddings, events, and portrait photography.', 400.00, 45, '67781ef7f33b03.97362201.jpeg', 'pending'),
(18, 19, 'Content Writing', 'High-quality content writing services for blogs, articles, and website copy. SEO optimized.', 100.00, 78, '67781ff4497576.42883060.jpeg', 'pending'),
(20, 19, 'Fitness Training', ' Online fitness classes, personalized workout plans, and nutrition advice.', 449.00, 87, '6778225a56f090.93107405.jpeg', 'pending'),
(21, 20, 'Custom Crafting', 'Handmade items like jewelry, stationery, and personalized gifts.', 98.00, 89, '67782767ab0391.87642561.jpeg', 'pending'),
(22, 20, 'Tech Support', 'Hardware troubleshooting, software installation, and IT consultancy.', 97.00, 97, '677827d0253c93.37612901.jpeg', 'pending'),
(23, 21, 'Content Writing', 'kodw kdo oii eodi f test87', 97997997.00, 45868698, '6778d2c8517869.57498947.jpeg', 'pending'),
(24, 21, 'Content Writing', 'kodw kdo oii eodi f test87', 979.00, 45, '6778d318ea8612.26733025.jpeg', 'pending'),
(25, 14, '35235', 'cgxdrfxg fgzsfdxg', 54.00, 46, '&lt;br /&gt;\r\n&lt;b&gt;Warning&lt;/b&gt;:  Undefined variable $current_image in &lt;b&gt;C:\\xampp\\htdocs\\multi-vendor-ecommerce-system\\pages\\vendor_dashboard.php&lt;/b&gt; on line &lt;b&gt;203&lt;/b&gt;&lt;br /&gt;\r\n', 'pending'),
(28, 14, 'beauty service', 'We are offering you best Beauty Services', 3000.00, 10, '68066a2817d359.34487163.png', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'sona123@gmail.com', '$2y$10$cjY99IUL4YXDKW0C8NjDBeTQmceTzNUAW9QNmQr5Acz0a1oL5.lzi', '2024-12-29 09:14:12', 'admin'),
(2, 'sona1234@gmail.com', '$2y$10$UIJvz2iIBTZBhCn.RGyyweQpQtJ3IT.owUWBHA/5y2a4jDhCSSEKe', '2024-12-29 09:17:37', 'customer'),
(3, 'sona12345@gmail.com', '$2y$10$op7B/s1A1QtrVfRV9RtiXuy4TjIPR0rHEsbi3v9vzIesvK1VxXP5C', '2024-12-29 09:18:19', 'customer'),
(5, 'sonam12598@gmail.com', '$2y$10$7sFUCdKbsq1DR.IeFDm/7uLt6OKYiHJ0f.x1BfekI1ONWesIeVJ5C', '2024-12-29 09:57:13', 'vendor'),
(6, 'sonamr12345@gmail.com', '$2y$10$uYCzG5OjNviOXeNUujJOSu7fINpRnxbfljSWdkEzGjAGhlnE7xYuS', '2024-12-29 09:58:36', 'vendor'),
(7, 'oassp@gmail.com', '$2y$10$UUrqbNHTrEjegxaBAIi0KudQLjCIcre5N16iBTy1LtOxf2wQXxl4e', '2024-12-29 10:04:13', 'vendor'),
(8, 'jsio@gmail.com', '$2y$10$.cgB5jt01vOcYyKDk1zSJulpcviyuQ8Z1u/jGCmwgWaWhRh268UAu', '2024-12-29 10:05:59', 'vendor'),
(9, 'siya12345@gmail.com', '$2y$10$bMgW7/OOXhIEgmzS8nfzJefmaAu92i.5IB.4o.LtCslc1kFRupzOS', '2024-12-29 10:38:36', 'customer'),
(10, 'siya123456@gmail.com', '$2y$10$9q4jEukpSYLpRJ6eyx89.ONEhXcy6eouYVZkPnBZyJtmPLJ.yflYu', '2024-12-29 10:39:22', 'customer'),
(11, 'siya123455676@gmail.com', '$2y$10$UKgfXzjVEDaFAbvcAzaGJud7/IxSvXxHTxocu9l9B/IBdnJtYGkzq', '2024-12-29 10:44:47', 'customer'),
(12, 'poonam123456@gmail.com', '$2y$10$7pKdDROzf3Pswn0EZ8YShemDRVFH/saXyxTAZsAqskoMelstO6rQe', '2024-12-29 10:45:29', 'vendor'),
(14, 'sonam123456@gmail.com', '$2y$10$7yp4dFZqVVd68M/Wq2PIz.UH9230ux/hx.kkkkSzbyNrzHZBPkFxe', '2024-12-29 17:41:38', 'vendor'),
(15, 'sona9520@gmail.com', '$2y$10$yzzkn1LEqgX8hmPSveF2NOyZZPz2SeeV/LA/oRxxXf6Z0nVg8j0y6', '2024-12-30 12:31:50', 'admin'),
(16, 'user1@gmail.com', '$2y$10$SMTnhleIZF2L23M772uuWOyHs8W4kHBChNlE3NuE4BWO78o7pL//m', '2025-01-02 18:50:39', 'vendor'),
(17, 'user2@gmail.com', '$2y$10$ONG/aEFRYoGe0ktfHPApseK0MRl8wXbVLpu6T1tqgZ.jdDlrJrD8S', '2025-01-02 18:54:31', 'customer'),
(18, 'user3@gmail.com', '$2y$10$tFFCb2gHJ7nMgCSWEnvbdetkV0uvxtTd.D3mLsCQeq4bliR7nBa06', '2025-01-02 18:56:12', 'vendor'),
(19, 'user4@gmail.com', '$2y$10$fK/.EZV9ElIT/AaU7oy4EOejMQ8OrgPmLam/.18ZInlbnS9/WVZ66', '2025-01-02 19:06:49', 'vendor'),
(20, 'Sonam1@gmail.com', '$2y$10$pnVOVUTGrBQfOgqat7LnieqtdPUTyCyYbst.xt1iJIbIGNUZBZAzO', '2025-01-02 20:00:58', 'vendor'),
(21, 'Poonam@gmail.com', '$2y$10$cKMbCwCFGRv4c2rEa.o6H.RPpq/K1oprPF7r9QuVGV3j25nJCtMuu', '2025-01-02 20:09:59', 'vendor'),
(22, 'sonam123@gmail.com', '$2y$10$ZGeG0G7ot8iVkqvZ33sMzO5gE.d2SgfgcEP8mur7aGYbPJi9mVgme', '2025-01-03 16:57:48', 'vendor'),
(23, 'sonam1234@gmail.com', '$2y$10$xHaq62boYIVMl2h7avvJs.Xe/PtQRRDIqZouuT4.CqVwR/Phnetv.', '2025-01-03 17:12:09', 'vendor'),
(24, 'sonam12345@gmail.com', '$2y$10$9FvMsHr3W202IT.66arcpe0Y5A4/Q0Np7B4X9FkWCqS8O24YtOobK', '2025-01-03 17:13:30', 'vendor'),
(25, 'sonam12@gmail.com', '$2y$10$LRqsE6sWl9uQF6oX9aX69eI5QUjozE18ANZFl6pSrGz4lF/4wPh.2', '2025-01-03 17:16:04', 'vendor'),
(26, 'sonam162@gmail.com', '$2y$10$tQm3waHGzK5u7DDBM4.lnu.V/mYP7VscZyQ9KUfwW07F83sT06XBO', '2025-01-03 17:26:06', 'vendor'),
(27, 'sonam112@gmail.com', '$2y$10$pW4pvvWi7KbQKlubgRPaUeQzMWNna6zBtkmHJZSlSO8h0crl7QzUK', '2025-01-03 17:33:15', 'vendor'),
(28, 'sonam1123@gmail.com', '$2y$10$Hbj9OKzqLL3k4cbaqb7rF.9vwBV5tRk1tDz/N7Dlb8ay/OmZVaeh.', '2025-01-03 17:49:07', 'vendor'),
(29, 'raman123@gmail.com', '$2y$10$Hv9gcTcbQgcyWmAnJdPH0ugo9SGgNQqxKi7Sxch/OKY/loatErYK6', '2025-01-04 06:08:00', 'customer'),
(30, 'sonam12345678@gmail.com', '$2y$10$Eq75cv6YPVAjFv.wvovPuOHqlPAeW/n5f4OU7T7lfktPcVQwnyLDi', '2025-01-04 06:15:51', 'vendor');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_name` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `vendor_status` varchar(50) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `user_id`, `vendor_name`, `contact_email`, `contact_phone`, `vendor_status`) VALUES
(11, 19, 'user 4', 'user4@gmail.com', '9488760970', 'pending'),
(12, 20, 'Sonam 1', 'Sonam1@gmail.com', '923743877937', 'pending'),
(13, 21, 'Sarah Lee', 'Poonam@gmail.com', '93447597397', 'pending'),
(14, 22, 'Jane Smith', 'sonam123@gmail.com', '345623454', 'pending'),
(15, 23, 'Michael Brown', 'sonam1234@gmail.com', '87008979797', 'pending'),
(16, 24, ' Lisa Green', 'sonam12345@gmail.com', '2334455544', 'pending'),
(17, 25, ' Alice Wilson', 'sonam12@gmail.com', '123215465', 'pending'),
(18, 26, ' Robert Johnson', 'sonam162@gmail.com', '5645546576', 'pending'),
(19, 27, ' Alice Wilson', 'sonam112@gmail.com', '9879689698', 'pending'),
(20, 28, 'John Venn', 'sonam1123@gmail.com', '39874893274', 'pending'),
(21, 30, 'Prince', 'sonam12345678@gmail.com', '78678696997', 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact_form_submissions`
--
ALTER TABLE `contact_form_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order` (`order_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contact_phone` (`contact_phone`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `contact_form_submissions`
--
ALTER TABLE `contact_form_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
