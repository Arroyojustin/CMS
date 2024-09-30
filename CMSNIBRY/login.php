<?php
session_start();
include('db/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    // Query the users table for the provided username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND user_type = :user_type");
    $stmt->execute(['username' => $username, 'user_type' => $userType]);
    $user = $stmt->fetch();

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $username; // Store the username in the session
        $_SESSION['user_type'] = $userType; // Store the user type in the session

        if ($userType === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: user/dashboard.php');
        }
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        
        <label for="user_type">User Type:</label>
        <select name="user_type" id="user_type" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        
        <button type="submit">Login</button>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </form>
</body>
</html>