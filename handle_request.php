<?php
include 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request for user registration
    $username = $_POST['username'];
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
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
    // Handle GET request for user login
    $email = $_GET['email'];
    $password = $_GET['password'];

    $sql = "SELECT * FROM User WHERE Email_Address='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['username'] = $row['Username'];
            header("Location: Mainpage.html");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }
}

$conn->close();
?>