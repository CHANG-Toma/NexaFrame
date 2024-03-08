<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/assets/dist/css/dashboard.css" />
    <script src="/assets/dist/js/bundle.js" defer></script>

    <title>Dashboard</title>
</head>

<body>

    <?php

    foreach ($components as $component) {
        include $_SERVER['DOCUMENT_ROOT'] . '/../app/views/partials/' . $component;
    }

    ?>

</body>

</html>