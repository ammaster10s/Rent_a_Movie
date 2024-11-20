<?php
include 'auth_check.php';
session_start();
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

  <?php
  include 'navigate.php';
  ?>

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

      <!-- Trending Movies Section -->
      <div class="categories">
        <h2 class="text-wrapper-2">Trending Movies</h2>
        <div class="image-row">
          <?php for ($i = 1; $i <= 6; $i++): ?>
            <img class="image" src="img/Trending-<?php echo $i; ?>.jpg" alt="Trending <?php echo $i; ?>" />
          <?php endfor; ?>
        </div>
      </div>

      <!-- Thriller Movies Section -->
      <div class="categories">
        <h2 class="text-wrapper-2">Thriller Movies</h2>
        <div class="image-row">
          <?php for ($i = 1; $i <= 6; $i++): ?>
            <img class="image" src="img/Thriller-<?php echo $i; ?>.jpg" alt="Thriller <?php echo $i; ?>" />
          <?php endfor; ?>
        </div>
      </div>

      <!-- Sci-Fi & Fantasy Movies Section -->
      <div class="categories">
        <h2 class="text-wrapper-2">Sci-Fi & Fantasy Movies</h2>
        <div class="image-row">
          <?php for ($i = 1; $i <= 6; $i++): ?>
            <img class="image" src="img/SciFi_Fantasy-<?php echo $i; ?>.jpg" alt="Sci-Fi & Fantasy <?php echo $i; ?>" />
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>

</body>

</html>