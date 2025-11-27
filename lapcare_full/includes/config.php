<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';          // sửa nếu MySQL có mật khẩu
$DB_NAME = 'lapcare';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_errno) {
    die("Kết nối MySQL thất bại: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
?>
