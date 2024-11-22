<?php
session_start();
include 'database.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Process deletions
if (!empty($_POST['deleted_addresses'])) {
    $deletedAddresses = json_decode($_POST['deleted_addresses'], true);

    foreach ($deletedAddresses as $addressId) {
        $query = "DELETE FROM User_Address WHERE Address_ID = ? AND User_ID = (SELECT User_ID FROM Users WHERE Username = ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $addressId, $username);
        $stmt->execute();
        $stmt->close();
    }
}

// Process address updates
$address_ids = $_POST['address_id'] ?? [];
$cities = $_POST['city'] ?? [];
$house_addresses = $_POST['house_address'] ?? [];
$zipcodes = $_POST['zipcode'] ?? [];
$countries = $_POST['country'] ?? [];
$phone_numbers = $_POST['phone_number'] ?? [];

for ($i = 0; $i < count($cities); $i++) {
    $address_id = $address_ids[$i];
    $city = $cities[$i];
    $house_address = $house_addresses[$i];
    $zipcode = $zipcodes[$i];
    $country = $countries[$i];
    $phone_number = $phone_numbers[$i];

    if (!empty($address_id)) {
        // Update existing address
        $query = "UPDATE User_Address SET City = ?, House_Address = ?, Zipcode = ?, Country = ?, Phone_number = ? WHERE Address_ID = ? AND User_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $city, $house_address, $zipcode, $country, $phone_number, $address_id, $user_id);
    } else {
        // Add new address
        $query = "INSERT INTO User_Address (User_ID, City, House_Address, Zipcode, Country, Phone_number) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssss", $user_id, $city, $house_address, $zipcode, $country, $phone_number);
    }
    $stmt->execute();
    $stmt->close();
}

// Redirect back to profile
header('Location: userprofile.php');
exit;
?>
