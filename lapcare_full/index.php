<?php include 'includes/header.php'; ?>

<?php
// ====== LẤY SẢN PHẨM CHÍNH (GIỚI HẠN 7 SP) ======
$cat = isset($_GET['cat']) ? $_GET['cat'] : null;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;

$sql = "SELECT * FROM sanpham WHERE 1";
$params = [];

if ($cat) {
    $sql .= " AND maloaisp = ?";
    $params[] = $cat;
}

if ($keyword) {
    $sql .= " AND tensp LIKE ?";
    $params[] = "%" . $keyword . "%";
}

// GIỚI HẠN 7 SẢN PHẨM
$sql .= " ORDER BY masp LIMIT 7";

if ($params) {
    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $result->free();
}
if (isset($stmt)) {
    $stmt->close();
}

// ====== FLASH SALE: LẤY 4 SẢN PHẨM KHUYẾN MÃI CAO NHẤT ======
$flashProducts = [];
$flashSql = "SELECT * 
             FROM sanpham 
             WHERE khuyenmai > 0 
             ORDER BY khuyenmai DESC, giasp DESC 
             LIMIT 3";

if ($flashResult = $conn->query($flashSql)) {
    while ($row = $flashResult->fetch_assoc()) {
        $flashProducts[] = $row;
    }
    $flashResult->free();
}
?>



<div class="container mt-4">
    
    <!-- HERO BANNER LAPCARE -->
<div class="hero-lapcare mb-4">
    <img src="images/nen.png" alt="Lapcare Banner" class="hero-img">
</div>


    <div class="p-4 mb-4 rounded text-white banner-lapcare">
        <h3 class="fw-bold mb-2">Khuyến mãi Lapcare</h3>
        <p class="mb-3">Giảm đến 5 triệu cho laptop &amp; phụ kiện – Săn deal ngay hôm nay.</p>
        <a href="#" class="btn btn-light text-danger fw-bold">Xem ưu đãi</a>
    </div>

        <!-- GIỚI THIỆU LAPCARE -->
    <section class="intro-lapcare mb-4">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="intro-text">
                    <p>
                        Trong bối cảnh công nghệ thông tin đang phát triển ngày càng mạnh mẽ, nhu cầu sử dụng các thiết bị
                        điện tử đặc biệt như máy tính để thực hiện các công việc hàng ngày như học tập, công việc cũng ngày
                        một tăng cao. Cũng từ đó ý tưởng website kinh doanh máy tính và phụ kiện điện tử được xây dựng nhằm
                        tạo ra một nền tảng thương mại điện tử chuyên cung cấp cho những tín đồ đam mê công nghệ cũng như
                        những người dùng có nhu cầu sử dụng chúng.
                    </p>
                    <p>
                        Website cung cấp đa dạng sản phẩm như laptop, PC lắp ráp, màn hình, bàn phím, chuột, tai nghe và
                        các thiết bị nâng cấp phần cứng. Hệ thống được thiết kế theo hướng tập trung vào trải nghiệm người
                        dùng, tối ưu quá trình tìm kiếm, so sánh, đặt hàng, đồng thời áp dụng các gợi ý sản phẩm thông minh
                        giúp khách hàng lựa chọn phù hợp theo nhu cầu học tập, làm việc, gaming.
                    </p>
                    <p>
                        Bên cạnh đó, khách hàng cũng có thể tìm thấy những sản phẩm mới nhất từ các thương hiệu nổi tiếng
                        như Apple, Asus, Lenovo, Dell, MSI... giúp cho khách hàng luôn được tiếp cận một cách nhanh nhất đến
                        công nghệ.
                    </p>
                    <p>
                        Chúng tôi tự tin rằng, khi đến với website của chúng tôi, các bạn sẽ lựa chọn được những sản phẩm
                        ưng ý, với giá thành rẻ cùng như chất lượng tốt nhất trong từng phân khúc giá.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="intro-card text-center">
                    <h5 class="fw-bold mb-2">Lapcare – Laptop &amp; Phụ kiện</h5>
                    <p class="small mb-3">
                        Nền tảng mua sắm thiết bị công nghệ dành cho học tập, làm việc và giải trí.
                    </p>
                    <ul class="list-unstyled small text-start d-inline-block">
                        <li>• Laptop chính hãng, đa dạng cấu hình</li>
                        <li>• Phụ kiện văn phòng &amp; gaming</li>
                        <li>• Tư vấn theo nhu cầu sử dụng</li>
                        <li>• Bảo hành uy tín, hỗ trợ nhanh</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


<?php
// Thời gian kết thúc flash sale: 30 ngày tính từ hiện tại
$flashEndTime = time() + 30 * 24 * 60 * 60;
?>

<?php if (!empty($flashProducts)): ?>
    <!-- FLASH SALE -->
    <section class="flash-sale mb-4">
        <div class="flash-sale-header d-flex justify-content-between align-items-center">
            <div class="fs-title">
                ⚡ FLASH SALE LAPCARE – GIẢM SỐC HÔM NAY ⚡
            </div>
            <div class="fs-timer small">
                Kết thúc sau: <span id="fs-countdown">00 ngày 00:00:00</span>
            </div>
        </div>

        <!-- GRID FLASH SALE: MOBILE 1 CỘT, TỪ MD TRỞ LÊN 3 CỘT -->
<div class="row row-cols-1 row-cols-md-3 g-3 mt-2">

            <?php foreach ($flashProducts as $p): ?>
                <?php
                    $gia = (float)$p['giasp'];
                    $km  = (float)$p['khuyenmai'];
                    $gia_km = $km > 0 ? $gia * (1 - $km) : $gia;
                ?>
                <div class="col">
                    <div class="flash-item h-100">
                        <img src="<?php echo htmlspecialchars($p['hinhanh']); ?>"
                             alt="<?php echo htmlspecialchars($p['tensp']); ?>">

                        <p class="name mb-1">
                            <?php echo htmlspecialchars($p['tensp']); ?>
                        </p>

                        <p class="price-sale mb-0">
                            <?php echo number_format($gia_km, 0, ',', '.'); ?> đ
                        </p>

                        <?php if ($km > 0): ?>
                            <p class="price-original mb-1">
                                <del><?php echo number_format($gia, 0, ',', '.'); ?> đ</del>
                                <span class="badge-km">-<?php echo $km * 100; ?>%</span>
                            </p>
                        <?php endif; ?>

                        <a href="product.php?masp=<?php echo urlencode($p['masp']); ?>"
                           class="btn btn-danger btn-sm w-100 mt-1">
                            Mua ngay
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>





    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
            <?php
            if ($cat) {
                foreach ($categories as $c) {
                    if ($c['maloaisp'] === $cat) {
                        echo "Sản phẩm: " . htmlspecialchars($c['tenloai']);
                        break;
                    }
                }
            } elseif ($keyword) {
                echo "Kết quả tìm kiếm cho: " . htmlspecialchars($keyword);
            } else {
                echo "Sản phẩm nổi bật";
            }
            ?>
        </h4>
        <span class="text-muted small">
            Có <?php echo count($products); ?> sản phẩm
        </span>
    </div>

    <div class="row g-3">
        <?php foreach ($products as $p): ?>
            <?php
            $gia = (float)$p['giasp'];
            $km = (float)$p['khuyenmai'];
            $gia_km = $km > 0 ? $gia * (1 - $km) : $gia;
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm product-card position-relative">
                    <?php if ($km > 0): ?>
                        <span class="badge bg-danger position-absolute m-2">
                            -<?php echo $km * 100; ?>%
                        </span>
                    <?php endif; ?>

                    <img src="<?php echo htmlspecialchars($p['hinhanh']); ?>"
                         class="card-img-top" alt="<?php echo htmlspecialchars($p['tensp']); ?>">

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">
                            <?php echo htmlspecialchars($p['tensp']); ?>
                        </h6>

                        <p class="mb-1 text-danger fw-bold">
                            <?php echo number_format($gia_km, 0, ',', '.'); ?> đ
                        </p>
                        <?php if ($km > 0): ?>
                            <p class="mb-1 small text-muted text-decoration-line-through">
                                <?php echo number_format($gia, 0, ',', '.'); ?> đ
                            </p>
                        <?php endif; ?>

                        <p class="small text-muted mb-2">
                            <?php echo htmlspecialchars($p['hang']); ?>
                            <?php echo $p['ram'] ? ' · ' . htmlspecialchars($p['ram']) : ''; ?>
                            <?php echo $p['cpu'] ? ' · ' . htmlspecialchars($p['cpu']) : ''; ?>
                        </p>

                        <div class="mt-auto d-flex gap-2">
                            <a href="product.php?masp=<?php echo urlencode($p['masp']); ?>"
                               class="btn btn-outline-secondary btn-sm flex-grow-1">
                                Chi tiết
                            </a>
                            <a href="cart.php?action=add&amp;masp=<?php echo urlencode($p['masp']); ?>"
                               class="btn btn-danger btn-sm flex-grow-1">
                                Thêm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
            <p>Không tìm thấy sản phẩm phù hợp.</p>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    const countdownEl = document.getElementById('fs-countdown');
    if (!countdownEl) return;

    // PHP -> JS (milisecond)
    const endTime = <?php echo $flashEndTime * 1000; ?>;

    function updateCountdown() {
        const now = Date.now();
        let diff = Math.floor((endTime - now) / 1000); // giây

        if (diff <= 0) {
            countdownEl.textContent = "00 ngày 00:00:00";
            clearInterval(timer);
            return;
        }

        const days = Math.floor(diff / 86400);
        diff %= 86400;
        const hours = String(Math.floor(diff / 3600)).padStart(2, "0");
        diff %= 3600;
        const mins  = String(Math.floor(diff / 60)).padStart(2, "0");
        const secs  = String(diff % 60).padStart(2, "0");

        countdownEl.textContent = days + " ngày " + hours + ":" + mins + ":" + secs;
    }

    updateCountdown();
    const timer = setInterval(updateCountdown, 1000);
})();
</script>


<?php include 'includes/footer.php'; ?>
