<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['user']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle Add User
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type']; // Add user type

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, user_type) VALUES (:username, :password, :email, :user_type)");
    $stmt->execute(['username' => $username, 'password' => $hashed_password, 'email' => $email, 'user_type' => $user_type]);
}

// Handle Edit User
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type']; // Add user type

    // Update password only if provided
    $setPassword = "";
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $setPassword = ", password = :password";
    }

    $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, user_type = :user_type $setPassword WHERE id = :id");
    $params = ['username' => $username, 'email' => $email, 'user_type' => $user_type, 'id' => $id];
    if (!empty($password)) {
        $params['password'] = $hashed_password;
    }
    $stmt->execute($params);
}

// Handle Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Prevent deletion of the admin user
    if ($id != 1) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } else {
        $error = "Cannot delete the admin user.";
    }
}

// Fetch Users
$usersStmt = $pdo->query("SELECT * FROM users");
$users = $usersStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
            <h1>Manage Users</h1>

            <!-- Add User Form -->
            <h2>Add New User</h2>
            <form action="user_management.php" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                
                <label for="user_type">User Type:</label>
                <select name="user_type" id="user_type" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                
                <button type="submit" name="add_user">Add User</button>
            </form>

            <!-- Users Table -->
            <h2>Existing Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['user_type']) ?></td>
                            <td>
                                <!-- Edit User -->
                                <a href="user_management.php?edit=<?= $user['id'] ?>">Edit</a> |
                                <!-- Delete User -->
                                <a href="user_management.php?delete=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            // Handle Edit User Form
            if (isset($_GET['edit'])) {
                $editId = $_GET['edit'];
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
                $stmt->execute(['id' => $editId]);
                $userToEdit = $stmt->fetch();
            ?>
                <!-- Edit User Form -->
                <h2>Edit User</h2>
                <form action="user_management.php" method="post">
                    <input type="hidden" name="id" value="<?= $userToEdit['id'] ?>">
                    
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($userToEdit['username']) ?>" required>
                    
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($userToEdit['email']) ?>" required>
                    
                    <label for="password">Password (leave blank to keep current password):</label>
                    <input type="password" name="password" id="password">
                    
                    <label for="user_type">User Type:</label>
                    <select name="user_type" id="user_type" required>
                        <option value="user" <?= $userToEdit['user_type'] == 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $userToEdit['user_type'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    
                    <button type="submit" name="edit_user">Update User</button>
                </form>
            <?php } ?>

            <?php
            // Display Error if any
            if (isset($error)) {
                echo "<p>$error</p>";
            }
            ?>
        </main>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>