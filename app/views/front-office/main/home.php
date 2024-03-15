<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/dist/css/main.css" />
    <title>Home</title>   
</head>
<body>
<?php

$components = [
    'navbar.php',
    'hero.php',
    'content-layout.php',
    'content-layout-project.php',
    'trust-us.php',
    'testimonial.php',
    'FAQ.php',
    'footer.php',
];

foreach ($components as $component) {
    include $_SERVER['DOCUMENT_ROOT'] . '/../app/views/partials/' . $component;
}
?>
</body>
</html>
