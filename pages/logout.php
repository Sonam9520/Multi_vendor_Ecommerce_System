<?php
session_start();
include_once '../includes/config.php';

// Redirect to the login page or homepage after logout (optional)
header("Location: login.php");  // Replace with your desired redirect
session_destroy();
exit();
?>
