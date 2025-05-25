<?php
header("Content-Type: application/json; charset=UTF-8");
include("connect.php");

// Nhận id hóa đơn cần phê duyệt từ client
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data["id"])) {
    echo json_encode(["success" => false, "message" => "Thiếu id hóa đơn"]);
    exit;
}
$id = $data["id"];

// Cập nhật trạng thái thành "Đã thanh toán"
$stmt = $conn->prepare("UPDATE hoa_don SET status = 'Đã thanh toán' WHERE id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    exit;
}
$stmt->bind_param("s", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Không tìm thấy hoặc hóa đơn đã được phê duyệt"]);
}

$stmt->close();
$conn->close();
?>
