<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($pageData['title']); ?></title>
    <style><?php echo $pageData['css']; ?></style>
</head>
<body>
    <?php echo $pageData['html']; ?>
</body>
</html>