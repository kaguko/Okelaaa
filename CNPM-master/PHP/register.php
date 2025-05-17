<?php
// ...existing code...

// Kiểm tra email hoặc username đã tồn tại trước khi đăng ký
$email = $conn->real_escape_string($_POST['email']);
$username = $conn->real_escape_string($_POST['username']);
$sql_check = "SELECT id FROM users WHERE email='$email' OR username='$username'";
$result = $conn->query($sql_check);
if ($result && $result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email hoặc tên đăng nhập đã tồn tại!']);
    exit;
}

// Thực hiện lệnh INSERT như bình thường
// ...existing code...
?>