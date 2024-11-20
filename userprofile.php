
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
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
    <div class="profile-form-container">
        <h3>Profile</h3>
        <p>View or update your profile details below.</p>
        <form action="" method="post">
            <!-- Username -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
            </div>
            
            <!-- Name -->
            <div class="form-group">
                <label for="f_name">Name:</label>
                <input type="text" id="f_name" name="f_name" value="<?php echo htmlspecialchars($user_data['f_name']); ?>" required>
            </div>
            
            <!-- Surname -->
            <div class="form-group">
                <label for="l_name">Surname:</label>
                <input type="text" id="l_name" name="l_name" value="<?php echo htmlspecialchars($user_data['l_name']); ?>" required>
            </div>
            
            <!-- ZIP and Country -->
            <div class="form-group-inline">
                <div>
                    <label for="zip">ZIP:</label>
                    <input type="text" id="zip" name="zip" value="<?php echo htmlspecialchars($user_data['zip']); ?>" required>
                </div>
                <div>
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user_data['country']); ?>" required>
                </div>
            </div>
            
            <!-- Address -->
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_data['address']); ?>" required>
            </div>
            
            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
            </div>
            
            <!-- Apply Button -->
            <button type="apply" class="apply-button">APPLY CHANGE</button>
        </form>
    </div>
</body>
</html>
