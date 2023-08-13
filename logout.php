<?php
// Start the session
session_start();

echo "<script>";
              echo "console.log('Logged out')";
              echo "</script>";

// Destroy the session to log the user out
session_destroy();

// Redirect to the login page or any other desired page
header("Location: login.php");
exit();
?>