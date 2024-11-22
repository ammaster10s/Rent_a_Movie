<?php
// session_start();
include 'database.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

include 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wishlist</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
    include 'navigate.php';
    ?>
  <div class="wishlist-container">
    <h1>Wishlist</h1>
    <div class="wishlist-item">
      <div class="movie-poster">
        <img src="poster.jpg" alt="No Time to Die">
      </div>
      <div class="movie-details">
        <h2>No Time To Die</h2>
        <p>2021 • 2h 21min</p>
        <p>Daniel Craig returns one last time as James Bond, starring alongside Oscar winner Rami Malek in 'No Time To Die'. Bond has left active service and is enjoying a tranquil life in Jamaica. His peace is short-lived when his old friend Felix Leiter (Jeffrey Wright) from the CIA turns up asking for help.</p>
        <p><strong>Actors:</strong> Daniel Craig, Ana de Armas, Rami Malek</p>
        <p><strong>Director:</strong> Cary Joji Fukunaga</p>
        <p><strong>Genres:</strong> Thrillers, Action & Adventure</p>
        <p><strong>Price:</strong> $19.99</p>
        <div class="actions">
          <button class="btn">Add to Wishlist</button>
          <button class="btn">Add to Cart</button>
        </div>
      </div>
    </div>

    <!-- Duplicate for another movie -->
    <div class="wishlist-item">
      <div class="movie-poster">
        <img src="poster.jpg" alt="No Time to Die">
      </div>
      <div class="movie-details">
        <h2>No Time To Die</h2>
        <p>2021 • 2h 21min</p>
        <p>Daniel Craig returns one last time as James Bond, starring alongside Oscar winner Rami Malek in 'No Time To Die'. Bond has left active service and is enjoying a tranquil life in Jamaica. His peace is short-lived when his old friend Felix Leiter (Jeffrey Wright) from the CIA turns up asking for help.</p>
        <p><strong>Actors:</strong> Daniel Craig, Ana de Armas, Rami Malek</p>
        <p><strong>Director:</strong> Cary Joji Fukunaga</p>
        <p><strong>Genres:</strong> Thrillers, Action & Adventure</p>
        <p><strong>Price:</strong> $19.99</p>
        <div class="actions">
          <button class="btn">Add to Wishlist</button>
          <button class="btn">Add to Cart</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
