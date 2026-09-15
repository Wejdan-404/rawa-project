-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2026 at 01:59 AM
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
-- Database: `rawa_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `creation_date`) VALUES
(1, 'نباتات مزهرة', '2026-05-29 05:59:24'),
(2, 'نباتات منزلية', '2026-05-29 05:59:24'),
(3, 'نباتات مميزة', '2026-05-29 05:59:24'),
(4, 'نباتات داخلية', '2026-05-29 05:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `offer`
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
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`id`, `title`, `image`, `description`, `features`, `care_catalog`, `org_price`, `discount`, `cat_id`, `user_id`, `quantity`, `status`, `care_level`, `creation_date`) VALUES
(1, 'الياسمين', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQoeoYmw_oSLPt9L2rHqD5gFiVQwUKEguHJG3R86SqB0-IszqTiTK0KGr0&s=10', 'نبتة عطرية تنشر الجمال والرائحة الجميلة، سهلة العناية ومناسبة للمبتدئين.', 'رائحة عطرة جميلة|تزهر في الربيع والصيف|مناسبة للشرفات والحدائق|تجذب الفراشات والنحل', 'الري: مرتين أسبوعياً|الضوء: ضوء شمس مباشر أو غير مباشر|التربة: خفيفة وجيدة الصرف|التسميد: شهرياً في موسم النمو|التقليم: بعد انتهاء موسم الإزهار', 0.00, 0, 1, 1, 8, 'available', 'easy', '2026-05-29 05:59:24'),
(2, 'التوليب', 'https://img.youm7.com/ArticleImgs/2019/9/10/113990--%D8%A7%D9%84%D8%AA%D9%88%D9%84%D9%8A%D8%A8--(1).jpg', 'زهرة رقيقة تعشق الأجواء الهادئة، تضيف لمسة رومانسية للمكان.', 'ألوان زاهية ومتعددة|تزهر في الربيع|مثالية للهدايا|رمز الحب والجمال', 'الري: مرة أسبوعياً بانتظام|الضوء: ضوء غير مباشر|التربة: غنية بالمواد العضوية|التسميد: في بداية موسم النمو|الحرارة: تفضل الأجواء المعتدلة', 0.00, 0, 1, 1, 5, 'available', 'medium', '2026-05-29 05:59:24'),
(3, 'اللافندر', 'https://landgreen.com.eg/wp-content/uploads/2024/10/%D8%A8%D8%B0%D9%88%D8%B1-%D9%84%D8%A7%D9%81%D9%86%D8%AF%D8%B1-300x300.jpeg', 'نبتة مريحة برائحتها الهادئة المميزة، تساعد على الاسترخاء والنوم.', 'رائحة هادئة ومريحة|تساعد على النوم|طاردة للحشرات الطبيعية|جميلة لتزيين المنزل', 'الري: قليل جداً — مرة كل 10 أيام|الضوء: شمس مباشرة لساعات طويلة|التربة: جيدة الصرف وقليلة الرطوبة|التسميد: مرة كل شهرين|التقليم: في الخريف لتشجيع النمو', 0.00, 0, 1, 1, 3, 'available', 'medium', '2026-05-29 05:59:24'),
(4, 'البوتس', 'https://sultangardencenter.com/image/cache/catalog/product/5499/BND-SC808-13MS-(3)-5499-thumb-800x533.jpg', 'نبتة تضيف الحياة للمكان بأوراقها اللامعة، تتحمل الإهمال وقليل من الضوء.', 'تتحمل قلة الضوء|سهلة العناية جداً|تنقي الهواء من الملوثات|سريعة النمو', 'الري: مرة كل أسبوع أو أسبوعين|الضوء: ضوء خافت أو غير مباشر|التربة: أي تربة جيدة الصرف|التسميد: كل شهرين|ملاحظة: سامة للقطط والكلاب', 0.00, 0, 2, 1, 0, 'adopted', 'easy', '2026-05-29 05:59:24'),
(5, 'الصبار', 'https://blog.nv.sa/sites/default/files/styles/xlarge/public/field/image/%D8%B5%D8%A8%D8%A7%D8%B11.jpg?itok=j5qCJLPJ&c=4e7a61ae3e058f7300f4b1fc0ecb8942', 'نبتة قوية تتحمل الظروف القاسية، مثالية لمن ينسى الري.', 'لا تحتاج عناية مستمرة|تتحمل الجفاف التام|مناسبة للمكاتب والمنازل|أشكال وأحجام متعددة', 'الري: مرة كل 3 أسابيع صيفاً، شهرياً شتاءً|الضوء: ضوء شمس مباشر|التربة: رملية جيدة الصرف|التسميد: مرة في الربيع فقط|تحذير: احذر الأشواك عند التعامل معه', 0.00, 0, 2, 1, 15, 'available', 'easy', '2026-05-29 05:59:24'),
(6, 'النعناع', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQI-fEHbguXchO9GQo9llXpS66TChmMf0MY9G-RHTSNC920hh80dNQyh_Y&s=10', 'نبتة منعشة سريعة النمو، رائحتها تملأ المكان بالحيوية.', 'رائحة منعشة ومميزة|صالحة للأكل والشرب|سريعة الانتشار والنمو|طاردة للحشرات', 'الري: يومياً أو كل يومين|الضوء: ضوء شمس جزئي|التربة: غنية ورطبة|التسميد: شهرياً|نصيحة: ازرعها في وعاء منفصل لأنها تنتشر بسرعة', 0.00, 0, 2, 1, 20, 'available', 'easy', '2026-05-29 05:59:24'),
(7, 'الفيكس', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQPymtUkl4_Qq6sWRYehIuKT-dRayVibHU_cLucPLrUNtQdznDtTdj-e57k&s=10', 'نبتة أنيقة بأوراق كبيرة لامعة، تمنح المكان طابعاً راقياً.', 'أوراق كبيرة لامعة وجميلة|تنقي الهواء بشكل ممتاز|مناسبة للمكاتب والصالات|تعطي إحساساً بالرقي', 'الري: مرة أسبوعياً|الضوء: ضوء ساطع غير مباشر|التربة: خفيفة وجيدة التصريف|التسميد: كل شهر في موسم النمو|ملاحظة: حساسة للمسودات والتغيرات المفاجئة', 0.00, 0, 3, 1, 4, 'available', 'medium', '2026-05-29 05:59:24'),
(8, 'المونستيرا', 'https://albustan.com.sa/wp-content/uploads/2025/05/IDR-000280-jpg.webp', 'نبتة مميزة بأوراقها الفريدة العصرية، الأكثر شيوعاً في ديكورات المنازل.', 'أوراق فريدة وعصرية الشكل|تنمو بسرعة ملحوظة|تناسب التصميم الداخلي الحديث|تنقي الهواء وترفع الرطوبة', 'الري: مرة أسبوعياً في الصيف، كل أسبوعين في الشتاء|الضوء: ضوء ساطع غير مباشر|التربة: غنية بالمواد العضوية|التسميد: شهرياً من الربيع للخريف|نصيحة: امسح الأوراق بقطعة رطبة للحفاظ على لمعانها', 0.00, 0, 3, 1, 6, 'available', 'medium', '2026-05-29 05:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
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
-- Table structure for table `user`
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
-- Dumping data for table `user`
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
-- Constraints for dumped tables
--

--
-- Constraints for table `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `fk_offer_category` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `fk_offer_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_offer` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`),
  ADD CONSTRAINT `fk_reservation_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
