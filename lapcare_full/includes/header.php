<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';

// Lấy danh sách loại sản phẩm
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
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="bg-dark text-white py-1 small">
    <div class="container d-flex justify-content-between">
        <div>Lapcare - Laptop chính hãng, trả góp 0%</div>
        <div>Hotline: 1900 1234</div>
    </div>
</div>

<header class="bg-danger text-white py-3">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="index.php" class="text-white text-decoration-none fw-bold fs-3">
            Lapcare
        </a>

        <form class="d-flex flex-grow-1 mx-4" action="index.php" method="get">
            <input class="form-control me-2" type="search" name="keyword"
                   placeholder="Bạn muốn mua gì hôm nay?">
            <button class="btn btn-light" type="submit">Tìm</button>
        </form>

        <div class="d-flex align-items-center gap-3">
            <a href="cart.php" class="text-white text-decoration-none">
                🛒 Giỏ hàng
                (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)
            </a>
            <a href="login.php" class="btn btn-outline-light btn-sm">Đăng nhập</a>
            <a href="register.php" class="btn btn-light btn-sm text-danger">Đăng ký</a>
        </div>
    </div>

    <nav class="mt-3">
        <div class="container">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php">Trang chủ</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button"
                       data-bs-toggle="dropdown">
                        Danh mục
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach ($categories as $c): ?>
                            <li>
                                <a class="dropdown-item"
                                   href="index.php?cat=<?php echo urlencode($c['maloaisp']); ?>">
                                    <?php echo htmlspecialchars($c['tenloai']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link text-white" href="#">Tin tức</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Liên hệ</a></li>
            </ul>
        </div>
    </nav>
</header>
