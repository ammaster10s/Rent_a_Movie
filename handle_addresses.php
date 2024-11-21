<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['username'];
$query = "SELECT User_ID FROM Users WHERE Username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$user_id = $user['User_ID'];
$stmt->close();

// Update addresses
$address_ids = $_POST['address_id'];
$labels = $_POST['address_label'];
$addresses = $_POST['address'];
$zips = $_POST['zip'];
$countries = $_POST['country'];
$phones = $_POST['phone'];

foreach ($labels as $index => $label) {
    $address_id = $address_ids[$index];
    $address = $addresses[$index];
    $zip = $zips[$index];
    $country = $countries[$index];
    $phone = $phones[$index];

    if (empty($address_id)) {
        // New address
        $query = "INSERT INTO User_Address (User_ID, address_label, address, zip, country, phone) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssss", $user_id, $label, $address, $zip, $country, $phone);
    } else {
        // Update existing address
        $query = "UPDATE User_Address SET address_label = ?, address = ?, zip = ?, country = ?, phone = ? WHERE id = ? AND User_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssisi", $label, $address, $zip, $country, $phone, $address_id, $user_id);
    }
    $stmt->execute();
    $stmt->close();
}

header('Location: profile.php');
exit;
?>
