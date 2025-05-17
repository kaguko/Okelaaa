<?php
require_once 'connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Đăng nhập thành công!',
                    'user' => [
                        'username' => $user['username'],
                        'email' => $user['email'] ?? ''
                    ]
                ]);
                $stmt->close();
                $conn->close();
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Sai mật khẩu!']);
                $stmt->close();
                $conn->close();
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Tài khoản không tồn tại!']);
            $stmt->close();
            $conn->close();
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
        $conn->close();
        exit;
    }
}
$conn->close();
