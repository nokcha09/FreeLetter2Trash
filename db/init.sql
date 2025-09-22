USE newsletter_db;

-- --------------------------------------------------------
-- subscribers table: Manages newsletter subscriber information
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'Subscriber’s email address',
    status ENUM('pending', 'verified') NOT NULL DEFAULT 'pending' COMMENT 'Subscription status (pending or verified)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of subscription request',
    verified_at TIMESTAMP NULL COMMENT 'Timestamp when email was verified'
);

-- --------------------------------------------------------
-- users table: Manages user login and profile information
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'User’s unique email address',
    password VARCHAR(255) NOT NULL COMMENT 'Hashed password for user authentication',
    nickname VARCHAR(255) NULL COMMENT 'User’s chosen nickname for personalization',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of user registration',
    status ENUM('pending', 'verified') NOT NULL DEFAULT 'pending' COMMENT 'User account status (pending or verified)',
    token VARCHAR(255) NULL COMMENT 'Verification token for email confirmation'
);