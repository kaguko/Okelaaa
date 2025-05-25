<?php
header("Content-Type: application/json; charset=UTF-8");
include("connect.php");

// Trả về danh sách hóa đơn có trạng thái "Chưa thanh toán"
$sql = "SELECT id, total FROM hoa_don WHERE status = 'Chưa thanh toán'";
$result = $conn->query($sql);

$invoices = [];
while ($row = $result->fetch_assoc()) {
    $invoices[] = [
        "id" => $row["id"],
        "total" => $row["total"]
    ];
}

echo json_encode([
    "success" => true,
    "invoices" => $invoices
]);

$conn->close();
?>
