<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Fetch categories with descriptions
$categoriesStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoriesStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="user-dashboard-body">
    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <aside class="sidebar">
            <ul>
                <li><a href="home_dashboard.php">Home</a></li>
                <li><a href="profile.php">View Profile</a></li>
                <li><a href="profile.php">Browse Categories</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="recent_posts.php">Recent Posts</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>
        <main class="dashboard-content">
            <h1>Welcome to Your Home Dashboard</h1>
            
            <h2>Explore Categories</h2>
            <p>Browse through various categories to find interesting content tailored to your interests.</p>

            <div class="category-list">
                <?php foreach ($categories as $category): ?>
                    <div class="category-item">
                        <h3><?= htmlspecialchars($category['name']) ?></h3>
                        <!-- Check if description is set before displaying -->
                        <p><?= isset($category['description']) ? htmlspecialchars($category['description']) : 'No description available.' ?></p>
                        <a href="category.php?id=<?= $category['id'] ?>" class="btn">Explore <?= htmlspecialchars($category['name']) ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
