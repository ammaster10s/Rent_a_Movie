<?php
// Include database connection
include 'database.php';

// Check if a movie name is provided
if (isset($_GET['movie'])) {
    $movieName = urldecode($_GET['movie']); // Decode the movie name from the URL

    // Query to get movie details
    $query = "SELECT * FROM Movie WHERE Movie_Name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $movieName);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc(); // Fetch the movie details
    } else {
        echo "Movie not found.";
        exit;
    }
} else {
    echo "No movie selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['Movie_Name']); ?></title>
    <link rel="stylesheet" href="globals.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navigate.php'; ?>

    <div class="movie-details">
        <h1><?php echo htmlspecialchars($movie['Movie_Name']); ?></h1>
        <img src="<?php echo htmlspecialchars($movie['Poster_Path']); ?>" alt="<?php echo htmlspecialchars($movie['Movie_Name']); ?>" class="movie-poster">
        <p><strong>Main Actor:</strong> <?php echo htmlspecialchars($movie['Main_Actor']); ?></p>
        <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['Released_date']); ?></p>
        <p><strong>Price:</strong> $<?php echo htmlspecialchars($movie['Price']); ?></p>
        <p><strong>Length:</strong> <?php echo htmlspecialchars($movie['Length']); ?> minutes</p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($movie['Description']); ?></p>
    </div>
</body>

</html>
