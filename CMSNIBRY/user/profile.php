<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Fetch user data
$username = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="user-profile-body">
    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <aside class="sidebar">
            <h2 class="sidebar-title">Menu</h2>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">View Profile</a></li>
                <li><a href="categories.php">Browse Categories</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="recent_posts.php">Recent Posts</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="profile-content">
            <h1 class="profile-title">Profile of <?= htmlspecialchars($user['username']) ?></h1>
            
            <div class="profile-card">
                <div class="profile-header">
                    <!-- Display the profile photo if it exists -->
                    <img src="<?= !empty($user['profile_photo']) ? '../' . htmlspecialchars($user['profile_photo']) : '../assets/images/default-profile.png' ?>" alt="Profile Picture" class="profile-pic">
                    <h2 class="profile-username"><?= htmlspecialchars($user['username']) ?></h2>
                </div>
                <div class="profile-details">
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <!-- Add more user details here -->
                </div>
                <div class="profile-actions">
                    <a href="edit_profile.php" class="btn">Edit Profile</a>
                </div>
            </div>
        </main>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
