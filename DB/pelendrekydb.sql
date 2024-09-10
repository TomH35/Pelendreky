-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1:3307
-- Čas generovania: Út 10.Sep 2024, 15:51
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `pelendrekydb`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `ads`
--

CREATE TABLE `ads` (
  `ad_id` int(11) NOT NULL,
  `ad_title` varchar(255) NOT NULL,
  `ad_image_url` varchar(255) DEFAULT NULL,
  `ad_link_url` varchar(255) NOT NULL,
  `ad_html` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `impressions` int(11) DEFAULT 0,
  `clicks` int(11) DEFAULT 0,
  `status` enum('active','inactive','expired') DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL,
  `article_title` varchar(255) NOT NULL,
  `article_slug` varchar(255) NOT NULL,
  `article_text` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `image_url` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `article_view_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `articles`
--

INSERT INTO `articles` (`article_id`, `article_title`, `article_slug`, `article_text`, `user_id`, `category_id`, `published_at`, `created_at`, `updated_at`, `status`, `image_url`, `tags`, `article_view_count`) VALUES
(14, 'New Article', 'new-article', 'text', 1, 5, '2024-09-05 11:27:05', '2024-09-05 09:27:05', '2024-09-10 09:07:12', 'published', '/Backend/public/ArticleImages/66d9796931622-3_SkupinaRozvrh.PNG', '', 1),
(15, 'Another New Article', 'another-new-article', 'text', 1, 6, '2024-09-05 13:50:43', '2024-09-05 11:50:43', '2024-09-05 12:00:03', 'published', '/Backend/public/ArticleImages/66d99b13b614a-3_SkupinaRozvrh.PNG', '', 0),
(16, 'Second Article', 'second-article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sodales lectus odio, in sodales magna luctus at. Donec ac tincidunt lectus, eget ullamcorper urna. Quisque cursus bibendum tortor eget elementum. Vestibulum quis tempor ex, vitae finibus erat. Donec rutrum augue sit amet tellus sodales posuere. Maecenas suscipit nisl quam. Praesent eget erat auctor, ultricies justo vel, pulvinar diam. In aliquam elementum lectus non lobortis. Etiam semper diam sed volutpat feugiat. Cras justo nulla, fringilla a interdum a, porttitor facilisis mauris. ', 1, 5, '2024-09-05 14:02:32', '2024-09-05 12:02:32', '2024-09-05 12:02:32', 'published', '/Backend/public/ArticleImages/66d99dd866d9b-3_SkupinaRozvrh.PNG', '', 0),
(17, 'Article Title', 'article-title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sodales lectus odio, in sodales magna luctus at. Donec ac tincidunt lectus, eget ullamcorper urna. Quisque cursus bibendum tortor eget elementum. Vestibulum quis tempor ex, vitae finibus erat. Donec rutrum augue sit amet tellus sodales posuere. Maecenas suscipit nisl quam. Praesent eget erat auctor, ultricies justo vel, pulvinar diam. In aliquam elementum lectus non lobortis. Etiam semper diam sed volutpat feugiat. Cras justo nulla, fringilla a interdum a, porttitor facilisis mauris. ', 1, 6, '2024-09-05 14:03:31', '2024-09-05 12:03:31', '2024-09-05 12:03:31', 'published', '/Backend/public/ArticleImages/66d99e135320b-3_SkupinaRozvrh.PNG', '', 0),
(18, 'Another Title', 'another-title', 'text', 1, 5, '2024-09-05 14:04:31', '2024-09-05 12:04:31', '2024-09-10 09:07:15', 'published', '/Backend/public/ArticleImages/66d99e4f18058-3_SkupinaRozvrh.PNG', '', 2),
(19, 'Sport Article', 'sport-article', 'text', 1, 7, '2024-09-08 15:16:19', '2024-09-08 13:16:19', '2024-09-08 13:16:19', 'published', '/Backend/public/ArticleImages/66dda3a3c67c6-3_SkupinaRozvrh.PNG', '', 0),
(20, 'Another Sport Article', 'another-sport-article', 'text', 1, 7, '2024-09-08 15:16:48', '2024-09-08 13:16:48', '2024-09-10 08:56:24', 'published', '/Backend/public/ArticleImages/66dda3c023712-3_SkupinaRozvrh.PNG', '', 1),
(21, 'Zábava Article', 'zabava-article', 'text', 1, 14, '2024-09-08 15:23:01', '2024-09-08 13:23:01', '2024-09-10 09:08:23', 'published', '/Backend/public/ArticleImages/66dda5350e61c-3_SkupinaRozvrh.PNG', '', 1),
(22, 'Article title one', 'article-title-one', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla volutpat dolor massa, a dignissim metus blandit nec. Vestibulum erat velit, pharetra vel convallis eu, tincidunt non libero. Suspendisse potenti. Curabitur ut diam sodales, sodales arcu eu, dictum nisi. In finibus ligula turpis, quis ullamcorper orci consectetur a. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis erat diam, congue vel purus ac, pharetra rutrum tellus. Praesent pretium sed urna vel sagittis. In vestibulum, dolor non faucibus porttitor, nulla risus volutpat lorem, sit amet porttitor urna elit et ex. Vestibulum tristique sit amet nulla nec imperdiet. In at consequat felis. In hendrerit urna non neque vehicula, eu eleifend libero efficitur. In hac habitasse platea dictumst. </p><p><br></p><h2><strong>DJSOijdjsoajd</strong></h2><h2><br></h2><p>Cras vitae quam quis purus condimentum dictum. Vivamus feugiat, ligula feugiat efficitur rutrum, odio nunc eleifend felis, sit amet consectetur enim odio at ligula. Donec sollicitudin eleifend consectetur. Nulla mattis massa ac leo venenatis, porttitor fermentum lectus interdum. Curabitur lobortis libero at felis luctus elementum. Pellentesque interdum feugiat quam quis dignissim. Nunc eu urna arcu. Sed sit amet pharetra sem. Suspendisse fringilla, quam vitae sollicitudin laoreet, quam ligula ornare est, quis condimentum dui leo eget elit. Vestibulum vitae suscipit turpis. Nunc tempus nisl vel tortor efficitur, non imperdiet tellus ullamcorper. Pellentesque diam sem, consequat sit amet turpis in, hendrerit sagittis metus. Praesent ac commodo nulla, sed consectetur magna. Proin nec tortor nec nisi interdum condimentum et vel nulla. Aliquam imperdiet lectus in metus varius, sed porta orci porttitor. </p>', 1, 5, '2024-09-10 15:40:05', '2024-09-10 13:40:05', '2024-09-10 13:45:13', 'published', '/Backend/public/ArticleImages/66e04c35ace49-3_SkupinaRozvrh.PNG', '', 4);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_slug`, `created_at`) VALUES
(5, 'Technológia', 'technologia', '2024-08-17 15:00:55'),
(6, 'Politika', 'politika', '2024-09-04 11:57:49'),
(7, 'Šport', 'sport', '2024-09-08 12:53:22'),
(14, 'Zábava', 'zabava', '2024-09-08 13:22:34');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subscription_type` enum('basic','premium','vip') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive','canceled') DEFAULT 'active',
  `auto_renew` tinyint(1) DEFAULT 1,
  `payment_method` enum('credit_card','paypal','bank_transfer','crypto') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`user_id`, `name`, `surname`, `password`, `email`, `created_at`, `user_is_admin`) VALUES
(1, 'T', 'T', '$2y$10$AHLwFvQ8iaVKhluzk6AE4.KXTeewFD4utHNz83VIFj0yycfJq1FEW', 'email@gmail.com', '2024-08-13 11:43:15', 1),
(2, 'T', 'T', '$2y$10$fKZvtq7V7Fs/e0I5UL2rKukDIM8w36LW1ZY/pDTM/4/Qh.zjFSVzK', 'email3@gmail.com', '2024-08-13 11:52:15', 1);

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexy pre tabuľku `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`article_id`),
  ADD UNIQUE KEY `article_slug` (`article_slug`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexy pre tabuľku `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_slug` (`category_slug`);

--
-- Indexy pre tabuľku `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `fk_user_subscription` (`user_id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `ads`
--
ALTER TABLE `ads`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `articles`
--
ALTER TABLE `articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pre tabuľku `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pre tabuľku `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Obmedzenie pre tabuľku `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `fk_user_subscription` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
