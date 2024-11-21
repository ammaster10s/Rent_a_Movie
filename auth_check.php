<?php

// If you want first page to be mainpage.php comment this

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

?>