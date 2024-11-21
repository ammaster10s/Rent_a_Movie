<?php
session_start();
include 'database.php';

// Function to check if the user profile is complete
function isProfileComplete($username, $conn)
{
    $query = "SELECT ADDRESS_ID FROM User_Address WHERE (SELECT User_ID From Users where Username=?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return false; // Fail gracefully
    }
    $stmt->bind_param("i", $username);
    $stmt->execute();
    $address= null;
    $stmt->bind_result($address);
    $stmt->fetch();
    $stmt->close();

    return !empty($address);
}

// Check user session and profile completion
$showNotification = false;
if (isset($_SESSION['username'])) {
    $showNotification = !isProfileComplete($_SESSION['username'], $conn);
}
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


<!-- Notification Bar -->
<?php if ($showNotification): ?>
    <div id="notification" class="notification">
        <p class="notification-text">
            🎉 Welcome! It seems your profile is incomplete. 
            <a href="userprofile.php" class="configure-link">Configure your profile</a> to get personalized movie recommendations.
        </p>
        <button id="close-notification" class="close-btn" aria-label="Close Notification">&times;</button>
    </div>
<?php endif; ?>



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
  <script>
    // Close Notification Bar
    document.addEventListener('DOMContentLoaded', () => {
      const closeNotification = document.getElementById('close-notification');
      if (closeNotification) {
        closeNotification.addEventListener('click', () => {
          const notification = document.getElementById('notification');
          if (notification) {
            notification.style.display = 'none';
          }
        });
      }
    });
  </script>
</body>

</html>
<?php
