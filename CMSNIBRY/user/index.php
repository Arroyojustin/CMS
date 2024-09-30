<?php
include('../db/config.php');

// Fetch Recent Posts
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
$recent_posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include('header.php'); ?>
    <h1>Welcome to the CMS</h1>
    
    <h2>Recent Posts</h2>
    <ul>
        <?php foreach ($recent_posts as $post): ?>
            <li><a href="content.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></li>
        <?php endforeach; ?>
    </ul>

    <?php include('footer.php'); ?>
</body>
</html>
