-- Create reviews table for admin management
USE salon_raya;

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    review_text TEXT NOT NULL,
    rating INT DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
    client_initial VARCHAR(1) DEFAULT '',
    background_color VARCHAR(20) DEFAULT '#a484e8',
    google_link VARCHAR(255) DEFAULT '',
    is_published BOOLEAN DEFAULT FALSE,
    is_on_main_page BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    added_to_main_page_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 