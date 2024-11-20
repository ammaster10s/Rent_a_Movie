<?php
include 'auth_check.php';
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
    <!-- Centered Navigation Links and Search Bar -->
    <div class="navbar-center">
      <a href="Mainpage.php">HOME</a>
      <a href="history.php">HISTORY</a>
      <a href="wishlist.php">WISHLIST</a>
      <a href="tv_series.php">TV SERIES</a>
      <a href="movies.php">MOVIES</a>
  
      <!-- Search Bar -->
      <div class="search-container">
        <input type="text" placeholder="Search..." aria-label="Search" />
        <img src="img/search-icon.png" alt="Search Icon" />
      </div>
    </div>
  
    <!-- Right-side Items -->
    <div class="navbar-right">
      <a href="Signup.php">SIGNUP</a>
      <a href="Login.php">LOGIN</a>
      <img class="profile-icon" src="img/profile-icon.png" alt="Profile Icon" />
    </div>
  </nav>

  

  <!-- Main Page Content -->
  <div class="mainpage">
    <div class="content">
      <!-- Rent Movies Text at the Top -->
      <p class="rent-movies-to-watch">
        Rent Movies to Watch on DVD &amp; Blu-ray
      </p>

      <!-- Description Below -->
      <p class="text-wrapper">
        Unlike movie streaming services, we deliver your movie to you, and you return it back to us.
      </p>

      <!-- Trending Movies Title -->
      <div class="categories">
        <h2 class="text-wrapper-2">Trending Movies</h2>
      </div>

      <!-- Trending Movies Image -->
      <div class="image-row">
        <img class="image" src="img/Trending-1.jpg" alt="Trending 1" />
        <img class="image" src="img/Trending-2.jpg" alt="Trending 2" />
        <img class="image" src="img/Trending-3.jpg" alt="Trending 3" />
        <img class="image" src="img/Trending-4.jpg" alt="Trending 4" />
        <img class="image" src="img/Trending-5.jpg" alt="Trending 5" />
        <img class="image" src="img/Trending-6.jpg" alt="Trending 6" />
      </div>
      
      <!-- Thriller Movies Title -->
      <div class="categories">
        <h2 class="text-wrapper-2">Thriller Movies</h2>
      </div>

      <!-- Thriller Movies Image -->
      <div class="image-row">
        <img class="image" src="img/Thriller-1.jpg" alt="Thriller 1" />
        <img class="image" src="img/Thriller-2.jpg" alt="Thriller 2" />
        <img class="image" src="img/Thriller-3.jpg" alt="Thriller 3" />
        <img class="image" src="img/Thriller-4.jpg" alt="Thriller 4" />
        <img class="image" src="img/Thriller-5.jpg" alt="Thriller 5" />
        <img class="image" src="img/Thriller-6.jpg" alt="Thriller 6" />
      </div>

      <!-- Sci-Fi & Fantasy Movies Title -->
      <div class="categories">
        <h2 class="text-wrapper-2">Sci-Fi & Fantasy Movies</h2>
      </div>

      <!-- Sci-Fi & Fantasy Image -->
      <div class="image-row">
        <img class="image" src="img/SciFi_Fantasy-1.jpg" alt="SciFi_Fantasy 1" />
        <img class="image" src="img/SciFi_Fantasy-2.jpg" alt="SciFi_Fantasy 2" />
        <img class="image" src="img/SciFi_Fantasy-3.jpg" alt="SciFi_Fantasy 3" />
        <img class="image" src="img/SciFi_Fantasy-4.jpg" alt="SciFi_Fantasy 4" />
        <img class="image" src="img/SciFi_Fantasy-5.jpg" alt="SciFi_Fantasy 5" />
        <img class="image" src="img/SciFi_Fantasy-6.jpg" alt="SciFi_Fantasy 6" />
      </div>
    </div>
  </div>
</body>

</html>
