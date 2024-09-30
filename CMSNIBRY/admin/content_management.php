<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['user']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle Add Post
if (isset($_POST['add_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, category_id) VALUES (:title, :content, :category_id)");
    $stmt->execute(['title' => $title, 'content' => $content, 'category_id' => $category_id]);
}

// Handle Edit Post
if (isset($_POST['edit_post'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    
    $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content, category_id = :category_id WHERE id = :id");
    $stmt->execute(['title' => $title, 'content' => $content, 'category_id' => $category_id, 'id' => $id]);
}

// Handle Delete Post
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

// Fetch Posts and Categories
$postsStmt = $pdo->query("SELECT p.*, c.name AS category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id");
$posts = $postsStmt->fetchAll();

$categoriesStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoriesStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Content</title>
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
            <h1>Manage Content</h1>

            <!-- Add Post Form -->
            <h2>Add New Post</h2>
            <form action="content_management.php" method="post" class="form-content">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>
                
                <label for="content">Content:</label>
                <textarea name="content" id="content" rows="5" required></textarea>
                
                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" name="add_post" class="btn-add">Add Post</button>
            </form>

            <!-- Posts Table -->
            <h2>Existing Posts</h2>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= htmlspecialchars($post['id']) ?></td>
                            <td><?= htmlspecialchars($post['title']) ?></td>
                            <td><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</td>
                            <td><?= htmlspecialchars($post['category_name']) ?></td>
                            <td>
                                <!-- Edit Post -->
                                <a href="content_management.php?edit=<?= $post['id'] ?>" class="btn-edit">Edit</a> |
                                <!-- Delete Post -->
                                <a href="content_management.php?delete=<?= $post['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            // Handle Edit Post Form
            if (isset($_GET['edit'])) {
                $editId = $_GET['edit'];
                $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
                $stmt->execute(['id' => $editId]);
                $postToEdit = $stmt->fetch();
            ?>
                <!-- Edit Post Form -->
                <h2>Edit Post</h2>
                <form action="content_management.php" method="post" class="form-content">
                    <input type="hidden" name="id" value="<?= $postToEdit['id'] ?>">
                    
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" value="<?= htmlspecialchars($postToEdit['title']) ?>" required>
                    
                    <label for="content">Content:</label>
                    <textarea name="content" id="content" rows="5" required><?= htmlspecialchars($postToEdit['content']) ?></textarea>
                    
                    <label for="category_id">Category:</label>
                    <select name="category_id" id="category_id" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category['id'] == $postToEdit['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button type="submit" name="edit_post" class="btn-update">Update Post</button>
                </form>
            <?php } ?>

        </main>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>
