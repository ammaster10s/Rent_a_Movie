<?php
include 'database.php';
session_start();
ob_start(); // Start output buffering
// kuy
// Debugging output
echo "<h2>Debugging Output</h2>";

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
    
    echo "<p>Sanitized Inputs:</p>";
    echo "<ul>";
    echo "<li>Username: $username</li>";
    echo "<li>First Name: $f_name</li>";
    echo "<li>Last Name: $l_name</li>";
    echo "<li>Email Address: $email_address</li>";
    echo "</ul>";

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO Users (Username, F_Name, L_Name, Password, Email_Address) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("<p style='color:red;'>SQL Prepare failed: {$conn->error}</p>");
    } else {
        echo "<p>SQL Prepare successful.</p>";
    }

    $stmt->bind_param("sssss", $username, $f_name, $l_name, $password, $email_address);

    // Execute statement
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Data inserted successfully.</p>";
        $_SESSION['username'] = $username;
        echo "<p>Redirecting to Mainpage.php...</p>";
        header("Location: Mainpage.php");
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
    echo "<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Email Address</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['Username']}</td>";
        echo "<td>{$row['F_Name']}</td>";
        echo "<td>{$row['L_Name']}</td>";
        echo "<td>{$row['Email_Address']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No data found in Users table.</p>";
}

// Display all databases on the system
echo "<h3>All Databases on the System:</h3>";
$result = $conn->query("SHOW DATABASES");
if ($result && $result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['Database']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No databases found.</p>";
}

$conn->close();
?>