<?php
include 'database.php';

if (isset($_GET['movie_id'])) {
    $movie_id = intval($_GET['movie_id']);

    $query = "SELECT Movie_Name, Description, Price, Released_date, Length, Main_Actor FROM Movie WHERE Movie_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $stmt->bind_result($movie_name, $description, $price, $released_date, $length, $main_actor);
    $stmt->fetch();

    if ($movie_name) {
        echo "<h3>$movie_name</h3>";
        echo "<p><strong>Description:</strong> $description</p>";
        echo "<p><strong>Price:</strong> $$price</p>";
        echo "<p><strong>Release Date:</strong> $released_date</p>";
        echo "<p><strong>Length:</strong> $length mins</p>";
        echo "<p><strong>Main Actor:</strong> $main_actor</p>";
    } else {
        echo "Movie details not found.";
    }

    $stmt->close();
}
?>