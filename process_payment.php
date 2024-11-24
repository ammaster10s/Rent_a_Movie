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

// Retrieve the order_id from the POST request
if (!isset($_POST['order_id']) || !is_numeric($_POST['order_id'])) {
    $errors[] = "Order ID is missing or invalid.";
    $_SESSION['errors'] = $errors;
    header('Location: debug.php');
    exit();
}

$order_id = (int) $_POST['order_id'];

// Handle address
$use_existing_address = isset($_POST['use_existing_address']);
$save_new_address = isset($_POST['save_new_address']);
$temporary_address = !$use_existing_address; // Determine if this is a temporary address

$city = htmlspecialchars($_POST['city'] ?? '');
$house_address = htmlspecialchars($_POST['address'] ?? '');
$zipcode = htmlspecialchars($_POST['zip'] ?? '');
$country = htmlspecialchars($_POST['country'] ?? '');
$phone_number = htmlspecialchars($_POST['phone'] ?? '');

if ($use_existing_address) {
    // Fetch the selected address_id
    $address_id = $_POST['existing_address'] ?? null;

    if (empty($address_id)) {
        $errors[] = "No existing address selected.";
    } else {
        // Retrieve address details from the database
        $query = "SELECT City, House_Address, Zipcode, Country, Phone_number FROM User_Address WHERE Address_ID = ? AND User_ID = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            $errors[] = "Failed to prepare statement for fetching address: " . $conn->error;
        } else {
            $stmt->bind_param('ii', $address_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $address = $result->fetch_assoc();
                $city = $address['City'];
                $house_address = $address['House_Address'];
                $zipcode = $address['Zipcode'];
                $country = $address['Country'];
                $phone_number = $address['Phone_number'];
            } else {
                $errors[] = "The selected address could not be found.";
            }

            $stmt->close();
        }
    }
} else {
    // Validate new address fields
    if (empty($country)) $errors[] = "Country is required.";
    if (empty($house_address)) $errors[] = "Address is required.";
    if (empty($zipcode)) $errors[] = "Zip code is required.";
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

// Insert payment directly with address details
$stmt = $conn->prepare("
    INSERT INTO Payment (
        CreditCard_Number, CVC, Expiration_Date, User_ID, 
        City, House_Address, Zipcode, Country, Phone_number, Payment_Date
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
");
if (!$stmt) {
    error_log("SQL Prepare failed for payment insertion: " . $conn->error);
    die("SQL Prepare failed for payment insertion.");
}
$stmt->bind_param(
    'sssssssss',
    $credit_card_number,
    $cvv,
    $expiry_date_mysql,
    $user_id,
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

// Update order
$stmt = $conn->prepare("
    UPDATE Orders 
    SET Payment_ID = ?, Status = 'Completed'
    WHERE Order_ID = ? AND User_ID = ?
");
if (!$stmt) {
    error_log("SQL Prepare failed for order update: " . $conn->error);
    die("SQL Prepare failed for order update.");
}
$stmt->bind_param('iii', $payment_id, $order_id, $user_id);
if (!$stmt->execute()) {
    error_log("SQL Execute failed for order update: " . $stmt->error);
    die("SQL Execute failed for order update.");
}
$stmt->close();

// Insert into Borrow_History
$stmt = $conn->prepare("
    INSERT INTO Borrow_History (Payment_ID)
    VALUES (?)
");
if (!$stmt) {
    error_log("SQL Prepare failed for Borrow_History insertion: " . $conn->error);
    die("SQL Prepare failed for Borrow_History insertion.");
}
$stmt->bind_param('i', $payment_id);
if (!$stmt->execute()) {
    error_log("SQL Execute failed for Borrow_History insertion: " . $stmt->error);
    die("SQL Execute failed for Borrow_History insertion.");
}
$borrow_id = $stmt->insert_id; // Capture the Borrow_ID
$stmt->close();

// Insert into User_Access_BorrowHistory
$stmt = $conn->prepare("
    INSERT INTO User_Access_BorrowHistory (Borrow_ID, User_ID)
    VALUES (?, ?)
");
if (!$stmt) {
    error_log("SQL Prepare failed for User_Access_BorrowHistory insertion: " . $conn->error);
    die("SQL Prepare failed for User_Access_BorrowHistory insertion.");
}
$stmt->bind_param('ii', $borrow_id, $user_id);
if (!$stmt->execute()) {
    error_log("SQL Execute failed for User_Access_BorrowHistory insertion: " . $stmt->error);
    die("SQL Execute failed for User_Access_BorrowHistory insertion.");
}
$stmt->close();

// Insert into Place_Order
$stmt = $conn->prepare("
    INSERT INTO Place_Order (Order_ID, User_ID)
    VALUES (?, ?)
");
if (!$stmt) {
    error_log("SQL Prepare failed for Place_Order insertion: " . $conn->error);
    die("SQL Prepare failed for Place_Order insertion.");
}
$stmt->bind_param('ii', $order_id, $user_id);
if (!$stmt->execute()) {
    error_log("SQL Execute failed for Place_Order insertion: " . $stmt->error);
    die("SQL Execute failed for Place_Order insertion.");
}
$stmt->close();

$_SESSION['success_message'] = "Payment processed and order completed successfully!";
header('Location: Mainpage.php');
exit();

