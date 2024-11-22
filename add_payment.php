<?php
include 'database.php';
// session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get POST data
$address_id = $_POST['address_id']; // Selected address ID
$credit_card_number = $_POST['credit_card_number'];
$cvc = $_POST['cvc'];
$expiration_date = $_POST['expiration_date'];
$payment_date = date('Y-m-d H:i:s');

// Fetch the address details
$query = "SELECT * FROM User_Address WHERE Address_ID = ? AND User_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $address_id, $user_id);
$stmt->execute();
$address = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$address) {
    die("Invalid address selected.");
}

// Save the payment with address details
$query = "
INSERT INTO Payment (CreditCard_Number, CVC, Expiration_Date, User_ID, Payment_Date, City, House_Address, Zipcode, Country, Phone_number)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";
$stmt = $conn->prepare($query);
$stmt->bind_param(
    "sssissssss",
    $credit_card_number,
    $cvc,
    $expiration_date,
    $user_id,
    $payment_date,
    $address['City'],
    $address['House_Address'],
    $address['Zipcode'],
    $address['Country'],
    $address['Phone_number']
);
if ($stmt->execute()) {
    echo "Payment saved successfully!";
} else {
    echo "Error saving payment: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
