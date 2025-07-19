<?php

include "backend/conn.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get submitted form data
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate fields
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // Prepare and execute select query
        $stmt = $conn->prepare("SELECT id, fullname, password FROM tbl_customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $fullname, $hashed_password);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_fullname'] = $fullname;

                // Redirect to homepage
                header("Location: ../index.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that email.";
        }

        $stmt->close();
    }
}

$conn->close();

// If there's an error, store it in session and redirect back
if (!empty($error)) {
    $_SESSION['login_error'] = $error;
    header("Location: ../sign-in.php");
    exit;
}
?>
