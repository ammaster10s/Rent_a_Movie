<?php
session_start();
include 'database.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Results - Renting Movie System</title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include 'navigate.php'; ?>

    <div class="main-container">
        <div class="content">
            <h2 class="text-wrapper-mainpage">Search Results</h2>
            <div class="image-row">
                <?php
                // Check if a search query is provided
                $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

                if (empty($searchQuery)) {
                    echo "<p>Please enter a search term.</p>";
                } else {
                    // Fetch movies based on the search query
                    $query = "SELECT Movie_ID, Movie_Name, Poster_Path, Description, Price, Released_date, Length, Main_Actor FROM Movie WHERE Movie_Name LIKE ?";
                    $stmt = $conn->prepare($query);

                    if ($stmt) {
                        $searchTerm = '%' . $searchQuery . '%';
                        $stmt->bind_param("s", $searchTerm);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // Loop through and display movies
                            while ($row = $result->fetch_assoc()) {
                                echo '
                                    <div class="movie-card" data-movie-id="' . $row['Movie_ID'] . '"
                                         data-movie-name="' . htmlspecialchars($row['Movie_Name']) . '"
                                         data-description="' . htmlspecialchars($row['Description']) . '"
                                         data-price="' . htmlspecialchars($row['Price']) . '"
                                         data-released-date="' . htmlspecialchars($row['Released_date']) . '"
                                         data-length="' . htmlspecialchars($row['Length']) . '"
                                         data-main-actor="' . htmlspecialchars($row['Main_Actor']) . '"
                                         data-poster-path="' . htmlspecialchars($row['Poster_Path']) . '">
                                        <img class="movie-poster" src="' . htmlspecialchars($row['Poster_Path']) . '" 
                                             alt="' . htmlspecialchars($row['Movie_Name']) . '">
                                    </div>
                                ';
                            }
                        } else {
                            echo '<p style="color: white;">No movies found matching your search query.</p>';

                        }

                        $stmt->close();
                    } else {
                       
                        echo '<p style="color: white;">Error preparing the query.</p>';
                    }
                }
                ?>
            </div>
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
                        <div class="hover-content">
                            <img src="${posterPath}" alt="${movieName}" class="hover-poster">
                            <div class="hover-details">
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
                            </div>
                        </div>
                    `;

                    // Center the hover box on the page
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
                body: JSON.stringify({ movieId }),
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
            fetch('add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ movieId }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Movie successfully added to the wishlist!');
                    } else {
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the movie to the wishlist.');
                });
        }
    </script>
</body>

</html>
