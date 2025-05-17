<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$fullname || !$phone || !$email || !$gender || !$dob || !$address || !$username || !$password) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
        $conn->close();
        exit;
    }

    // Kiểm tra username đã tồn tại chưa
    $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Lỗi prepare: ' . $conn->error]);
        $conn->close();
        exit;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Tên đăng nhập đã tồn tại!']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Mã hóa mật khẩu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admin (fullname, phone, email, gender, dob, address, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Lỗi prepare insert: ' . $conn->error]);
        $conn->close();
        exit;
    }
    $stmt->bind_param('ssssssss', $fullname, $phone, $email, $gender, $dob, $address, $username, $hashedPassword);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đăng ký admin thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi đăng ký admin! ' . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
$conn->close();
