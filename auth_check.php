<?php

// If you want first page to be mainpage.php comment this


// Check if a session is already started before starting a new one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}
?>
