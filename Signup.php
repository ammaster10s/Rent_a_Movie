<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>

<!-- Navigation Bar -->
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
    
  
    
    <!-- Sign-Up Form Section -->
    <div class="signup">
      <div class="container">
        <form action="handle_request.php" method="post">
          <h2>Sign Up</h2>

          <!-- First Name -->
          <label for="f_name">First Name</label>
          <input 
            type="text" 
            id="f_name" 
            name="f_name" 
            placeholder="Enter your first name" 
            aria-required="true" 
            required 
          />

          <!-- Surname -->
          <label for="l_name">Surname</label>
          <input 
            type="text" 
            id="l_name" 
            name="l_name" 
            placeholder="Enter your surname" 
            aria-required="true" 
            required 
          />

          <!-- Username -->
          <label for="username">Username</label>
          <input 
            type="text" 
            id="username" 
            name="username" 
            placeholder="Choose a username (e.g., johndoe123)" 
            aria-required="true" 
            required 
          />

          <!-- Password -->
          <label for="password">Password</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Enter your password (min 8 characters)" 
            minlength="8" 
            aria-required="true" 
            required 
          />

          <!-- Confirm Password -->
          <label for="confirm_password">Confirm Password</label>
          <input 
            type="password" 
            id="confirm_password" 
            name="confirm_password" 
            placeholder="Re-enter your password" 
            minlength="8" 
            aria-required="true" 
            required 
          />

          <!-- Email -->
          <label for="email_address">Email</label>
          <input 
            type="email" 
            id="email_address" 
            name="email_address" 
            placeholder="Enter your email address" 
            aria-required="true" 
            required 
          />

          <!-- Submit Button -->
          <button type="submit" name="register" class="submit-button">CREATE ACCOUNT</button>
          <!-- Apply Button -->
          <!-- <button type="Apply" class="apply-button">CREATE ACCOUNT</button> -->

          <!-- Redirect to Login -->
          <p>Already have an account? <a href="Login.php">Click here!</a></p>
        </form>
      </div>
    </div>

    <!-- Include Scripts (Optional for Dynamic Features) -->
    <script>
      // Example: Add password match validation
      document.querySelector('form').addEventListener('apply', function (e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        if (password !== confirmPassword) {
          e.preventDefault();
          alert('Passwords do not match!');
        }
      });
    </script>

  </body>
</html>
