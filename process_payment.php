<?php
session_start();
include 'database.php';

$errors = [];

// Validate the user's session
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    $errors[] = "Invalid session. Please log in again.";
    $_SESSION['errors'] = $errors;
    header('Location: Mainpage.php');
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// Handle address
$use_existing_address = isset($_POST['use_existing_address']);
$save_new_address = isset($_POST['save_new_address']);
$address_id = $_POST['existing_address'] ?? null;

if (!$use_existing_address) {
    // Save the new address (temporarily or permanently)
    $country = htmlspecialchars($_POST['country'] ?? '');
    $address = htmlspecialchars($_POST['address'] ?? '');
    $zip = htmlspecialchars($_POST['zip'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');

    if (empty($country)) $errors[] = "Country is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($zip)) $errors[] = "Zip code is required.";

    if (empty($errors)) {
        if ($save_new_address) {
            // Save the address to User_Address table
            $stmt = $conn->prepare("
                INSERT INTO User_Address (User_ID, Country, House_Address, Zipcode, Phone_Number)
                VALUES (?, ?, ?, ?, ?)
            ");
            if (!$stmt) {
                die("SQL Prepare failed for address insertion: " . $conn->error);
            }
            $stmt->bind_param('issss', $user_id, $country, $address, $zip, $phone);
            $stmt->execute();
            $address_id = $stmt->insert_id;
            $stmt->close();
        } else {
            // Use a temporary address (not saved)
            $address_id = null; // Indicating no permanent Address_ID
        }
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: payment_form.php');
        exit();
    }
} else {
    // Validate existing address selection
    if (empty($address_id)) {
        $errors[] = "No address selected.";
        $_SESSION['errors'] = $errors;
        header('Location: payment_form.php');
        exit();
    }
}

// Handle credit card
$credit_card_number = $_POST['credit_card_number'] ?? '';
$expiry_date = $_POST['expiry_date'] ?? '';
$cvv = $_POST['cvv'] ?? '';

if (empty($credit_card_number)) $errors[] = "Credit card number is required.";
elseif (!preg_match('/^(4|5[1-5])/', $credit_card_number)) $errors[] = "Credit card must be Visa or MasterCard.";
elseif (strlen($credit_card_number) !== 16 || !is_numeric($credit_card_number)) $errors[] = "Credit card number must be 16 digits.";

if (empty($expiry_date)) $errors[] = "Expiry date is required.";
elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry_date)) $errors[] = "Expiry date must be in MM/YY format.";

if (empty($cvv)) $errors[] = "CVV is required.";
elseif (!is_numeric($cvv) || strlen($cvv) !== 3) $errors[] = "CVV must be a 3-digit number.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: payment_form.php');
    exit();
}

// Insert the payment record
$stmt = $conn->prepare("
    INSERT INTO Payment (CreditCard_Number, CVC, Expiration_Date, User_ID, Address_ID, Payment_Date)
    VALUES (?, ?, ?, ?, ?, NOW())
");
if (!$stmt) {
    die("SQL Prepare failed for payment insertion: " . $conn->error);
}

$stmt->bind_param('ssssi', $credit_card_number, $cvv, $expiry_date, $user_id, $address_id);
if (!$stmt->execute()) {
    die("SQL Execute failed for payment insertion: " . $stmt->error);
}
$payment_id = $stmt->insert_id;
$stmt->close();

// Update the order as completed
$stmt = $conn->prepare("UPDATE Orders SET Status = 'Completed', Address_ID = ? WHERE User_ID = ? AND Status = 'Pending'");
if (!$stmt) {
    die("SQL Prepare failed for order update: " . $conn->error);
}
$stmt->bind_param('ii', $address_id, $user_id);
if (!$stmt->execute()) {
    die("SQL Execute failed for order update: " . $stmt->error);
}
$stmt->close();

// Redirect to success page with success message
$_SESSION['success_message'] = "Payment processed successfully!";
header('Location: Mainpage.php');
exit();
?>
