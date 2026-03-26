<?php
// admin/index.php
session_start();
require '../includes/db.php';

// Check authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// Fetch posts
$stmt = $pdo->query("
    SELECT p.id, p.title, p.created_at, c.name AS category_name, a.name AS author_name 
    FROM posts p 
    LEFT JOIN categories c ON p.category_id = c.id 
    LEFT JOIN authors a ON p.author_id = a.id
    ORDER BY p.created_at DESC
");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="brand">
                <h2>News Today</h2>
            </div>
            <nav>
                <a href="index.php" class="active">Dashboard</a>
                <a href="add_post.php">Add New Post</a>
                <a href="../index.php" target="_blank">View Site</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>
        <main class="admin-main">
            <header class="admin-header">
                <h1>Manage Posts</h1>
                <a href="add_post.php" class="btn btn-primary">Create Post</a>
            </header>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($posts) > 0): ?>
                        <?php foreach($posts as $post): ?>
                        <tr>
                            <td><?= $post['id'] ?></td>
                            <td><?= htmlspecialchars($post['title']) ?></td>
                            <td><?= htmlspecialchars($post['category_name'] ?? 'Uncategorized') ?></td>
                            <td><?= htmlspecialchars($post['author_name'] ?? 'Unknown') ?></td>
                            <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                            <td>
                                <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No posts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
