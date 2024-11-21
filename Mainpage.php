<?php
// session_start();
include 'database.php';

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
include 'auth_check.php';

// Function to check if the user profile is complete
function isProfileComplete($username, $conn)
{
  $query = "SELECT ADDRESS_ID FROM User_Address WHERE User_ID = (SELECT User_ID FROM Users WHERE Username=?)";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return false; // Fail gracefully
  }
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $address = null;
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

  <div class="mainpage">
    <div class="content">
      <p class="rent-movies-to-watch">Rent Movies to Watch on DVD &amp; Blu-ray</p>
      <p class="text-wrapper">Unlike movie streaming services, we deliver your movie to you, and you return it back to us.</p>

      <?php
      // Define movie categories
      $categories = ['Trending', 'Thriller', 'SciFi_Fantasy'];

      // Loop through each category
      foreach ($categories as $category): ?>
        <div class="categories">
          <h2 class="text-wrapper-2"><?php echo htmlspecialchars($category); ?> Movies</h2>
          <div class="image-row">
            <?php
            // Prepare and execute query to fetch movies in the current category
            $query = "SELECT Movie_ID, Movie_Name, Poster_Path FROM Movie WHERE Category = ?";
            if ($stmt = $conn->prepare($query)) {
              $stmt->bind_param("s", $category);
              $stmt->execute();
              $result = $stmt->get_result();

              // Check if any movies are returned
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()):
                  $posterPath = file_exists($row['Poster_Path']) ? htmlspecialchars($row['Poster_Path']) : 'img/default-poster.jpg';
            ?>
                  <img
                    class="image movie-poster"
                    src="<?php echo $posterPath; ?>"
                    alt="<?php echo htmlspecialchars($row['Movie_Name']); ?>"
                    data-movie-id="<?php echo $row['Movie_ID']; ?>"
                    data-movie-name="<?php echo htmlspecialchars($row['Movie_Name']); ?>"
                    data-movie-description="This is a placeholder description for <?php echo htmlspecialchars($row['Movie_Name']); ?>." />
            <?php endwhile;
              } else {
                echo "<p class='no-movies'>No movies found in the $category category.</p>";
              }

              $stmt->close();
            } else {
              echo "<p class='query-error'>Error preparing the query for $category category.</p>";
            }

            ?>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>



  <!-- Modal Container -->
  <div id="movie-modal" class="modal">
  <div class="modal-content">
    <span id="close-modal" class="close">&times;</span>
    <div id="movie-details">
      <h3>Minecraft Movie</h3>
      <div class="movie-info">
        <p><strong>Description:</strong> An action-packed adventure where players are transported into the Minecraft universe, fighting mobs and building to survive.</p>
        <p><strong>Price:</strong> $10.99</p>
        <p><strong>Release Date:</strong> 2025-04-04</p>
        <p><strong>Length:</strong> 120 mins</p>
        <p><strong>Main Actor:</strong> Jason Momoa</p>
      </div>
    </div>
    <button id="add-to-cart-btn" class="add-to-cart-btn">Add to Cart</button>
  </div>
</div>


  <script>
    // Modal Display Logic
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('movie-modal');
      const closeModal = document.getElementById('close-modal');
      const movieDetails = document.getElementById('movie-details');
      const addToCartBtn = document.getElementById('add-to-cart-btn');

      let currentMovieId = null;

      // Add event listeners to all movie posters
      document.querySelectorAll('.movie-poster').forEach(poster => {
        poster.addEventListener('click', async (e) => {
          currentMovieId = e.target.dataset.movieId;

          // Fetch movie details
          const response = await fetch(`getmovieDetail.php?movie_id=${currentMovieId}`);
          const details = await response.text();

          // Display details in modal
          movieDetails.innerHTML = details;
          modal.style.display = 'block';
        });
      });

      // Add to Cart Button Logic
      addToCartBtn.addEventListener('click', async () => {
        if (!currentMovieId) return;

        // Send the movie ID to the server to add to the cart
        const response = await fetch(`addToCart.php?movie_id=${currentMovieId}`);
        const result = await response.json();

        if (result.success) {
          alert(`Movie added to cart: ${result.message}`);
        } else {
          alert(`Error adding movie to cart: ${result.message}`);
        }

        // Close the modal after adding to cart
        modal.style.display = 'none';
      });

      // Close modal when clicking the close button
      closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
      });

      // Close modal when clicking outside the modal content
      window.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.style.display = 'none';
        }
      });
    });
  </script>


</body>

</html>