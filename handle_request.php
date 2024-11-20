<?php
include 'database.php';
session_start();
ob_start(); // Start output buffering

echo "<h2>Debugging Output</h2>"; // Debugging Header

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<p>Form submitted via POST method.</p>";

    // Validate form inputs
    $errors = [];
    if (empty($_POST['username'])) $errors[] = "Username is required.";
    if (empty($_POST['f_name'])) $errors[] = "First Name is required.";
    if (empty($_POST['l_name'])) $errors[] = "Last Name is required.";
    if (empty($_POST['password'])) $errors[] = "Password is required.";
    if (empty($_POST['email_address'])) $errors[] = "Email Address is required.";
    if (empty($_POST['phone_number'])) $errors[] = "Phone Number is required.";

    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        exit(); // Stop execution
    }

    // Sanitize inputs
    $username = $_POST['username'];
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $email_address = $_POST['email_address'];
    $phone_number = $_POST['phone_number'];

    echo "<p>Sanitized Inputs:</p>";
    echo "<ul>";
    echo "<li>Username: $username</li>";
    echo "<li>First Name: $f_name</li>";
    echo "<li>Last Name: $l_name</li>";
    echo "<li>Email Address: $email_address</li>";
    echo "<li>Phone Number: $phone_number</li>";
    echo "</ul>";

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO Users (Username, F_Name, L_Name, Password, Email_Address, Phone_number) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("<p style='color:red;'>SQL Prepare failed: {$conn->error}</p>");
    } else {
        echo "<p>SQL Prepare successful.</p>";
    }

    $stmt->bind_param("ssssss", $username, $f_name, $l_name, $password, $email_address, $phone_number);

    // Execute statement
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Data inserted successfully.</p>";
        $_SESSION['username'] = $username;
        echo "<p>Redirecting to Mainpage.html...</p>";
        header("Location: Mainpage.html");
        $stmt->close();
        exit();
    } else {
        $stmt->close();
        die("<p style='color:red;'>SQL Execution Error: {$stmt->error}</p>");
    }
}

// Display current data in the `Users` table for verification
echo "<h3>Existing Data in Users Table:</h3>";

$result = $conn->query("SELECT * FROM Users");
if ($result && $result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Email Address</th><th>Phone Number</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['Username']}</td>";
        echo "<td>{$row['F_Name']}</td>";
        echo "<td>{$row['L_Name']}</td>";
        echo "<td>{$row['Email_Address']}</td>";
        echo "<td>{$row['Phone_number']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No data found in Users table.</p>";
}

$conn->close();
?>
