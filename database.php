<?php
$servername = "localhost:8889";  // ใช้ 8889 ละกัน
$username = "root"; 
$password = "root";
$dbname = "MovieRentalSystem";  // ใช้อันนี้นะ

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>