<?php
include 'includes/header.php';

if (!isset($_GET['masp'])) {
    echo "<div class='container mt-4'>Không tìm thấy sản phẩm.</div>";
    include 'includes/footer.php';
    exit;
}

$masp = $_GET['masp'];

$stmt = $conn->prepare("SELECT s.*, l.tenloai 
                        FROM sanpham s 
                        LEFT JOIN loaisp l ON s.maloaisp = l.maloaisp
                        WHERE masp = ?");
$stmt->bind_param("s", $masp);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<div class='container mt-4'>Sản phẩm không tồn tại.</div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-5">
            <img src="<?php echo htmlspecialchars($product['hinhanh']); ?>"
                 class="img-fluid border rounded" alt="<?php echo htmlspecialchars($product['tensp']); ?>">
        </div>
        <div class="col-md-7">
            <h3><?php echo htmlspecialchars($product['tensp']); ?></h3>
            <p class="text-muted">
                Mã: <?php echo htmlspecialchars($product['masp']); ?> |
                Loại: <?php echo htmlspecialchars($product['tenloai']); ?>
            </p>

            <?php
            $gia = (float)$product['giasp'];
            $km = (float)$product['khuyenmai'];
            $gia_km = $km > 0 ? $gia * (1 - $km) : $gia;
            ?>
            <p class="fs-4 text-danger fw-bold">
                <?php echo number_format($gia_km, 0, ',', '.'); ?> đ
            </p>
            <?php if ($km > 0): ?>
                <p class="text-muted text-decoration-line-through">
                    Giá gốc: <?php echo number_format($gia, 0, ',', '.'); ?> đ
                    (Giảm <?php echo $km * 100; ?>%)
                </p>
            <?php endif; ?>

            <ul class="list-unstyled mb-3">
                <?php if ($product['manhinh']): ?>
                    <li><strong>Màn hình:</strong> <?php echo htmlspecialchars($product['manhinh']); ?></li>
                <?php endif; ?>
                <?php if ($product['cpu']): ?>
                    <li><strong>CPU:</strong> <?php echo htmlspecialchars($product['cpu']); ?></li>
                <?php endif; ?>
                <?php if ($product['ram']): ?>
                    <li><strong>RAM:</strong> <?php echo htmlspecialchars($product['ram']); ?></li>
                <?php endif; ?>
                <?php if ($product['ocung']): ?>
                    <li><strong>Ổ cứng:</strong> <?php echo htmlspecialchars($product['ocung']); ?></li>
                <?php endif; ?>
                <?php if ($product['hang']): ?>
                    <li><strong>Hãng:</strong> <?php echo htmlspecialchars($product['hang']); ?></li>
                <?php endif; ?>
                <?php if ($product['thoigian']): ?>
                    <li><strong>Bảo hành:</strong> <?php echo htmlspecialchars($product['thoigian']); ?></li>
                <?php endif; ?>
            </ul>

            <p><?php echo nl2br(htmlspecialchars($product['mota'])); ?></p>

            <a href="cart.php?action=add&amp;masp=<?php echo urlencode($product['masp']); ?>"
               class="btn btn-danger me-2">Thêm vào giỏ hàng</a>
            <a href="index.php" class="btn btn-outline-secondary">Quay lại trang chủ</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
