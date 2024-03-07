<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/assets/dist/css/dashboard.css" />
    <link href="https://cdn.jsdelivr.net/npm/grapesjs/dist/css/grapesjs.css" rel="stylesheet" />

    <script src="/assets/dist/js/page-builder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/grapesjs"></script>
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
