<?php
// session_start();
include 'database.php';
include 'auth_check.php';

// Function to check if the user profile is complete
function isProfileComplete($username, $conn)
{
    $query = "SELECT ADDRESS_ID FROM User_Address WHERE (SELECT User_ID FROM Users WHERE Username=?)";
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

    <!-- Main Page Content -->
    <div class="mainpage">
        <div class="content">
            <p class="rent-movies-to-watch">Rent Movies to Watch on DVD &amp; Blu-ray</p>
            <p class="text-wrapper">Unlike movie streaming services, we deliver your movie to you, and you return it back to us.</p>

            <?php
            // Define ranges for categories
            $categories = [
                'Trending Movies' => [1, 6],
                'Thriller Movies' => [7, 12],
                'Sci-Fi & Fantasy Movies' => [13, 18]
            ];

            foreach ($categories as $category => $range): ?>
                <div class="categories">
                    <h2 class="text-wrapper-2"><?php echo $category; ?></h2>
                    <div class="image-row">
                        <?php
                        $query = "SELECT Movie_ID, Movie_Name, Poster_Path FROM Movie WHERE Movie_ID BETWEEN ? AND ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ii", $range[0], $range[1]);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()): ?>
                            <img
                                class="image movie-poster"
                                src="<?php echo $row['Poster_Path']; ?>"
                                alt="<?php echo htmlspecialchars($row['Movie_Name']); ?>"
                                data-movie-id="<?php echo $row['Movie_ID']; ?>" />
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal Container -->
    <div id="movie-modal" class="modal">
        <div class="modal-content">
            <span id="close-modal" class="close">&times;</span>
            <div id="movie-details"></div>
        </div>
    </div>

    <script>
        // Modal Display Logic
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('movie-modal');
            const closeModal = document.getElementById('close-modal');
            const movieDetails = document.getElementById('movie-details');

            // Handle clicking on a movie poster
            document.querySelectorAll('.movie-poster').forEach(poster => {
                poster.addEventListener('click', async (e) => {
                    const movieId = e.target.dataset.movieId;

                    // Fetch movie details
                    const response = await fetch(`getMovieDetails.php?movie_id=${movieId}`);
                    const details = await response.text();

                    // Display details in modal
                    movieDetails.innerHTML = details;
                    modal.style.display = 'block';
                });
            });

            // Handle closing the modal
            closeModal.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            // Close modal when clicking outside of the content
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>
