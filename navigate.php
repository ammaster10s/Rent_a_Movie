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
<style>
    .admin-button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    margin-right: 10px;
}

.admin-button:hover {
    background-color: #45a049;
}

</style>
<body>
<nav class="navbar">
    <!-- Centered Navigation Links -->
    <div class="navbar-center">
        <a href="Mainpage.php">HOME</a>

        <?php if (isset($_SESSION['username'])): ?>
            <!-- Display these links only when the user is logged in -->
            <a href="history.php">HISTORY</a>
            <a href="wishlist.php">WISHLIST</a>
        <?php endif; ?>

        <!-- Search Bar -->
        <?php if (isset($_SESSION['username'])): ?>
            <div class="search-container">
                <form action="search_result.php" method="get" >
                    <input type="text" name="q" placeholder="Search..." aria-label="Search" required />
                </form>
               
                <a href="Order.php"><img class="nav-icon" src="img/Cart-icon.png" alt="Cart Icon" /></a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right-side Items -->
    <div class="navbar-right">
        <?php if (isset($_SESSION['username'])): ?>
            <!-- Show Admin Dashboard link if the user is an admin -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                <a href="admin_dashboard.php" class="admin-button">ADMIN DASHBOARD</a>
            <?php endif; ?>
            
            <!-- Show Logout when logged in -->
            <a href="logout.php">LOGOUT</a>
            <a href="userprofile.php"><img class="nav-profileicon" src="img/profile-icon.png" alt="Profile Icon" /></a>
        <?php else: ?>
            <!-- Show Signup and Login when logged out -->
            <a href="Signup.php">SIGNUP</a>
            <a href="Login.php">LOGIN</a>
        <?php endif; ?>
    </div>
</nav>
</body>

</html>
