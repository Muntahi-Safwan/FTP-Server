-- Create Database
CREATE DATABASE IF NOT EXISTS ftp_server;
USE ftp_server;

-- =========================
-- TABLE: users
-- =========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'moderator') NOT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLE: categories
-- =========================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_category_parent
        FOREIGN KEY (parent_id)
        REFERENCES categories(id)
        ON DELETE CASCADE
);

-- =========================
-- TABLE: contents
-- =========================
CREATE TABLE contents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    uploader_id INT NOT NULL,
    download_count INT DEFAULT 0,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_content_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_content_uploader
        FOREIGN KEY (uploader_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- =========================
-- TABLE: content_requests
-- =========================
CREATE TABLE content_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_ip VARCHAR(50),
    content_title VARCHAR(255) NOT NULL,
    category_requested VARCHAR(100),
    message TEXT,
    status ENUM('pending', 'fulfilled', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- SEED DATA
-- =====================================================

-- Password for all accounts: 12345678

INSERT INTO users
(name, email, password_hash, role, profile_picture)
VALUES
(
    'Main Admin',
    'admin@ftpmedia.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.ogB8kFQh6rQW7y6.',
    'admin',
    'uploads/profile/admin.png'
),
(
    'Moderator One',
    'mod1@ftpmedia.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.ogB8kFQh6rQW7y6.',
    'moderator',
    'uploads/profile/mod1.png'
),
(
    'Moderator Two',
    'mod2@ftpmedia.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.ogB8kFQh6rQW7y6.',
    'moderator',
    'uploads/profile/mod2.png'
);

-- =========================
-- MAIN CATEGORIES
-- =========================

INSERT INTO categories (id, name, parent_id)
VALUES
(1, 'Movies', NULL),
(2, 'Software', NULL),
(3, 'TV Series', NULL),
(4, 'Games', NULL);

-- =========================
-- SUB CATEGORIES
-- =========================

INSERT INTO categories (name, parent_id)
VALUES
('Action', 1),
('Comedy', 1),
('Hollywood', 1),
('Windows', 2),
('Linux', 2),
('Programming', 2),
('Anime', 3),
('Drama', 3),
('RPG', 4),
('FPS', 4);

-- =========================
-- CONTENTS
-- =========================

INSERT INTO contents
(title, description, file_path, category_id, uploader_id, download_count)
VALUES
(
    'Avengers Endgame',
    'Marvel action movie in HD quality.',
    'uploads/contents/avengers_endgame.mp4',
    5,
    1,
    150
),
(
    'Ubuntu ISO 24.04',
    'Ubuntu Linux operating system image.',
    'uploads/contents/ubuntu_24.iso',
    9,
    2,
    90
),
(
    'Visual Studio Code',
    'Lightweight source code editor.',
    'uploads/contents/vscode_setup.exe',
    10,
    1,
    250
),
(
    'Attack on Titan S1',
    'Popular anime TV series.',
    'uploads/contents/aot_s1.mp4',
    11,
    3,
    300
),
(
    'Valorant',
    'Competitive FPS multiplayer game.',
    'uploads/contents/valorant_setup.exe',
    14,
    2,
    180
),
(
    'The Office',
    'Comedy TV series collection.',
    'uploads/contents/the_office.zip',
    12,
    3,
    110
);

-- =========================
-- CONTENT REQUESTS
-- =========================

INSERT INTO content_requests
(requester_ip, content_title, category_requested, message, status)
VALUES
(
    '192.168.1.10',
    'GTA VI',
    'Games',
    'Please upload the latest GTA game.',
    'pending'
),
(
    '192.168.1.15',
    'Adobe Photoshop',
    'Software',
    'Need the newest Photoshop version.',
    'fulfilled'
),
(
    '192.168.1.20',
    'Interstellar',
    'Movies',
    'Looking for 4K version.',
    'rejected'
);
