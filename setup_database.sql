-- Create database if not exists
CREATE DATABASE IF NOT EXISTS salon_raya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE salon_raya;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    service VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    comment TEXT,
    duration_minutes INT DEFAULT 60,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    cancellation_token VARCHAR(64),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create push_subscriptions table for web push notifications
CREATE TABLE IF NOT EXISTS push_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endpoint VARCHAR(255) NOT NULL UNIQUE,
    p256dh VARCHAR(255) NOT NULL,
    auth VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create reviews table for admin management
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int(11) DEFAULT 5 CHECK (`rating` >= 1 and `rating` <= 5),
  `client_initial` varchar(1) DEFAULT '',
  `background_color` varchar(20) DEFAULT '#a484e8',
  `google_link` varchar(255) DEFAULT '',
  `is_published` tinyint(1) DEFAULT 0,
  `is_on_main_page` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `published_at` timestamp NULL DEFAULT NULL,
  `added_to_main_page_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample reviews data
INSERT INTO `reviews` (`id`, `client_name`, `review_text`, `rating`, `client_initial`, `background_color`, `google_link`, `is_published`, `is_on_main_page`, `created_at`, `published_at`, `added_to_main_page_at`, `is_deleted`, `deleted_at`) VALUES
(2, 'Cvetelina Mihova', 'Това е единствения салон, който посещавам. Наистина професионално обслужване и това е единственото място, където знаят как се работи с къдрава коса. Само от този салон, като изляза не ме е срам от това колко бухнала ми е косата, а се чувствам все едно имам най-красивата коса на света. Диди е най прекрасната фрзьорка! Просто я обичам!', 5, 'C', '#f4511e', 'https://g.co/kgs/DtSU4et', 0, 1, '2025-06-07 08:25:50', NULL, '2025-06-07 08:25:50', 0, NULL),
(3, 'Alexander Penchovski', 'Страхотен професионалист и топла атмосфера!', 5, 'A', '#a484e8', 'https://g.co/kgs/87FvPtW', 0, 1, '2025-06-07 08:25:50', NULL, '2025-06-07 08:25:50', 0, NULL),
(5, 'Evelina Evtimova', 'Салон Райа е най - доброто място за разкрасяване и добро настроение. Диди е професионалист с петнадесет годишен опит в бранша. Тя е много търпелива с клиентите, винаги усмихната. Аз винаги излизам от там с настроение и със сияйна и блестяща коса! Сърдечно препоръчвам да посетите салона, защото ще останете изключително доволни от постигнатите резултати!', 5, 'E', '#689f38', 'https://g.co/kgs/x9ZqR4w', 0, 1, '2025-06-07 15:45:53', NULL, '2025-06-07 15:45:53', 0, NULL);

-- Configure reviews table
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

COMMIT; 