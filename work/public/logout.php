<?php

require_once(__DIR__ . '/../app/config.php');

if(!isset($_SESSION["login"])) {
  header("Location: index.php");
  exit;
}

unset($_SESSION['login']);

if (isset($_COOKIE["PHPSSID"])) {
  setcookie("PHPSESSID", '', time() - 1800, '/');
}

session_destroy();

header("Location: index.php");