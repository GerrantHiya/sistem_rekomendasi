-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 09, 2025 at 05:01 AM
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
-- Database: `u984988750_rekom`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime NOT NULL,
  `password_hash` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `email`, `name`, `last_login`, `password_hash`) VALUES
(1, 'gerrante.hiya@gmail.com', 'Gerrant Hiya', '2025-09-24 01:10:03', '$2y$10$v34SB7EDPzWwu7PENc..l.sunQK3kBYfPVfVcmMvmg9qn/cdFJ8fm');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `ID_Brand` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`ID_Brand`, `name`) VALUES
(1, 'Alexandre Christie'),
(2, 'SEIKO'),
(3, 'Fendi'),
(4, 'Louis Vuitton'),
(5, 'On Cloud'),
(6, 'Patek Philippe'),
(7, 'UBS');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `ID_Cart` int NOT NULL,
  `ID_Customers` int NOT NULL,
  `ID_Variant` int NOT NULL,
  `unit_price` double NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`ID_Cart`, `ID_Customers`, `ID_Variant`, `unit_price`, `quantity`) VALUES
(3, 1, 1, 12000000, 1),
(4, 1, 5, 1378920000, 1),
(5, 1, 4, 3000000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID_Categories` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID_Categories`, `name`) VALUES
(1, 'Accessories'),
(2, 'Top'),
(3, 'Bottom'),
(4, 'Shoes');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `ID_Customers` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`ID_Customers`, `name`, `email`, `password_hash`, `phone_number`, `last_login`, `address`, `city`, `province`, `postcode`) VALUES
(1, 'Gerrant Hiya', 'jipbagus@gmail.com', '$2y$10$UCzwNRekiVhVoKLJ5wEHZOvwRe.xJhG.p/LNso19KJ34GCJyk7i3C', '6285213321603', '2025-11-03 10:07:45', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `ID_Gender` int NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`ID_Gender`, `name`, `code`) VALUES
(1, 'Male', 'M'),
(2, 'Female', 'F'),
(3, 'Non-Binary', 'NB');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID_Orders` int NOT NULL,
  `ID_Customers` int NOT NULL,
  `place_at` datetime NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '0',
  `Shipping_Address` text COLLATE utf8mb4_unicode_ci,
  `Discount` double DEFAULT NULL,
  `Subtotal` double DEFAULT NULL,
  `Delivery_Cost` double DEFAULT NULL,
  `Total` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `ID_Order_Items` int NOT NULL,
  `ID_Orders` int NOT NULL,
  `ID_Variant` int NOT NULL,
  `Status` int NOT NULL,
  `Shipping_Address` text COLLATE utf8mb4_unicode_ci,
  `Discount` double DEFAULT NULL,
  `Subtotal` double DEFAULT NULL,
  `Delivery_Cost` double DEFAULT NULL,
  `Total` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `ID_Payments` int NOT NULL,
  `ID_Order` int NOT NULL,
  `Paid_at` datetime NOT NULL,
  `Status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID_Products` int NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SKU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID_Brand` int NOT NULL,
  `ID_Gender` int NOT NULL,
  `ID_Categories` int NOT NULL,
  `ID_SubCategories` int NOT NULL,
  `Description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID_Products`, `Name`, `SKU`, `ID_Brand`, `ID_Gender`, `ID_Categories`, `ID_SubCategories`, `Description`) VALUES
(1, 'Presage', 'PSG', 2, 1, 1, 2, 'Presage combines a Japanese aesthetic sense with traditional craftsmanship and Seiko’s mechanical watchmaking skills in an original collection that offers Japanese beauty, quality and long-lasting performance.'),
(2, 'Cloudmonster', 'OCC', 5, 1, 4, 6, 'On Running Cloudmonster didesain khusus untuk performa lari, baik itu 5K atau 20K sekali pun. Unit Cloud, yang terbukti mampu mengurangi rasa lelah dan menurunkan detak jantung, hadir dengan ukuran lebih besar. Desain yang bold, tetap dengan rasa ringan dan kuat.'),
(3, 'Cloudtilt', 'OCR', 5, 2, 4, 7, 'The On Cloudtilt Women\'s in pink delivers the perfect blend of modern performance and elegant, feminine style. Equipped with On\'s signature CloudTec® Phase technology, these shoes deliver ultra-comfortable cushioning and a smooth stride transition for all-day wear. Their sleek, lightweight, and stylish design makes them perfect for both casual activities and trendy athleisure styles. Experience premium comfort with a fresh pink touch, only at JD Sports Indonesia.'),
(4, 'Nautilus', '5811', 6, 1, 1, 2, 'Movement:\r\nSelf-winding mechanical movement. Caliber 26‑330 S C.\r\n\r\nPower Reserve: 35 - 45 hours'),
(5, 'Necklace', 'NCK', 7, 2, 1, 5, '-');

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `ID_Image` int NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID_Variant` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_image`
--

INSERT INTO `product_image` (`ID_Image`, `image`, `ID_Variant`) VALUES
(1, 'f762b4e651e55fade20131b9459c178ba183eb66.png', 1),
(2, '35edf23e45c1a3a4d6741f2b438bc9eb4ff1941d.png', 1),
(3, '73da7be58eab42c8468b7b07330a0d16cebb5786.png', 4),
(4, '50c3ac23dda6cbdce165ee0b7a096770eaf1e0d4.png', 4),
(5, 'ed9bde33df2ef1704cd1b636a0349e291916263a.webp', 2),
(6, 'abae2efb899d0c3a086e2912f63305ecec6ad032.png', 3),
(7, '6113ec7037e63b60fde2ededfa9ea66c5d92eca8.webp', 5),
(8, 'a181f8c1e8f356cf4a2c2746e3f844df00d70c5d.jpeg', 6);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `ID_Variants` int NOT NULL,
  `variant_sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_CatSize` int DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double DEFAULT NULL,
  `stock_qty` int DEFAULT NULL,
  `weight_gram` double DEFAULT NULL,
  `ID_Product` int NOT NULL,
  `ID_Size` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`ID_Variants`, `variant_sku`, `id_CatSize`, `color`, `price`, `stock_qty`, `weight_gram`, `ID_Product`, `ID_Size`) VALUES
(1, 'PSG-SPB463', NULL, 'silver, beige', 12000000, 15, 139, 1, 0),
(2, 'OCC-B', NULL, 'black', 3000000, 20, 250, 2, 0),
(3, 'OCR-P', NULL, 'pink', 2700000, 10, 200, 3, 0),
(4, 'PSG-SPB495', NULL, 'black', 3000000, 20, 100, 1, 0),
(5, '5811-1G-001', NULL, 'white gold, Sunburst blue with black-gradient rim', 1378920000, 2, 250, 4, 0),
(6, 'NCK-12000', NULL, 'rose gold', 12000000, 100, 55, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `ID_Shipments` int NOT NULL,
  `ID_Orders` int NOT NULL,
  `Tracking_Number` int NOT NULL,
  `Status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `ID_Size` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chest` double DEFAULT NULL,
  `body_length` double DEFAULT NULL,
  `waist` double DEFAULT NULL,
  `hip` double DEFAULT NULL,
  `thigh` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `ID_SubCategories` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID_Categories` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`ID_SubCategories`, `name`, `ID_Categories`) VALUES
(1, 'Bracelets', 1),
(2, 'Watches', 1),
(3, 'T-Shirts', 2),
(4, 'Shirt', 2),
(5, 'Necklace', 1),
(6, 'Casual', 4),
(7, 'Running', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`ID_Brand`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`ID_Cart`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID_Categories`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`ID_Customers`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`ID_Gender`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID_Orders`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`ID_Order_Items`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`ID_Payments`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID_Products`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`ID_Image`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`ID_Variants`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`ID_Shipments`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`ID_Size`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`ID_SubCategories`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `ID_Brand` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `ID_Cart` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID_Categories` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `ID_Customers` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gender`
--
ALTER TABLE `gender`
  MODIFY `ID_Gender` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID_Orders` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `ID_Order_Items` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `ID_Payments` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID_Products` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `ID_Image` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `ID_Variants` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `ID_Shipments` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `ID_Size` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `ID_SubCategories` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
