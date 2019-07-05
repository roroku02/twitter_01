<?php
session_start();

$_SESSION = [];
header('Content-Type: text/html; charset=UTF-8');

if(isset($_COOKIE['PHPSESSID'])){
    setcookie("PHPSESSID", '', time() - 1800, '/');
}

session_destroy();
header('location: ./login.php');
?>