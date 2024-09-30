<?php
session_start();
include('../db/config.php');

// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle Update Profile
if (isset($_POST['update_profile'])) {
    $admin_id = $_SESSION['admin_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare SQL query for profile update
        $query = "UPDATE users SET username = :username, email = :email";
        $params = ['username' => $username, 'email' => $email];

        if (!empty($password)) {
            // Update password if provided
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query .= ", password = :password";
            $params['password'] = $hashed_password;
        }

        $query .= " WHERE id = :id";
        $params['id'] = $admin_id;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        $message = "Profile updated successfully!";
    } catch (PDOException $e) {
        $message = "Error updating profile: " . $e->getMessage();
    }
}

// Fetch Current Admin Profile
$admin_id = $_SESSION['admin_id'];
try {
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = :id");
    $stmt->execute(['id' => $admin_id]);
    $admin = $stmt->fetch();
} catch (PDOException $e) {
    $message = "Error fetching profile: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Settings</title>
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
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </aside>
        <main class="dashboard-content">
            <h1>Admin Settings</h1>

            <!-- Display Message -->
            <?php if (isset($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <!-- Update Profile Form -->
            <h2>Update Profile</h2>
            <form action="settings.php" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($admin['username']) ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
                
                <label for="password">New Password (leave blank to keep current password):</label>
                <input type="password" name="password" id="password">
                
                <button type="submit" name="update_profile">Update Profile</button>
            </form>
        </main>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
