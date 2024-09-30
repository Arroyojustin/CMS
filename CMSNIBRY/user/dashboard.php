<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['user']) || $_SESSION['user_type'] != 'user') {
    header('Location: ../login.php');
    exit;
}

// Fetch user information
$username = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

// Fetch recent posts
$postsStmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
$recent_posts = $postsStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="user-dashboard-body">
    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <aside class="sidebar">
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="profile.php">View Profile</a></li>
                <li><a href="categories.php">Browse Categories</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="recent_posts.php">Recent Posts</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>
        <main class="dashboard-content">
            <h1>Welcome, <?= htmlspecialchars($user['username']) ?>!</h1>

            <h2>Your Profile</h2>
            <p>Email: <?= htmlspecialchars($user['email']) ?></p>

            <h2>Recent Posts</h2>
            <ul>
                <?php foreach ($recent_posts as $post): ?>
                    <li><a href="content.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </main>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
