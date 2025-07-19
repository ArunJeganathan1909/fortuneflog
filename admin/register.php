<?php
// Start the session if needed
include "backend/conn.php";

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input values
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Basic validation
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM tbl_customers WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        die("Email already registered.");
    }

    $checkStmt->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO tbl_customers (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

    if ($stmt->execute()) {
       header('location:../sign-in.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
