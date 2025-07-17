<?php
include 'conn.php';




    // Validate input
    $productId=$_REQUEST['productId'];
    
    $feature_text =$_REQUEST['feature_text'];

    if (!$productId || empty($feature_text) ) {
        throw new Exception('Invalid input data');
    }

    // Use prepared statement
    $stmt = $conn->prepare("INSERT INTO tbl_features (feature_text, product_id) VALUES (?, ?)");
    $stmt->bind_param("si", $feature_text, $productId);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert data: ' . $stmt->error);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Detail added successfully!'
    ]);

$conn->close();
?>