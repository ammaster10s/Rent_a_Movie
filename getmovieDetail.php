
<?php

include 'database.php';



if (isset($_GET['movie_id'])) {

    $movie_id = $_GET['movie_id'];

    $query = "SELECT * FROM Movie WHERE Movie_ID = ?";

    $stmt = $conn->prepare($query);

    $stmt->bind_param("i", $movie_id);

    $stmt->execute();

    $result = $stmt->get_result();

    $movie = $result->fetch_assoc();

    $stmt->close();



    if ($movie) {
        echo "<div class='movie-info'>";
        echo "<img src='" . htmlspecialchars($movie['Poster_Path']) . "' alt='" . htmlspecialchars($movie['Movie_Name']) . "' class='movie-poster'>";
        echo "<h3>" . htmlspecialchars($movie['Movie_Name']) . "</h3>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($movie['Description']) . "</p>";
        echo "<p><strong>Price:</strong> $" . htmlspecialchars($movie['Price']) . "</p>";
        echo "<p><strong>Release Date:</strong> " . htmlspecialchars($movie['Released_date']) . "</p>";
        echo "<p><strong>Length:</strong> " . htmlspecialchars($movie['Length']) . " mins</p>";
        echo "<p><strong>Main Actor:</strong> " . htmlspecialchars($movie['Main_Actor']) . "</p>";
        echo "</div>";
    } else {
        echo "<div class='movie-info'>Movie details not found.</div>";
    }
} else {

    echo "<div class='movie-info'>Invalid movie ID.</div>";
}

?>
  