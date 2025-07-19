<?php

include "backend/conn.php";

$error = '';

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $error = "You must be logged in.";
} else {
    $user_id = $_SESSION['user_id'];

    // ✅ Collect and sanitize input
    $fullname = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // ✅ Fetch current password hash from DB
    $stmt = $conn->prepare("SELECT password FROM tbl_customers WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password_hash);
    $stmt->fetch();
    $stmt->close();

    // ✅ Validate current password
    if (!password_verify($current_password, $db_password_hash)) {
        $error = "Current password is incorrect.";
    } else {
        // ✅ Update name and email
        $update_stmt = $conn->prepare("UPDATE tbl_customers SET fullname = ?, email = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $fullname, $email, $user_id);
        $update_stmt->execute();
        $update_stmt->close();

        // ✅ Handle password change (optional)
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                $error = "New passwords do not match.";
            } else {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $pwd_stmt = $conn->prepare("UPDATE tbl_customers SET password = ? WHERE id = ?");
                $pwd_stmt->bind_param("si", $new_hashed_password, $user_id);
                $pwd_stmt->execute();
                $pwd_stmt->close();
            }
        }
    }
}

// ✅ Redirect with error or success
if (!empty($error)) {
    $_SESSION['wrong_password'] = $error;
    header("Location: ../my-account.php");
    exit;
} else {
    $_SESSION['success_message'] = "Account updated successfully.";
    header("Location: ../my-account.php");
    exit;
}
?>
