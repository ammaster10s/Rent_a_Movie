<?php
session_start();
include 'database.php';

$errors = [];

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    $errors[] = "Invalid session. Please log in again.";
    $_SESSION['errors'] = $errors;
    header('Location: debug.php');
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// Handle address
$use_existing_address = isset($_POST['use_existing_address']);
$save_new_address = isset($_POST['save_new_address']);
$address_id = $use_existing_address ? ($_POST['existing_address'] ?? null) : null;

$temporary_address = false; // Default flag
$city = $house_address = $zipcode = $country = $phone_number = null;

if ($use_existing_address) {
    // Validate existing address
    if (empty($address_id)) {
        $errors[] = "No existing address selected.";
    }
} else {
    // Handle new address
    $country = htmlspecialchars($_POST['country'] ?? '');
    $house_address = htmlspecialchars($_POST['address'] ?? '');
    $zipcode = htmlspecialchars($_POST['zip'] ?? '');
    $phone_number = htmlspecialchars($_POST['phone'] ?? '');

    if (empty($country)) $errors[] = "Country is required.";
    if (empty($house_address)) $errors[] = "Address is required.";
    if (empty($zipcode)) $errors[] = "Zip code is required.";

    if (empty($errors)) {
        if ($save_new_address) {
            // Save the new address to the database
            $stmt = $conn->prepare("
                INSERT INTO User_Address (User_ID, Country, House_Address, Zipcode, Phone_Number)
                VALUES (?, ?, ?, ?, ?)
            ");
            if (!$stmt) {
                error_log("SQL Prepare failed for address insertion: " . $conn->error);
                die("SQL Prepare failed for address insertion.");
            }
            $stmt->bind_param('issss', $user_id, $country, $house_address, $zipcode, $phone_number);
            if (!$stmt->execute()) {
                error_log("SQL Execute failed for address insertion: " . $stmt->error);
                die("SQL Execute failed for address insertion.");
            }
            $address_id = $stmt->insert_id; // Use the new address ID
            $stmt->close();
        } else {
            // Treat the address as temporary
            $temporary_address = true;
        }
    }
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: debug.php');
    exit();
}

// Handle payment
$credit_card_number = preg_replace('/\D/', '', $_POST['credit_card_number'] ?? '');
$expiry_date = $_POST['expiry_date'] ?? '';
$cvv = $_POST['cvv'] ?? '';

if (!preg_match('/^\d{16}$/', $credit_card_number)) {
    $errors[] = "Invalid credit card number.";
}
if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry_date)) {
    $errors[] = "Invalid expiry date format.";
} else {
    [$month, $year] = explode('/', $expiry_date);
    $currentYear = (int) date('y');
    $currentMonth = (int) date('m');
    $fullYear = (int) ("20" . $year); // Convert YY to YYYY

    // Calculate the last day of the specified expiry month
    $expiry_last_date = strtotime("last day of $fullYear-$month");

    // Compare expiry date with current date
    if ($expiry_last_date < time()) {
        $errors[] = "The card is expired.";
    } else {
        // Convert to MySQL-compatible format
        $expiry_date_mysql = date('Y-m-t', $expiry_last_date);
    }
}



if (!preg_match('/^\d{3}$/', $cvv)) {
    $errors[] = "Invalid CVV.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: debug.php');
    exit();
}

// Insert payment
$stmt = $conn->prepare("
    INSERT INTO Payment (
        CreditCard_Number, CVC, Expiration_Date, User_ID, Address_ID, Temporary_Address, 
        City, House_Address, Zipcode, Country, Phone_number, Payment_Date
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
");
if (!$stmt) {
    error_log("SQL Prepare failed for payment insertion: " . $conn->error);
    die("SQL Prepare failed for payment insertion.");
}
$stmt->bind_param(
    'ssssiisssss',
    $credit_card_number,
    $cvv,
    $expiry_date_mysql, // Use the converted MySQL date here
    $user_id,
    $address_id,        // Null if temporary address
    $temporary_address, // TRUE or FALSE
    $city,
    $house_address,
    $zipcode,
    $country,
    $phone_number
);

if (!$stmt->execute()) {
    error_log("SQL Execute failed for payment insertion: " . $stmt->error);
    die("SQL Execute failed for payment insertion.");
}
$payment_id = $stmt->insert_id; // Capture the payment ID for the order
$stmt->close();

// Insert order
$stmt = $conn->prepare("
    INSERT INTO Orders (Payment_ID, User_ID, Status, Address_ID)
    VALUES (?, ?, 'Completed', ?)
");
if (!$stmt) {
    error_log("SQL Prepare failed for order insertion: " . $conn->error);
    die("SQL Prepare failed for order insertion.");
}
$stmt->bind_param('iii', $payment_id, $user_id, $address_id);
if (!$stmt->execute()) {
    error_log("SQL Execute failed for order insertion: " . $stmt->error);
    die("SQL Execute failed for order insertion.");
}
$stmt->close();

$_SESSION['success_message'] = "Payment processed and order completed successfully!";
header('Location: Mainpage.php');
exit();
