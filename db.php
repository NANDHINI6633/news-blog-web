<?php
// includes/db.php
$db_file = __DIR__ . '/../blog.sqlite';
try {
    $pdo = new PDO("sqlite:" . $db_file);
    // Set error mode to exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Enable foreign keys
    $pdo->exec("PRAGMA foreign_keys = ON;");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
