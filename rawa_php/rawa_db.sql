-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18 سبتمبر 2026 الساعة 19:46
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rawa_db`
--

-- --------------------------------------------------------

--
-- بنية الجدول `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `category`
--

INSERT INTO `category` (`id`, `title`, `creation_date`) VALUES
(1, 'نباتات مزهرة', '2026-05-29 05:59:24'),
(2, 'نباتات منزلية', '2026-05-29 05:59:24'),
(3, 'نباتات مميزة', '2026-05-29 05:59:24'),
(4, 'نباتات داخلية', '2026-05-29 05:59:24');

-- --------------------------------------------------------

--
-- بنية الجدول `offer`
--

CREATE TABLE `offer` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `care_catalog` text DEFAULT NULL,
  `org_price` decimal(8,2) DEFAULT 0.00,
  `discount` int(11) DEFAULT 0,
  `cat_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `status` varchar(20) DEFAULT 'available',
  `care_level` varchar(20) DEFAULT 'easy',
  `location_type` varchar(20) DEFAULT 'indoor',
  `temp_tolerance` varchar(20) DEFAULT 'medium',
  `water_freq` varchar(20) DEFAULT 'medium',
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `offer`
--

INSERT INTO `offer` (`id`, `title`, `image`, `description`, `features`, `care_catalog`, `org_price`, `discount`, `cat_id`, `user_id`, `quantity`, `status`, `care_level`, `location_type`, `temp_tolerance`, `water_freq`, `creation_date`) VALUES
(1, 'الياسمين', 'uploads/jasmine.jpg', 'نبتة عطرية تنشر الجمال والراحة الجميلة، سهلة العناية ومناسبة للمبتدئين.', 'رائحة عطرية فواحة، سرعة النمو، تزيين المداخل', 'الري: عند جفاف التربة السطحية\nالإضاءة: شمس غير مباشرة\nالتسميد: مرة شهرياً', 45.00, 0, 1, 1, 10, 'available', 'easy', 'indoor', 'medium', 'medium', '2026-09-18 16:29:13'),
(2, 'التوليب', 'uploads/tulip.jpg', 'زهرة رقيقة تعشق الأجواء الهادئة، تضيف لمسة رومانسية للمكان.', 'ألوان جذابة، مظهر أنيق، مثالية للهدايا', 'الري: اعتذال في الري\nالإضاءة: جو معتدل إلى بارد\nالتسميد: سماد نتروجيني خفيف', 60.00, 5, 1, 1, 8, 'available', 'medium', 'indoor', 'low', 'medium', '2026-09-18 16:29:13'),
(3, 'اللافندر', 'uploads/lavender.jpg', 'نبتة مريحة برائحتها الهادئة المميزة، تساعد على الاسترخاء والنوم.', 'جذب الفراشات، طرد الحشرات، رائحة مهدئة', 'الري: قليل جداً بعد الجفاف\nالإضاءة: شمس مباشرة\nالتسميد: نادراً ما تحتاج', 35.00, 0, 2, 1, 15, 'available', 'easy', 'outdoor', 'high', 'low', '2026-09-18 16:29:13'),
(4, 'البوتس', 'uploads/pothos.jpg', 'نبتة تضفي الحياة للمكان بأوراقها اللامعة، تتحمل مختلف الظروف.', 'تنقية الهواء، سهولة التكاثر، نبتة متسلقة', 'الري: عند جفاف التربة\nالإضاءة: إضاءة متوسطة إلى ضعيفة\nالتسميد: مرة كل أسبوعين', 25.00, 0, 1, 1, 20, 'available', 'easy', 'indoor', 'low', 'low', '2026-09-18 16:29:13'),
(5, 'الصبار', 'uploads/cactus.jpg', 'نبتة قوية تتحمل الظروف القاسية، مثالية لمن ينسى الري.', 'تحمل العطش، حماية من الأشعة، عمر طويل', 'الري: مرة كل أسبوعين إلى شهر\nالإضاءة: شمس مباشرة أو إضاءة قوية\nالتسميد: في الربيع فقط', 20.00, 0, 2, 1, 25, 'available', 'easy', 'outdoor', 'high', 'low', '2026-09-18 16:29:13'),
(6, 'النعناع', 'uploads/mint.jpg', 'نبتة منعشة سريعة النمو، رائحتها تملأ المكان وتستخدم في المشروبات.', 'استخدامات طبية وغذائية، انتشار سريع', 'الري: يومي أو عند جفاف السطح\nالإضاءة: شمس جزئية أو كاملة\nالتسميد: سماد عضوي بسيط', 15.00, 0, 2, 1, 30, 'available', 'medium', 'outdoor', 'medium', 'high', '2026-09-18 16:29:13'),
(7, 'الفيكس', 'uploads/ficus.jpg', 'شجيرة داخلية أنيقة تمتاز بأوراقها الخضراء الداكنة الجذابة.', 'مظهر فخم، تنقية الهواء، تناسب المكتب', 'الري: معتدل\nالإضاءة: سطوع بدون شمس مباشرة\nالتسميد: شهرياً خلال الصيف', 50.00, 10, 1, 1, 12, 'available', 'medium', 'indoor', 'medium', 'medium', '2026-09-18 16:29:13'),
(8, 'المونستيرا', 'uploads/monstera.jpg', 'نبات القفص الصدري المميز بأوراقه المخرمة الكبيرة، يضيف لمسة استوائية.', 'أوراق عريضة جذابة، إعطاء طابع عصري', 'الري: ري منتظم عند جفاف التربة\nالإضاءة: إضاءة ساطعة غير مباشرة\nالتسميد: سماد سائل شهرياً', 75.00, 0, 1, 1, 7, 'available', 'medium', 'indoor', 'medium', 'high', '2026-09-18 16:29:13');

-- --------------------------------------------------------

--
-- بنية الجدول `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `status` varchar(20) DEFAULT 'pending',
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `user`
--

INSERT INTO `user` (`id`, `firstName`, `lastName`, `email`, `password`, `address`, `mobile`, `is_admin`, `creation_date`) VALUES
(1, 'أدمن', 'رواء', 'admin@rawa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'الطائف', '0500000000', 1, '2026-05-29 05:59:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_offer_user` (`user_id`),
  ADD KEY `fk_offer_category` (`cat_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_reservation` (`user_id`),
  ADD KEY `fk_reservation_offer` (`offer_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `fk_offer_category` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `fk_offer_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- قيود الجداول `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_offer` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`),
  ADD CONSTRAINT `fk_reservation_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
