<?php
header("Content-Type: text/html; charset=UTF-8");
include("connect.php");

// Thống kê
$total = 0;
$paid = 0;
$unpaid = 0;
$sum_paid = 0;
$sum_unpaid = 0;

$sql = "SELECT status, COUNT(*) as count, SUM(total) as sum FROM hoa_don GROUP BY status";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $total += $row['count'];
    if ($row['status'] == 'Đã thanh toán') {
        $paid += $row['count'];
        $sum_paid += $row['sum'];
    } elseif ($row['status'] == 'Chưa thanh toán') {
        $unpaid += $row['count'];
        $sum_unpaid += $row['sum'];
    }
}

// Lấy tổng tiền tất cả hóa đơn (dù trạng thái gì)
$sql2 = "SELECT SUM(total) as total_all FROM hoa_don";
$total_all = 0;
$result2 = $conn->query($sql2);
if ($row2 = $result2->fetch_assoc()) {
    $total_all = $row2['total_all'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Báo cáo thống kê hóa đơn</title>
  <style>
    body { font-family: Arial; margin: 40px; }
    h2 { margin-bottom: 25px; }
    table { border-collapse: collapse; min-width: 400px;}
    td, th { border: 1px solid #bbb; padding: 10px 18px; }
    th { background: #f5f5f5; }
    .sum { font-weight: bold; color: #006b1b; }
  </style>
</head>
<body>
  <h2>BÁO CÁO THỐNG KÊ HÓA ĐƠN</h2>
  <table>
    <tr>
      <th>Chỉ tiêu</th>
      <th>Giá trị</th>
    </tr>
    <tr>
      <td>Tổng số hóa đơn</td>
      <td><?= $total ?></td>
    </tr>
    <tr>
      <td>Số hóa đơn đã thanh toán</td>
      <td><?= $paid ?></td>
    </tr>
    <tr>
      <td>Số hóa đơn chưa thanh toán</td>
      <td><?= $unpaid ?></td>
    </tr>
    <tr>
      <td>Tổng tiền đã thanh toán</td>
      <td class="sum"><?= number_format($sum_paid, 0, ",", ".") ?> VNĐ</td>
    </tr>
    <tr>
      <td>Tổng tiền chưa thanh toán</td>
      <td class="sum"><?= number_format($sum_unpaid, 0, ",", ".") ?> VNĐ</td>
    </tr>
    <tr>
      <td>Tổng tiền của tất cả hóa đơn</td>
      <td class="sum"><?= number_format($total_all, 0, ",", ".") ?> VNĐ</td>
    </tr>
  </table>
  <br>
  <a href="javascript:window.print()" style="color:#168add">🖨 In báo cáo</a>
</body>
</html>
