<?php
session_start();
if(isset($_GET['a']) && $_GET['a'] == 'logout'){
    unset($_SESSION['email']);
    unset($_SESSION['password']);

    header('Location: index.php');
}
?>

Профиль

<a href="profile.php?a=logout">Выйти</a>