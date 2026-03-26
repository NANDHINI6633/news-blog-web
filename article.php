<?php
// article.php
require 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$post_id = $_GET['id'];

// Fetch the post data
$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name, c.id AS category_id, a.name AS author_name 
    FROM posts p 
    LEFT JOIN categories c ON p.category_id = c.id 
    LEFT JOIN authors a ON p.author_id = a.id
    WHERE p.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Article not found.");
}

$page_title = $post['title'];
require 'includes/header.php';
?>

<article class="article-content" style="max-width: 1000px;">
    <header class="article-header">
        <a href="category.php?id=<?= $post['category_id'] ?>" class="article-category">
            <?= htmlspecialchars($post['category_name'] ?? 'Uncategorized') ?>
        </a>
        <h1 class="article-title"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="article-meta">
            <span>By <?= htmlspecialchars($post['author_name'] ?? 'Unknown Author') ?></span>
            <span>&bull;</span>
            <span>Published on <?= date('F d, Y', strtotime($post['created_at'])) ?></span>
        </div>
    </header>

    <?php if ($post['image_url']): ?>
        <img src="<?= htmlspecialchars($post['image_url']) ?>" alt="Article Image" class="article-image">
    <?php endif; ?>

    <div class="article-content">
        <!-- Assuming content might have simple paragraphs or HTML -->
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>
</article>

<div style="text-align: center; margin-top: 4rem;">
    <a href="index.php" class="btn btn-secondary">Back to Home</a>
</div>

<?php require 'includes/footer.php'; ?>
