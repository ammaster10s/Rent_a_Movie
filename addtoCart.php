<?php
session_start();
include 'database.php';

$response = ['success' => false, 'message' => ''];

if (isset($_GET['movie_id'])) {
    $movie_id = intval($_GET['movie_id']);
    $user_id = $_SESSION['user_id']; // Ensure user is logged in

    // Check if the movie is already in the cart
    $check_query = "SELECT * FROM Cart WHERE User_ID = ? AND Movie_ID = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['message'] = 'This movie is already in your cart.';
    } else {
        // Add the movie to the cart
        $insert_query = "INSERT INTO Cart (User_ID, Movie_ID) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $user_id, $movie_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Movie successfully added to your cart.';
        } else {
            $response['message'] = 'Failed to add movie to the cart.';
        }
    }

    $stmt->close();
} else {
    $response['message'] = 'Invalid movie ID.';
}

echo json_encode($response);
?>
