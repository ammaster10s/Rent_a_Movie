<?php
// session_start();
include 'database.php';
include 'auth_check.php';

// Check if the user profile is complete
function isProfileComplete($username, $conn)
{
  $query = "SELECT ADDRESS_ID FROM User_Address WHERE User_ID = (SELECT User_ID FROM Users WHERE Username=?)";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return false;
  }
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $address = null;
  $stmt->bind_result($address);
  $stmt->fetch();
  $stmt->close();
  return !empty($address);
}

// Check session for profile completion
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
  <?php include 'navigate.php'; ?>

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

  <!-- Movie Categories -->
  <div class="mainpage">
    <div class="content">
      <p class="rent-movies-to-watch">Rent Movies to Watch on DVD &amp; Blu-ray</p>
      <p class="text-wrapper">Unlike movie streaming services, we deliver your movie to you, and you return it back to us.</p>

      <?php
      $categories = ['Trending', 'Thriller', 'SciFi_Fantasy'];

      foreach ($categories as $category): ?>
        <div class="categories">
          <h2 class="text-wrapper-2"><?php echo htmlspecialchars($category); ?> Movies</h2>
          <div class="image-row">
            <?php
            $query = "SELECT Movie_ID, Movie_Name, Poster_Path, Description, Price, Released_date, Length, Main_Actor FROM Movie WHERE Category = ?";
            if ($stmt = $conn->prepare($query)) {
              $stmt->bind_param("s", $category);
              $stmt->execute();
              $result = $stmt->get_result();

              while ($row = $result->fetch_assoc()): ?>
                <div class="movie-card"
                  data-movie-id="<?php echo $row['Movie_ID']; ?>"
                  data-movie-name="<?php echo htmlspecialchars($row['Movie_Name']); ?>"
                  data-description="<?php echo htmlspecialchars($row['Description']); ?>"
                  data-price="<?php echo htmlspecialchars($row['Price']); ?>"
                  data-released-date="<?php echo htmlspecialchars($row['Released_date']); ?>"
                  data-length="<?php echo htmlspecialchars($row['Length']); ?>"
                  data-main-actor="<?php echo htmlspecialchars($row['Main_Actor']); ?>"
                  data-poster-path="<?php echo htmlspecialchars($row['Poster_Path']); ?>">
                  <img class="movie-poster" src="<?php echo htmlspecialchars($row['Poster_Path']); ?>" alt="<?php echo htmlspecialchars($row['Movie_Name']); ?>">
                </div>
            <?php endwhile;

              $stmt->close();
            } ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Hover Box -->
  <div id="hover-box" class="hover-box"></div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const hoverBox = document.getElementById('hover-box');

      // Show hover box when clicking a movie poster
      document.querySelectorAll('.movie-card').forEach(card => {
        card.addEventListener('click', (e) => {
          const {
            movieId,
            movieName,
            description,
            price,
            releasedDate,
            length,
            mainActor,
            posterPath
          } = card.dataset;

          // Populate hover box with movie details
          hoverBox.innerHTML = `
        <img src="${posterPath}" alt="${movieName}" style="width: 100%; height: auto; margin-bottom: 10px;">
        <h3>${movieName}</h3>
        <p><strong>Description:</strong> ${description}</p>
        <p class="price"><strong>Price:</strong> $${price}</p>
        <p><strong>Release Date:</strong> ${releasedDate}</p>
        <p><strong>Length:</strong> ${length} mins</p>
        <p><strong>Main Actor:</strong> ${mainActor}</p>
        <div class="buttons">
          <button onclick="addToCart(${movieId})">Add to Cart</button>
          <button onclick="addToWishlist(${movieId})">Add to Wishlist</button>
        </div>
      `;

          // Position the hover box
          hoverBox.style.top = `${e.clientY + window.scrollY}px`;
          hoverBox.style.left = `${e.clientX}px`;
          hoverBox.style.display = 'block';
        });
      });

      // Hide hover box when clicking outside
      document.addEventListener('click', (e) => {
        if (!e.target.closest('.movie-card') && !e.target.closest('#hover-box')) {
          hoverBox.style.display = 'none';
        }
      });
    });

    function addToCart(movieId) {
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        },
        body: JSON.stringify({
        movieId
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
        alert('Movie successfully added to the cart!');
        } else {
        alert(`Error: ${data.message}`);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the movie to the cart.');
      });
    }



    function addToWishlist(movieId) {
      alert(`Movie with ID ${movieId} added to wishlist!`);
    }
  </script>
</body>

</html>