<?php require("lang.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Тестовое задание</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php if(isset($_SESSION['email']) && isset($_SESSION['password'])): ?>
    <?php require('profile.php'); ?>
<?php else: ?>
    <?php require('login.php'); ?>
<?php endif; ?>
</body>
</html>