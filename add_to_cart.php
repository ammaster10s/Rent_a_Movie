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
$input = json_decode(file_get_contents('php://input'), true);
$movieId = $input['movieId'] ?? null;

if (!$movieId) {
    echo json_encode(['success' => false, 'message' => 'Invalid Movie ID.']);
    exit;
}

// Begin database transaction
$conn->begin_transaction();

try {
    // Check if the user exists
    $query = "SELECT User_ID FROM Users WHERE User_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($existingUserId);
    $stmt->fetch();
    $stmt->close();
    
    if (!$existingUserId) {
        echo json_encode(['success' => false, 'message' => 'User does not exist.']);
        exit;
    }
    
    // Check if an order already exists for the user
    $query = "SELECT Order_ID FROM Orders WHERE User_ID = ? AND Payment_ID IS NULL";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($orderId);
    $stmt->fetch();
    $stmt->close();

    if (!$orderId) {
        // Create a new order with User_ID
        $query = "INSERT INTO Orders (User_ID, Payment_ID) VALUES (?, NULL)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();
    }

    // Add the movie to the Order_Contain table
    $query = "INSERT INTO Order_Contain (Order_ID, Movie_ID) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $orderId, $movieId);

    if (!$stmt->execute()) {
        throw new Exception('Failed to add the movie to the cart.');
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Movie successfully added to the cart.']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>