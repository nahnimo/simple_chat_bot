<?php
session_start();
require_once("config.php");
// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the login page or any other desired location
header('Location: login.php');
exit();
?>
