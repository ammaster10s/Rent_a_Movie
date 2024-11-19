<?php
include 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request
    $username = $_POST['username'];
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $password = $_POST['password'];
    $email_address = $_POST['email_address'];
    $phone_number = $_POST['phone_number'];

    $sql = "INSERT INTO User (Username, F_Name, L_Name, Password, Email_Address, Phone_number) VALUES ('$username', '$f_name', '$l_name', '$password', '$email_address', '$phone_number')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username;
        header("Location: Mainpage.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request
    $sql = "SELECT * FROM User";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "User_ID: " . $row["User_ID"]. " - Username: " . $row["Username"]. " - Email: " . $row["Email_Address"]. "<br>";
        }
    } else {
        echo "0 results";
    }
}

$conn->close();
?>