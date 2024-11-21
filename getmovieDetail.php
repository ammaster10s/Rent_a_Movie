<?php
include 'database.php';

if (isset($_GET['movie_id'])) {
    $movieId = $_GET['movie_id'];

    // Query to get movie details from the Movie table
    $query = "SELECT * FROM Movie WHERE Movie_ID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Error preparing statement.";
        exit;
    }
    $stmt->bind_param("i", $movieId);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();

        // Output movie details
        echo "<h1>" . htmlspecialchars($movie['Movie_Name']) . "</h1>";
        echo "<img src='" . htmlspecialchars($movie['Poster_Path']) . "' alt='" . htmlspecialchars($movie['Movie_Name']) . "' class='movie-poster-modal'>";
        echo "<p><strong>Main Actor:</strong> " . htmlspecialchars($movie['Main_Actor']) . "</p>";
        echo "<p><strong>Release Date:</strong> " . htmlspecialchars($movie['Released_date']) . "</p>";
        echo "<p><strong>Price:</strong> $" . htmlspecialchars($movie['Price']) . "</p>";
        echo "<p><strong>Length:</strong> " . htmlspecialchars($movie['Length']) . " minutes</p>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($movie['Description']) . "</p>";
    } else {
        echo "Movie not found.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
