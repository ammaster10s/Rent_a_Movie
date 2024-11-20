<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
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


  <div class="user-container">
    <h2 class="form-title">User Profile</h2>

    <!-- First Name -->
    <label for="f_name">First Name</label>
    <input type="text" id="f_name" name="f_name" placeholder="Enter your first name" aria-required="true" required />

    <!-- Surname -->
    <label for="l_name">Surname</label>
    <input type="text" id="l_name" name="l_name" placeholder="Enter your surname" aria-required="true" required />

    <!-- Username -->
    <label for="username">Username</label>
    <input type="text" id="username" name="username" placeholder="Choose a username (e.g., johndoe123)"
      aria-required="true" required />

    <!-- Password -->
    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter your password (min 8 characters)"
      minlength="8" aria-required="true" required />

    <!-- Zip -->
    <label for="Zip">Zip</label>
    <input type="text" id="zip" name="zip" placeholder="Enter your zip code" aria-required="true" required />

    <!-- Country -->
    <label for="Country">Country</label>
    <input type="text" id="country" name="country" placeholder="Enter your country" aria-required="true" required />

    <!-- Email -->
    <label for="email_address">Email</label>
    <input type="email" id="email_address" name="email_address" placeholder="Enter your email address"
      aria-required="true" required />


    <!-- Apply Button -->
    <button type="submit" class="submit-button">Apply Change</button>

  </div>

</html>