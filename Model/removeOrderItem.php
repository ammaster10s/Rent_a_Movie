<?php
header('Content-Type: application/json');
include '../database.php'; // Adjust the path to the correct location
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = isset($_POST['movie_id']) ? (int)$_POST['movie_id'] : null;

if (!$movie_id) {
    echo json_encode(['success' => false, 'message' => 'Movie ID is missing.']);
    exit;
}

try {
    // Begin a transaction
    $conn->begin_transaction();

    // Check if the order exists
    $stmt = $conn->prepare("SELECT Order_ID FROM Orders WHERE User_ID = ? AND Payment_ID IS NULL");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($order_id);
    $stmt->fetch();
    $stmt->close();

    if (!$order_id) {
        echo json_encode(['success' => false, 'message' => 'No active order found.']);
        $conn->rollback();
        exit;
    }

    // Remove the movie from the order
    $stmt = $conn->prepare("DELETE FROM Order_Contain WHERE Order_ID = ? AND Movie_ID = ?");
    $stmt->bind_param('ii', $order_id, $movie_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Movie removed successfully.']);
        // Redirect back to order summary (if needed)
        header('Location: ../order.php');
        exit;
    } else {
        throw new Exception('Failed to remove movie from order.');
    }
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
