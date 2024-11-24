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

        // Check if the email already exists
        $email_check_query = "SELECT Email_Address FROM Users WHERE Email_Address = ?";
        $stmt = $conn->prepare($email_check_query);

        if (!$stmt) {
            die("SQL Prepare failed: {$conn->error}");
        }

        $stmt->bind_param("s", $email_address);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists
            $errors[] = "The email address is already in use. Please choose another email.";
            $_SESSION['errors'] = $errors;
            $stmt->close();
            header("Location: Signup.php");
            exit();
        }

        $stmt->close();

        // Check if the username already exists
        $email_check_query = "SELECT Username FROM Users WHERE Username = ?";
        $stmt = $conn->prepare($email_check_query);

        if (!$stmt) {
            die("SQL Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists
            $errors[] = "The username is already in use. Please choose another username.";
            $_SESSION['errors'] = $errors;
            $stmt->close();
            header("Location: Signup.php");
            exit();
        }

        $stmt->close();

        // Prepare SQL statement for registration
        $register_query = "INSERT INTO Users (Username, F_Name, L_Name, Password, Email_Address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($register_query);

        if (!$stmt) {
            die("SQL Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssss", $username, $f_name, $l_name, $password, $email_address);

        // Execute statement
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $stmt->insert_id; // Set user_id in session
            $stmt->close();
            header("Location: Mainpage.php");
            exit();
        } else {
            $_SESSION['errors'] = ["SQL Execution Error: {$stmt->error}"];
            $stmt->close();
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
        $stmt = $conn->prepare("SELECT * FROM Users WHERE Email_Address=? OR Username=?");
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
                $stmt->close();
                header("Location: Mainpage.php");
                exit();
            } else {
                $errors[] = "Invalid password.";
            }
        } else {
            $errors[] = "No user found with this email.";
        }

        // If there are errors during login
        $_SESSION['errors'] = $errors;
        $stmt->close();
        header("Location: Login.php");
        exit();
    }
}
