<?php
// session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Renting Movie System</title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
<nav class="navbar">
    <!-- Centered Navigation Links -->
    <div class="navbar-center">
        <a href="Mainpage.php">HOME</a>

        <?php if (isset($_SESSION['username'])): ?>
            <!-- Display these links only when the user is logged in -->
            <a href="history.php">HISTORY</a>
            <a href="wishlist.php">WISHLIST</a>
            <a href="tv_series.php">TV SERIES</a>
            <a href="movies.php">MOVIES</a>
        <?php endif; ?>

        <!-- Search Bar -->
        <?php if (isset($_SESSION['username'])): ?>
            <div class="search-container">
                <input type="text" placeholder="Search..." aria-label="Search" />
                <img src="img/search-icon.png" alt="Search Icon" />
                <a href = Order.php> <img src="img/Cart-icon.png" alt="Cart Icon" /></a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right-side Items -->
    <div class="navbar-right">
        <?php if (isset($_SESSION['username'])): ?>
            <!-- Show Logout when logged in -->
            <a href="logout.php">LOGOUT</a>
            <a href="userprofile.php"><img class="profile-icon" src="img/profile-icon.png" alt="Profile Icon" /></a>
        <?php else: ?>
            <!-- Show Signup and Login when logged out -->
            <a href="Signup.php">SIGNUP</a>
            <a href="Login.php">LOGIN</a>
        <?php endif; ?>
    </div>
</nav>
</body>
</html>
