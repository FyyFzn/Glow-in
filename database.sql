CREATE DATABASE IF NOT EXISTS glowin_db;
USE glowin_db;

-- 1. Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL,
    profile_pic VARCHAR(255) DEFAULT 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80',
    header_pic VARCHAR(255) DEFAULT 'https://images.unsplash.com/photo-1505839673365-e3971f8d9184?auto=format&fit=crop&w=1400&q=80',
    role ENUM('postinger', 'spesialis', 'admin') DEFAULT 'postinger',
    specialty_id INT DEFAULT NULL,
    points INT DEFAULT 0,
    api_key VARCHAR(255) DEFAULT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Posts
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT DEFAULT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- 3. Comments
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Specialties
CREATE TABLE IF NOT EXISTS specialties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE users ADD CONSTRAINT fk_user_specialty FOREIGN KEY (specialty_id) REFERENCES specialties(id) ON DELETE SET NULL;

-- 6. Schedules (Jadwal Spesialis)
CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    spesialis_id INT NOT NULL,
    available_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('tersedia', 'dibooking') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (spesialis_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 7. Bookings
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    postinger_id INT NOT NULL,
    schedule_id INT NOT NULL,
    status ENUM('menunggu', 'disetujui', 'selesai', 'dibatalkan') DEFAULT 'menunggu',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (postinger_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE
);

-- 8. Reports
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    reported_type ENUM('user', 'post', 'comment') NOT NULL,
    reported_id INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('menunggu', 'diproses', 'selesai') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 9. Messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 10. Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    actor_id INT NOT NULL,
    type ENUM('like', 'comment', 'follow', 'system') NOT NULL,
    reference_id INT DEFAULT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Contoh Data Users
INSERT INTO users (username, password, name, bio, location, role, points, api_key) VALUES
('johndoe', '$2y$10$YourHashedPasswordHere1', 'John Doe', 'Hello, I am John!', 'Jakarta', 'postinger', 1500, 'api_johndoe123'),
('janedoe', '$2y$10$YourHashedPasswordHere2', 'Jane Doe', 'Love to code!', 'Bandung', 'postinger', 2000, 'api_janedoe456'),
('alexsmith', '$2y$10$YourHashedPasswordHere3', 'Alex Smith', 'Designer & Developer', 'Surabaya', 'spesialis', 1800, 'api_alexsmith789'),
('sarahwilson', '$2y$10$YourHashedPasswordHere4', 'Sarah Wilson', 'Content Creator', 'Yogyakarta', 'postinger', 2200, 'api_sarahwilson012'),
('mikejohnson', '$2y$10$YourHashedPasswordHere5', 'Mike Johnson', 'Tech Enthusiast', 'Medan', 'postinger', 1600, 'api_mikejohnson345'),
('emilydavis', '$2y$10$YourHashedPasswordHere6', 'Emily Davis', 'Photographer', 'Semarang', 'spesialis', 1900, 'api_emilydavis678'),
('admin', '$2y$10$YourHashedPasswordHere7', 'Admin', 'Administrator', 'Jakarta', 'admin', 0, 'api_admin901');

-- 11. Likes
CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    UNIQUE(user_id, post_id)
);

-- 12. Follows
CREATE TABLE IF NOT EXISTS follows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT NOT NULL,
    following_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE(follower_id, following_id)
);
