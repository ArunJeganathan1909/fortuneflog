<?php
include "backend/conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['pTitle'];
    $description = $_POST['pDescription'];
    $price = $_POST['pPrice'];
    $age = $_POST['pAge'];
    $type = $_POST['pType'];
    $contactName = $_POST['pContactName'];
    $contactAddress = $_POST['pContactAddress'];
    $email = $_POST['pEmail'];
    $phone = $_POST['pPhone'];
    $p_categories = $_POST['pCatogory'];


    $stmt = $conn->prepare("INSERT INTO tbl_products_customers
        (title, description, price, age, type, contact_name, contact_address, contact_email, contact_phone,p_categories)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("ssdisssssi", $title, $description, $price, $age, $type, $contactName, $contactAddress, $email, $phone,$p_categories);

    if ($stmt->execute()) {
        $productId = $stmt->insert_id;

        $allImagesUploaded = true;
        foreach ($_FILES['pImages']['tmp_name'] as $key => $tmpName) {
            $imageName = $_FILES['pImages']['name'][$key];
            $imageTmp = $_FILES['pImages']['tmp_name'][$key];

            $targetDir = "../uploads/products/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $targetFile = $targetDir . time() . "_" . basename($imageName);

            if (move_uploaded_file($imageTmp, $targetFile)) {
                $stmtImg = $conn->prepare("INSERT INTO tbl_product_images (product_id, image_path) VALUES (?, ?)");
                $stmtImg->bind_param("is", $productId, $targetFile);
                $stmtImg->execute();
                $stmtImg->close();
            } else {
                $allImagesUploaded = false;
            }
        }

        if ($allImagesUploaded) {
            $_SESSION['message'] = "Product added successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Product added, but some images failed to upload.";
            $_SESSION['msg_type'] = "warning";
        }
    } else {
        $_SESSION['message'] = "Error adding product: " . $stmt->error;
        $_SESSION['msg_type'] = "danger";
    }
    $stmt->close();
    $conn->close();

    // Redirect to the form or dashboard page
    header("Location: ../my-account.php");
    exit();
}
?>
