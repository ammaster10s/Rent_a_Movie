<?php
include 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Summary</title>
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <!-- Navigation Bar -->
  <?php
  include 'navigate.php';
  ?>


  <!-- Order Section -->
  <div class="order-page">
    <h1 class="order-title">Your Order</h1>

    <!-- Order Items -->
    <div class="order-items">
      <div class="order-item">
        <div class="item-details">
          <h3 class="movie-title">NO TIME TO DIE</h3>
          <button class="remove-button">REMOVE</button>
        </div>
        <div class="item-info">
          <span class="quantity">1</span>
          <span class="price">14.99$/WEEK</span>
        </div>
      </div>

      <div class="order-item">
        <div class="item-details">
          <h3 class="movie-title">A QUIET PLACE</h3>
          <button class="remove-button">REMOVE</button>
        </div>
        <div class="item-info">
          <span class="quantity">1</span>
          <span class="price">14.99$/WEEK</span>
        </div>
      </div>
    </div>

    <!-- Total Price -->
    <div class="total-price">
      <span>TOTAL PRICE:</span>
      <span class="price">29.98$</span>
    </div>

    <!-- Pay Button -->
    <button class="pay-button">PAY NOW</button>

    <!-- Continue Shopping -->
    <a href="movies.php" class="continue-shopping">Continue Shopping</a>
  </div>
</body>

</html>