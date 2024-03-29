<body>
    <?php
        foreach ($articles as $article) {
            echo "<h1>" . $article["title"] . "</h1>";
            echo "<p>" . $article["content"] . "</p>";
            echo "<p>" . $article["keywords"] . "</p>";
            echo "<img src='" . $article["picture_url"] . "' />";
            echo "<p>" . $article["created_at"] . "</p>";
        }
    ?>
</body>