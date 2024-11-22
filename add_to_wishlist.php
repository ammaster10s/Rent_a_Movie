<?php
include 'database.php';
session_start();

header('Content-Type: application/json');

// Debug: Check if session is set
if (!isset($_SESSION['user_id'])) {
    error_log("User not logged in.");
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$movieId = $input['movieId'] ?? null;

// Debug: Log received data
error_log("User ID: $userId, Movie ID: $movieId");

// Check if movieId is valid
if (!$movieId) {
    error_log("Invalid Movie ID received.");
    echo json_encode(['success' => false, 'message' => 'Invalid Movie ID.']);
    exit;
}

$conn->begin_transaction();

try {
    // Debug: Validate user exists
    $query = "SELECT User_ID FROM Users WHERE User_ID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("User query prepare failed: " . $conn->error);
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($existingUserId);
    $stmt->fetch();
    $stmt->close();
    
    if (!$existingUserId) {
        error_log("User does not exist in database.");
        echo json_encode(['success' => false, 'message' => 'User does not exist.']);
        exit;
    }

    // Debug: Check if movie already exists in wishlist
    $query = "SELECT * FROM Wishlist WHERE User_ID = ? AND Movie_ID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Wishlist query prepare failed: " . $conn->error);
    }
    $stmt->bind_param('ii', $userId, $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        error_log("Movie already in wishlist.");
        echo json_encode(['success' => false, 'message' => 'Movie already in wishlist.']);
        exit;
    }
    $stmt->close();

    // Debug: Insert movie into wishlist
    $query = "INSERT INTO Wishlist (User_ID, Movie_ID) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Wishlist insert prepare failed: " . $conn->error);
    }
    $stmt->bind_param('ii', $userId, $movieId);

    if (!$stmt->execute()) {
        throw new Exception("Wishlist insert execute failed: " . $stmt->error);
    }

    $conn->commit();
    error_log("Movie successfully added to wishlist: User ID $userId, Movie ID $movieId");
    echo json_encode(['success' => true, 'message' => 'Movie successfully added to the wishlist.']);
} catch (Exception $e) {
    $conn->rollback();
    error_log("Transaction error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
