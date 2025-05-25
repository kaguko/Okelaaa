<?php
header("Content-Type: application/json; charset=UTF-8");
include("connect.php");

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data["id"])) {
    echo json_encode(["success" => false, "message" => "Không nhận được input JSON"]);
    exit;
}
$id = $data["id"];

$stmt = $conn->prepare("SELECT * FROM hoa_don WHERE id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    exit;
}
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "data" => [
            "invoice" => [
                "id" => $row["id"],
                "total" => $row["total"],
                "status" => $row["status"],
                "customer_name" => $row["customer_name"] ?? "",
                "created_at" => $row["created_at"] ?? ""
            ]
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Không tìm thấy hóa đơn"]);
}
$stmt->close();
$conn->close();
?>
