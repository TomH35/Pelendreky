-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1:3307
-- Čas generovania: Št 05.Sep 2024, 14:05
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
  `tags` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `articles`
--

INSERT INTO `articles` (`article_id`, `article_title`, `article_slug`, `article_text`, `user_id`, `category_id`, `published_at`, `created_at`, `updated_at`, `status`, `image_url`, `tags`) VALUES
(14, 'New Article', 'new-article', 'text', 1, 5, '2024-09-05 11:27:05', '2024-09-05 09:27:05', '2024-09-05 09:27:42', 'published', '/Backend/public/ArticleImages/66d9796931622-3_SkupinaRozvrh.PNG', ''),
(15, 'Another New Article', 'another-new-article', 'text', 1, 6, '2024-09-05 13:50:43', '2024-09-05 11:50:43', '2024-09-05 12:00:03', 'published', '/Backend/public/ArticleImages/66d99b13b614a-3_SkupinaRozvrh.PNG', ''),
(16, 'Second Article', 'second-article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sodales lectus odio, in sodales magna luctus at. Donec ac tincidunt lectus, eget ullamcorper urna. Quisque cursus bibendum tortor eget elementum. Vestibulum quis tempor ex, vitae finibus erat. Donec rutrum augue sit amet tellus sodales posuere. Maecenas suscipit nisl quam. Praesent eget erat auctor, ultricies justo vel, pulvinar diam. In aliquam elementum lectus non lobortis. Etiam semper diam sed volutpat feugiat. Cras justo nulla, fringilla a interdum a, porttitor facilisis mauris. ', 1, 5, '2024-09-05 14:02:32', '2024-09-05 12:02:32', '2024-09-05 12:02:32', 'published', '/Backend/public/ArticleImages/66d99dd866d9b-3_SkupinaRozvrh.PNG', ''),
(17, 'Article Title', 'article-title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sodales lectus odio, in sodales magna luctus at. Donec ac tincidunt lectus, eget ullamcorper urna. Quisque cursus bibendum tortor eget elementum. Vestibulum quis tempor ex, vitae finibus erat. Donec rutrum augue sit amet tellus sodales posuere. Maecenas suscipit nisl quam. Praesent eget erat auctor, ultricies justo vel, pulvinar diam. In aliquam elementum lectus non lobortis. Etiam semper diam sed volutpat feugiat. Cras justo nulla, fringilla a interdum a, porttitor facilisis mauris. ', 1, 6, '2024-09-05 14:03:31', '2024-09-05 12:03:31', '2024-09-05 12:03:31', 'published', '/Backend/public/ArticleImages/66d99e135320b-3_SkupinaRozvrh.PNG', ''),
(18, 'Another Title', 'another-title', 'text', 1, 5, '2024-09-05 14:04:31', '2024-09-05 12:04:31', '2024-09-05 12:04:31', 'published', '/Backend/public/ArticleImages/66d99e4f18058-3_SkupinaRozvrh.PNG', '');

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
(6, 'Politika', 'politika', '2024-09-04 11:57:49');

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
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pre tabuľku `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
