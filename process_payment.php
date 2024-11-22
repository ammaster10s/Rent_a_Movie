<?php
session_start();
include 'database.php';

// Validate the user's session
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    die("Invalid session. Please log in again.");
}

$user_id = (int) $_SESSION['user_id'];

// Handle address
$use_existing_address = isset($_POST['use_existing_address']);
$address_id = $_POST['existing_address'] ?? null;

if (!$use_existing_address) {
    // Save the new address
    $country = $_POST['country'] ?? '';
    $address = $_POST['address'] ?? '';
    $zip = $_POST['zip'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (!empty($country) && !empty($address) && !empty($zip)) {
        $stmt = $conn->prepare("
            INSERT INTO User_Address (User_ID, Country, House_Address, Zipcode, Phone_Number)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('issss', $user_id, $country, $address, $zip, $phone);
        $stmt->execute();
        $address_id = $conn->insert_id;
        $stmt->close();
    } else {
        die("Incomplete address details.");
    }
}

// Handle credit card
$credit_card_number = $_POST['credit_card_number'] ?? '';
$expiry_date = $_POST['expiry_date'] ?? '';
$cvv = $_POST['cvv'] ?? '';

if (empty($credit_card_number) || empty($expiry_date) || empty($cvv)) {
    die("Credit card details are required.");
}

// Validate and process payment (Mocked here)
// In a real-world scenario, integrate with a payment gateway like Stripe or PayPal
if (strlen($credit_card_number) < 16 || !is_numeric($cvv)) {
    die("Invalid credit card details.");
}

// Update the order as completed
$stmt = $conn->prepare("UPDATE Orders SET Status = 'Completed', Address_ID = ? WHERE User_ID = ? AND Status = 'Pending'");
$stmt->bind_param('ii', $address_id, $user_id);
$stmt->execute();
$stmt->close();

header('Location: success.php');
?>
