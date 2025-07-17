<?php
include 'conn.php';

$featureId = $_POST['featureId'] ?? 0;

$sqlDelete = "DELETE FROM tbl_features WHERE feature_id   = ?";
$stmt = $conn->prepare($sqlDelete);
$stmt->bind_param("i", $featureId);
$success = $stmt->execute();

echo json_encode(["success" => $success, "message" => $success ? "Detail deleted successfully" : "Error deleting detail"]);
?>