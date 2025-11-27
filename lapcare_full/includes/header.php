<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';

// Lấy danh sách loại sản phẩm (cho index.php dùng biến $categories)
$categories = [];
$sql_loaisp = "SELECT maloaisp, tenloai FROM loaisp ORDER BY maloaisp";
if ($result = $conn->query($sql_loaisp)) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    $result->free();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lapcare - Laptop &amp; Phụ kiện</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS riêng -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<!-- KHÔNG còn header / menu ở đây theo yêu cầu của nhóm -->
