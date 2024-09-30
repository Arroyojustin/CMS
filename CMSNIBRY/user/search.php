<?php
include('../db/config.php');

$query = $_GET['query'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE :query");
$stmt->execute(['query' => "%$query%"]);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for search icon -->
</head>
<body class="user-search-body">
    <?php include('header.php'); ?>

    <div class="user-search-form-container">
        <form action="search.php" method="GET" class="user-search-form">
            <input type="text" name="query" placeholder="Search..." class="user-search-input" value="<?= htmlspecialchars($query) ?>">
            <button type="submit" class="user-search-button">
                <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
            </button>
        </form>
    </div>

    <div class="user-search-results-container">
        <div class="user-search-results-header">
            <h1>Search Results for: "<span><?= htmlspecialchars($query) ?></span>"</h1>
        </div>
        
        <div class="user-search-results-content">
            <?php if (count($results) > 0): ?>
                <ul class="user-search-results-list">
                    <?php foreach ($results as $result): ?>
                        <li class="user-search-result-item">
                            <a href="content.php?id=<?= $result['id'] ?>" class="user-search-result-link"><?= htmlspecialchars($result['title']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No results found for "<span><?= htmlspecialchars($query) ?></span>".</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
