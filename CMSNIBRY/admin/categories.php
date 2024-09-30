<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['user']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Add Category
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute(['name' => $category_name]);
}

// Fetch Categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include('header.php'); ?>
    <div class="dashboard-container">
        <aside class="sidebar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="content_management.php">Content</a></li>
                <li><a href="user_management.php">Users</a></li>
            </ul>
        </aside>

        <main class="dashboard-content">
            <h1>Manage Categories</h1>

            <!-- Add Category Form -->
            <form action="categories.php" method="post" class="form-category">
                <label for="category_name">New Category:</label>
                <input type="text" name="category_name" id="category_name" placeholder="Enter new category" required>
                <button type="submit" name="add_category" class="btn-add">Add Category</button>
            </form>

            <!-- List of Existing Categories -->
            <h2>Existing Categories</h2>
            <ul class="category-list">
                <?php foreach ($categories as $category): ?>
                    <li class="category-item"><?= htmlspecialchars($category['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        </main>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>
