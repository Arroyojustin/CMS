<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include('./header.php'); ?>
    <div class="dashboard-container">
        <aside class="sidebar">
            <ul>
                <li><a href="./dashboard.php">Dashboard</a></li>
                <li><a href="./categories.php">Categories</a></li>
                <li><a href="./content_management.php">Content</a></li>
                <li><a href="./user_management.php">Users</a></li>
            </ul>
        </aside>
        <main class="dashboard-content">
            <h1>WELCOME</h1>

            <section class="sports-section">
                <h2>Sports</h2>
                <p>In the Sports section, users will see the latest updates and scores from their favorite teams. Whether it's football, basketball, or any other sport, this section will keep users informed about upcoming matches, results, and breaking news.</p>
            </section>

            <section class="news-section">
                <h2>News</h2>
                <p>The News section provides users with current events, politics, technology updates, and international news. Users will find articles from various news sources to stay updated on what's happening around the world.</p>
            </section>

            <section class="anime-section">
                <h2>Anime</h2>
                <p>In the Anime section, users can browse through the latest anime episodes, news about upcoming seasons, and reviews of popular anime series. It's a hub for anime fans to stay connected with their favorite shows.</p>
            </section>

            <section class="gaming-section">
               <h2>Gaming</h2>
                <p>The Gaming section offers the latest news, reviews, and trailers for upcoming games. Users can explore updates on popular gaming platforms like PlayStation, Xbox, PC, and mobile. This section also features content about esports events, gaming tips, and in-depth reviews of the latest releases in the gaming world.</p>
            </section>
            
        </main>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>
