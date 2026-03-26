<?php
// authors.php
require 'includes/db.php';
$page_title = 'Our Authors';

$authors = $pdo->query("SELECT * FROM authors ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

require 'includes/header.php';
?>

<div class="page-header">
    <h1>Our Writers & Contributors</h1>
    <p>Meet the passionate people who bring you the news.</p>
</div>

<div class="authors-grid">
    <?php if (count($authors) > 0): ?>
        <?php foreach ($authors as $author): ?>
            <div class="author-card">
                <?php if ($author['image_url']): ?>
                    <img src="<?= htmlspecialchars($author['image_url']) ?>" alt="<?= htmlspecialchars($author['name']) ?>" class="author-avatar">
                <?php else: ?>
                    <div class="author-avatar" style="background-color: #e2e8f0; display:flex; align-items:center; justify-content:center; font-size: 2rem; color: var(--text-muted); font-weight: bold;">
                        <?= substr($author['name'], 0, 1) ?>
                    </div>
                <?php endif; ?>
                <h3 class="author-name"><?= htmlspecialchars($author['name']) ?></h3>
                <p class="author-bio"><?= htmlspecialchars($author['bio'] ?? 'No bio available.') ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; grid-column: 1 / -1; font-size: 1.2rem; color: var(--text-muted);">No authors found.</p>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>
