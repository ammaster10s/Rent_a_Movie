<?php
include 'database.php';
session_start();

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$userId = $_SESSION['user_id'];
$movieId = filter_input(INPUT_GET, 'movie_id', FILTER_VALIDATE_INT);

if (!$movieId) {
    echo json_encode(['success' => false, 'message' => 'Invalid Movie ID.']);
    exit;
}

$conn->begin_transaction();

try {
    // Check if the movie exists in the user's active order
    $query = "
        SELECT oc.Order_ID 
        FROM Order_Contain oc
        INNER JOIN Orders o ON oc.Order_ID = o.Order_ID
        WHERE o.User_ID = ? AND o.Payment_ID IS NULL AND oc.Movie_ID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $userId, $movieId);
    $stmt->execute();
    $stmt->bind_result($orderId);
    $stmt->fetch();
    $stmt->close();

    if (!$orderId) {
        echo json_encode(['success' => false, 'message' => 'Movie not found in the active order.']);
        $conn->rollback();
        exit;
    }

    // Delete the movie from the Order_Contain table
    $query = "DELETE FROM Order_Contain WHERE Order_ID = ? AND Movie_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $orderId, $movieId);

    if (!$stmt->execute()) {
        throw new Exception('Failed to remove the movie from the cart.');
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Movie successfully removed from the cart.']);
} catch (Exception $e) {
    $conn->rollback();
    error_log("Error: " . $e->getMessage()); // Log the error for debugging
    echo json_encode(['success' => false, 'message' => 'An error occurred while processing your request.']);
}

$conn->close();
?>
