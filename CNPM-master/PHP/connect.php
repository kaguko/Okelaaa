<?php
$servername = "127.0.0.1";  // WAMP/XAMPP đều dùng được
$username = "root";
$password = "";              // Không mật khẩu mặc định
$database = "cnpm_db";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);
if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Kết nối thất bại: " . $conn->connect_error
    ]));
}
$conn->set_charset("utf8mb4");
?>
