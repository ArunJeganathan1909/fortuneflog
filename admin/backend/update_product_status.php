<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    $allowed_statuses = ['pending', 'approved', 'rejected'];

    if ($product_id > 0 && in_array($status, $allowed_statuses)) {
        $stmt = $conn->prepare("UPDATE tbl_products_customers SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $product_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Product status updated successfully.";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to update product status.";
            $_SESSION['msg_type'] = "danger";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Invalid data provided.";
        $_SESSION['msg_type'] = "danger";
    }
    $conn->close();

    header("Location: ../referes.php");
    exit;
} else {
    header("Location: ../referes.php");
    exit;
}
