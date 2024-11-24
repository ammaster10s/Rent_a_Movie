<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Process deletions
if (!empty($_POST['deleted_addresses'])) {
    $deletedAddresses = json_decode($_POST['deleted_addresses'], true);

    foreach ($deletedAddresses as $addressId) {
        $query = "DELETE FROM User_Address WHERE Address_ID = ? AND User_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $addressId, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Process updates and inserts
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

    if (empty($city) || empty($house_address) || empty($zipcode) || empty($country)) {
        continue; // Skip invalid inputs
    }

    if (!empty($address_id)) {
        // Update existing address
        $query = "UPDATE User_Address SET City = ?, House_Address = ?, Zipcode = ?, Country = ?, Phone_number = ? WHERE Address_ID = ? AND User_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $city, $house_address, $zipcode, $country, $phone_number, $address_id, $user_id);
    } else {
        // Insert new address
        $query = "INSERT INTO User_Address (User_ID, City, House_Address, Zipcode, Country, Phone_number) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssss", $user_id, $city, $house_address, $zipcode, $country, $phone_number);
    }
    $stmt->execute();
    $stmt->close();
}

// Redirect with success message
$_SESSION['message'] = "Your changes have been saved successfully.";
header('Location: userprofile.php');
exit();

?>