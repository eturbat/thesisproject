<?php
session_start();

$_SESSION = array();

session_destroy();

header("Location: professor_login.php");
exit;
?>