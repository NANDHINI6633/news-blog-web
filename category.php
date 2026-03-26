<?php
// category.php
require 'includes/db.php';

$current_category_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

// Fetch all categories for the filter
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Base query for posts
$query = "
    SELECT p.id, p.title, p.excerpt, p.image_url, p.created_at, c.name AS category_name, a.name AS author_name 
    FROM posts p 
    LEFT JOIN categories c ON p.category_id = c.id 
    LEFT JOIN authors a ON p.author_id = a.id
";

$params = [];

if ($current_category_id) {
    $query .= " WHERE p.category_id = ?";
    $params[] = $current_category_id;
    
    // Find category name for title
    $stmt_cat = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt_cat->execute([$current_category_id]);
    $catTitle = $stmt_cat->fetchColumn();
    $page_title = $catTitle ? $catTitle . ' Category' : 'Category';
} else {
    $page_title = 'Categories';
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'includes/header.php';
?>

<div class="page-header">
    <h1>Explore by Category</h1>
    <p>Find articles on the topics you care about most.</p>
</div>

<!-- Category Filters -->
<div class="category-filter">
    <a href="category.php" class="filter-badge <?= !$current_category_id ? 'active' : '' ?>">All Topics</a>
    <?php foreach($categories as $cat): ?>
        <a href="category.php?id=<?= $cat['id'] ?>" class="filter-badge <?= $current_category_id == $cat['id'] ? 'active' : '' ?>">
            <?= htmlspecialchars($cat['name']) ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="post-grid">
    <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>
            <article class="card">
                <?php if ($post['image_url']): ?>
                    <img src="<?= htmlspecialchars($post['image_url']) ?>" alt="Article Image" class="card-img">
                <?php else: ?>
                    <div class="card-img" style="background-color: #e2e8f0; display:flex; align-items:center; justify-content:center; color:#94a3b8;">No Image</div>
                <?php endif; ?>
                <div class="card-content">
                    <div class="card-category"><?= htmlspecialchars($post['category_name'] ?? 'Uncategorized') ?></div>
                    <h2 class="card-title">
                        <a href="article.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                    </h2>
                    <p class="card-excerpt">
                        <?= htmlspecialchars($post['excerpt'] ?: substr($post['content'], 0, 100) . '...') ?>
                    </p>
                    <div class="card-footer">
                        <span>By <?= htmlspecialchars($post['author_name'] ?? 'Unknown Author') ?></span>
                        <span><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; grid-column: 1 / -1; font-size: 1.2rem; color: var(--text-muted);">No articles found in this category.</p>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>
