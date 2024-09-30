<?php
session_start();
include('../db/config.php');
include('upload_directory.php'); // Ensure uploads directory is set up

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

// Fetch user data
$username = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $password = $_POST['password'];
    $profile_photo = $_FILES['profile_photo'];

    // Update user data
    $update_query = "UPDATE users SET username = :username, email = :email";
    $params = ['username' => $new_username, 'email' => $new_email];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query .= ", password = :password";
        $params['password'] = $hashed_password;
    }

    // Handle profile photo upload
    if (!empty($profile_photo['name'])) {
        $upload_dir = __DIR__ . '/../uploads/';
        $photo_path = 'uploads/' . basename($profile_photo['name']);

        if (move_uploaded_file($profile_photo['tmp_name'], $upload_dir . basename($profile_photo['name']))) {
            $update_query .= ", profile_photo = :profile_photo";
            $params['profile_photo'] = $photo_path;
        } else {
            $message = "Failed to upload photo.";
        }
    }

    $update_query .= " WHERE username = :username";
    $stmt = $pdo->prepare($update_query);
    $stmt->execute($params);

    $message = "Profile updated successfully!";
    // Refresh user data after update
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $new_username]);
    $user = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="user-dashboard">
        <aside class="user-sidebar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">View Profile</a></li>
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="categories.php">Browse Categories</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="recent_posts.php">Recent Posts</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>
        <main class="user-form">
            <h1>Edit Profile</h1>

            <!-- Display Message -->
            <?php if (isset($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <!-- Edit Profile Form -->
            <form action="edit_profile.php" method="post" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                
                <label for="password">New Password</label>
                <input type="password" name="password" id="password">
                
                <label for="profile_photo">Profile Photo:</label>
                <input type="file" name="profile_photo" id="profile_photo">
                
                <button type="submit" name="update_profile">Update Profile</button>
            </form>
        </main>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
