CREATE DATABASE IF NOT EXISTS pixelswap;
USE pixelswap;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT 'pixel-avatar.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    developer VARCHAR(120) NOT NULL,
    genre VARCHAR(80) NOT NULL,
    description TEXT NOT NULL,
    release_date DATE NOT NULL,
    cover_image VARCHAR(255) DEFAULT 'cover-default.png',
    banner_image VARCHAR(255) DEFAULT 'banner-default.png',
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_games_genre (genre),
    INDEX idx_games_title (title),
    INDEX idx_games_price (price)
);

CREATE TABLE game_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    owner_user_id INT NOT NULL,
    key_code VARCHAR(80) NOT NULL UNIQUE,
    status ENUM('available','sold','traded') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    FOREIGN KEY (owner_user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_keys_owner (owner_user_id),
    INDEX idx_keys_game (game_id)
);

CREATE TABLE listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    game_id INT NOT NULL,
    listing_type ENUM('sale','trade') NOT NULL DEFAULT 'sale',
    key_id INT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    accepts_trade TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('active','sold','removed') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    FOREIGN KEY (key_id) REFERENCES game_keys(id) ON DELETE SET NULL,
    INDEX idx_listings_seller (seller_id),
    INDEX idx_listings_game (game_id),
    INDEX idx_listings_status (status)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    listing_id INT NOT NULL,
    key_id INT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('completed','cancelled') NOT NULL DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (key_id) REFERENCES game_keys(id) ON DELETE SET NULL,
    INDEX idx_orders_buyer (buyer_id)
);

CREATE TABLE trades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_user_id INT NOT NULL,
    receiver_user_id INT NOT NULL,
    offered_key_id INT NOT NULL,
    requested_listing_id INT NOT NULL,
    message TEXT,
    status ENUM('pending','accepted','rejected','cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (sender_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (offered_key_id) REFERENCES game_keys(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    INDEX idx_trades_sender (sender_user_id),
    INDEX idx_trades_receiver (receiver_user_id),
    INDEX idx_trades_status (status)
);

CREATE TABLE wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_wishlist_user_game (user_id, game_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
);
