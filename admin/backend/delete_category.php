<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $catId = $_POST["cat_id"];

    // Check if category is in use by any product
    $sqlProd = "SELECT 1 FROM tbl_products WHERE p_categories = ?";
    $stmtProd = $conn->prepare($sqlProd);
    $stmtProd->bind_param("i", $catId);
    $stmtProd->execute();
    $stmtProd->store_result();

    if ($stmtProd->num_rows > 0) {
        echo "<script>alert('Category is already in use'); window.history.back();</script>";
        $stmtProd->close();
    } else {
        $stmtProd->close();

        // Get image filename before deletion
        $sqlImage = "SELECT cat_image FROM tbl_categories WHERE cat_id = ?";
        $stmtImage = $conn->prepare($sqlImage);
        $stmtImage->bind_param("i", $catId);
        $stmtImage->execute();
        $stmtImage->bind_result($imageFile);
        $stmtImage->fetch();
        $stmtImage->close();

        // Delete category
        $sqlDelete = "DELETE FROM tbl_categories WHERE cat_id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $catId);

        if ($stmtDelete->execute()) {
            // Delete the image file if it exists
            if (!empty($imageFile)) {
                $imagePath = "../uploads/categories/" . $imageFile;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            echo "<script>window.location.href='../categories.php';</script>";
        } else {
            echo "<script>alert('Failed to delete category!'); window.history.back();</script>";
        }

        $stmtDelete->close();
    }
}
$conn->close();
?>
