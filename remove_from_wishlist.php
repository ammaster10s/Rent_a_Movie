<?php
include 'database.php';
include 'auth_check.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['movieId'])) {
        $movieId = $data['movieId'];
        $userId = $_SESSION['user_id'];

        $query = "DELETE FROM Wishlist WHERE Movie_ID = ? AND User_ID = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('ii', $movieId, $userId);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Movie removed from wishlist']);
            } else {
                // Log the error for debugging
                echo json_encode(['success' => false, 'message' => 'Failed to execute query: ' . $stmt->error]);
            }

            $stmt->close();
        } else {
            // Log the query preparation error
            echo json_encode(['success' => false, 'message' => 'Failed to prepare query: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input: movieId is missing']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
