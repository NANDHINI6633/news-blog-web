<?php
// setup_db.php
require 'includes/db.php';

try {
    // Drop existing tables for a clean slate
    $pdo->exec("DROP TABLE IF EXISTS posts");
    $pdo->exec("DROP TABLE IF EXISTS categories");
    $pdo->exec("DROP TABLE IF EXISTS authors");
    $pdo->exec("DROP TABLE IF EXISTS users");

    // Create users table
    $pdo->exec("
        CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL
        )
    ");
    
    // Create categories table
    $pdo->exec("
        CREATE TABLE categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL
        )
    ");

    // Create authors table
    $pdo->exec("
        CREATE TABLE authors (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL,
            bio TEXT,
            image_url TEXT
        )
    ");

    // Create posts table
    $pdo->exec("
        CREATE TABLE posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            excerpt TEXT,
            content TEXT NOT NULL,
            category_id INTEGER,
            author_id INTEGER,
            image_url TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
            FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE SET NULL
        )
    ");

    // Insert Default Admin User (username: admin, password: password123)
    $password_hash = password_hash("password123", PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute(['admin', $password_hash]);

    // Insert Sample Categories
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $categories = ['Technology', 'Business', 'Lifestyle', 'Entertainment'];
    foreach ($categories as $cat) {
        $stmt->execute([$cat]);
    }

    // Insert Sample Authors
    $stmt = $pdo->prepare("INSERT INTO authors (name, bio, image_url) VALUES (?, ?, ?)");
    $stmt->execute(['Jane Doe', 'Senior Editor and Tech Enthusiast.', 'https://i.pravatar.cc/150?img=1']);
    $stmt->execute(['John Smith', 'Business Analyst and Blogger.', 'https://i.pravatar.cc/150?img=2']);

    // Insert Sample Posts
    $stmt = $pdo->prepare("INSERT INTO posts (title, excerpt, content, category_id, author_id, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        'The Future of AI Technology',
        'Artificial Intelligence is rapidly evolving. What does the future hold for developers and businesses?',
        'Full content for the AI Technology article goes here. It contains an in-depth analysis of AI trends and its implication on various industries...',
        1, // Technology
        1, // Jane Doe
        'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&q=80&w=800'
    ]);

    $stmt->execute([
        'Top 10 Business Strategies for 2026',
        'Explore the leading business strategies experts say will dominate the market in 2026.',
        'Full content for the business strategies article goes here. Detailed steps and case studies provided...',
        2, // Business
        2, // John Smith
        'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800'
    ]);

    echo "Database setup complete with sample data! Default Admin -> Username: admin | Password: password123";

} catch (PDOException $e) {
    echo "Error setting up database: " . $e->getMessage();
}
?>
