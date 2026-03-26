<?php
// admin/edit_post.php
session_start();
require '../includes/db.php';

// Check authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$post_id = $_GET['id'];

$error = '';

// Fetch current post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}

// Fetch categories and authors for form dropdowns
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$authors = $pdo->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $excerpt = trim($_POST['excerpt']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'] ?: null;
    $author_id = $_POST['author_id'] ?: null;
    $image_url = trim($_POST['image_url']);

    if (!$title || !$content) {
        $error = 'Title and Content are required.';
    } else {
        $update_stmt = $pdo->prepare("UPDATE posts SET title = ?, excerpt = ?, content = ?, category_id = ?, author_id = ?, image_url = ? WHERE id = ?");
        if ($update_stmt->execute([$title, $excerpt, $content, $category_id, $author_id, $image_url, $post_id])) {
            header("Location: index.php");
            exit;
        } else {
            $error = 'Failed to update post.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="brand"><h2>News Today</h2></div>
            <nav>
                <a href="index.php">Dashboard</a>
                <a href="add_post.php">Add New Post</a>
                <a href="../index.php" target="_blank">View Site</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>
        <main class="admin-main">
            <header class="admin-header">
                <h1>Edit Post</h1>
            </header>
            
            <div class="admin-form-container">
                <?php if($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $post['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="author_id">Author</label>
                        <select id="author_id" name="author_id">
                            <option value="">Select Author</option>
                            <?php foreach($authors as $author): ?>
                                <option value="<?= $author['id'] ?>" <?= $post['author_id'] == $author['id'] ? 'selected' : '' ?>><?= htmlspecialchars($author['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image_url">Image URL</label>
                        <input type="text" id="image_url" name="image_url" value="<?= htmlspecialchars($post['image_url'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="excerpt">Excerpt</label>
                        <textarea id="excerpt" name="excerpt" rows="3"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="content">Full Content *</label>
                        <textarea id="content" name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
