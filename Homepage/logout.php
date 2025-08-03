<?php
// Start the session to access and manage session variables
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session completely.
// This also deletes the session file on the server.
session_destroy();

// Redirect the user to the homepage or login page
// In this case, we'll redirect back to index.html
header("Location: ../reglogin/auth.php");
exit;
?>