CREATE DATABASE IF NOT EXISTS glowin_db;
USE glowin_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL,
    profile_pic VARCHAR(255) DEFAULT 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80',
    header_pic VARCHAR(255) DEFAULT 'https://images.unsplash.com/photo-1505839673365-e3971f8d9184?auto=format&fit=crop&w=1400&q=80',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Komentar yang baru saja dibuat di phpMyAdmin
CREATE TABLE IF NOT EXISTS comments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    post_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert a dummy user
INSERT INTO users (username, password) VALUES ('user1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password is 'password'

-- Insert a dummy post
INSERT INTO posts (user_id, content) VALUES (1, 'Halo, ini post pertama saya di Glow-in menggunakan PHP dan MySQL!');
