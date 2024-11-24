<?php

session_start();

if (isset($_SESSION['errors'])) {
    echo '<pre>';
    print_r($_SESSION['errors']);
    echo '</pre>';
}
?>
