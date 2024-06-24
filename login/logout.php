<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Remove the remember me cookie if set
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, "/", "", true, true);
}

// Redirect to the login page
header("Location: login.html");
exit();
?>
