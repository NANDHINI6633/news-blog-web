-- Create Database
CREATE DATABASE IF NOT EXISTS news_blog;
USE news_blog;

-- =========================
-- ADMIN TABLE
-- =========================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL
);

-- Default Admin Login
INSERT INTO admins (username, password) 
VALUES ('admin', 'admin123');


-- =========================
-- ARTICLES TABLE
-- =========================
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    author VARCHAR(100) NOT NULL,
    excerpt TEXT,
    content LONGTEXT,
    image_url TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =========================
-- SAMPLE ARTICLES (FOR DEMO)
-- =========================
INSERT INTO articles (title, category, author, excerpt, content, image_url) VALUES

('Global Climate Agreement Reached',
'World',
'Eleanor Hayes',
'World leaders sign a historic climate agreement.',
'In a landmark moment, leaders from over 100 countries signed a new agreement to combat climate change...',
'https://images.unsplash.com/photo-1469474968028-56623f02e42e'),

('AI Revolution in 2026',
'Technology',
'Marcus Chen',
'Artificial Intelligence is transforming industries worldwide.',
'AI is now being used in healthcare, finance, and education, revolutionizing how humans interact with technology...',
'https://images.unsplash.com/photo-1516321318423-f06f85e504b3'),

('Economy Shows Signs of Recovery',
'Economy',
'Sofia Valentina',
'Economic indicators show improvement after slowdown.',
'Experts report that inflation is stabilizing and job markets are improving after a challenging period...',
'https://images.unsplash.com/photo-1560518883-ce09059eeffa');


-- =========================
-- OPTIONAL: AUTHORS TABLE
-- =========================
CREATE TABLE authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    role VARCHAR(100),
    bio TEXT
);

INSERT INTO authors (name, role, bio) VALUES
('Eleanor Hayes', 'Senior World Correspondent', 'Covers global politics and climate issues.'),
('Marcus Chen', 'Technology Journalist', 'Writes about AI and emerging tech.'),
('Sofia Valentina', 'Economic Analyst', 'Focuses on global markets and finance.');