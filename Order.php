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
    <nav class="navbar">
    <!-- Centered Navigation Links and Search Bar -->
    <div class="navbar-center">
      <a href="Home.php">HOME</a>
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
