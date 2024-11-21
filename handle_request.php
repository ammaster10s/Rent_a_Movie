<?php
include 'database.php';
session_start();

$errors = []; // Array to store error messages

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        // Handle registration

        // Validate form inputs
        if (empty($_POST['username'])) $errors[] = "Username is required.";
        if (empty($_POST['f_name'])) $errors[] = "First Name is required.";
        if (empty($_POST['l_name'])) $errors[] = "Last Name is required.";
        if (empty($_POST['password'])) $errors[] = "Password is required.";
        if (empty($_POST['email_address'])) $errors[] = "Email Address is required.";

        // If there are errors, redirect with error messages
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: Signup.php");
            exit();
        }

        // Sanitize inputs
        $username = htmlspecialchars($_POST['username']);
        $f_name = htmlspecialchars($_POST['f_name']);
        $l_name = htmlspecialchars($_POST['l_name']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $email_address = htmlspecialchars($_POST['email_address']);

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO Users (Username, F_Name, L_Name, Password, Email_Address) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("SQL Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssss", $username, $f_name, $l_name, $password, $email_address);

        // Execute statement
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header("Location: Mainpage.php");
            exit();
        } else {
            $_SESSION['errors'] = ["SQL Execution Error: " . $stmt->error];
            header("Location: Signup.php");
            exit();
        }
    } elseif (isset($_POST['login'])) {
        // Handle login

        // Validate form inputs
        if (empty($_POST['email'])) $errors[] = "Email is required.";
        if (empty($_POST['password'])) $errors[] = "Password is required.";

        // If there are errors, redirect with error messages
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: Login.php");
            exit();
        }

        // Sanitize inputs
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];

        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT * FROM Users WHERE Email_Address=? or Username=?");
        if (!$stmt) {
            die("SQL Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['Password'])) {
                session_regenerate_id(true); // Secure the session
                $_SESSION['username'] = $row['Username'];
                $_SESSION['user_id'] = $row['User_ID'];
                header("Location: Mainpage.php");
                exit();
            } else {
                $_SESSION['errors'] = ["Invalid password."];
                header("Location: Login.php");
                exit();
            }
        } else {
            $_SESSION['errors'] = ["No user found with this email."];
            header("Location: Login.php");
            exit();
        }
    }
}
?>
