<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="Mainpage.php">HOME</a>
        <a href="history.php">HISTORY</a>
        <a href="wishlist.php">WISHLIST</a>
        <a href="tv_series.php">TV SERIES</a>
        <a href="movies.php">MOVIES</a>
        <div class="search-container">
          <input type="text" placeholder="Search..." aria-label="Search" />
          <img src="img/1413908-1.png" alt="Search Icon" />
        </div>
      </nav>
      
      
    

    <!-- Login Form Section -->
    <div class="LOGIN">
        <div class="form-container">
            <form action="handle_request.php" method="post">
                <h2 class="form-title">Login</h2>

                <!-- Email Address -->
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email address" required />

                <!-- Password -->
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password" required />

                <!-- Remember Password -->
                <div class="checkbox-container">
                    <label for="remember_password">
                        Remember Password?
                        <input type="checkbox" id="remember_password" name="remember_password" />
                    </label>
                </div>
                

                <!-- Submit Button -->
                <button type="submit" class="submit-button">LOG IN</button>

                <!-- Redirect to Signup -->
                <p class="redirect-text">Don’t have an account? <a href="Signup.php">Click here!</a></p>
            </form>
        </div>
    </div>
</body>
</html>
