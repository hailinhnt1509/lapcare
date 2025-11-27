<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Sản Phẩm</title>

    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="../footer/footer.css">
    <link rel="stylesheet" href="sanpham.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>

<?php include '../header/header.php'; ?>
<?php include '../includes/config.php'; ?>

<?php
// ============ PHÂN TRANG ============
$limit = 12;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// ============ BỘ LỌC ============
$where = " WHERE 1 ";

if (!empty($_GET['hang']))  $where .= " AND hang = '{$_GET['hang']}' ";
if (!empty($_GET['ram']))   $where .= " AND ram = '{$_GET['ram']}' ";
if (!empty($_GET['manhinh'])) $where .= " AND manhinh = '{$_GET['manhinh']}' ";

if (!empty($_GET['gia'])) {
    $gia = $_GET['gia'];
    if ($gia == 1) $where .= " AND giasp < 10000000 ";
    if ($gia == 2) $where .= " AND giasp BETWEEN 10000000 AND 20000000 ";
    if ($gia == 3) $where .= " AND giasp > 20000000 ";
}

// ============ SẮP XẾP THEO ============
$orderBy = "";
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == "low")     $orderBy = " ORDER BY giasp ASC ";
    if ($_GET['sort'] == "high")    $orderBy = " ORDER BY giasp DESC ";
    if ($_GET['sort'] == "popular") $orderBy = "";
}

$sql = "SELECT * FROM sanpham $where $orderBy LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

// tổng sản phẩm
$countQuery = mysqli_query($conn, "SELECT COUNT(masp) AS total FROM sanpham $where");
$total = mysqli_fetch_assoc($countQuery)['total'];
$totalPage = ceil($total / $limit);
?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<div class="container">

    <!-- ===================== BANNER ===================== -->
    <div class="swiper banner-slide">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="../images/banner1.jpg"></div>
            <div class="swiper-slide"><img src="../images/banner2.jpg"></div>
            <div class="swiper-slide"><img src="../images/banner3.jpg"></div>
        </div>
    </div>

    <script>
        new Swiper('.swiper', { loop: true, autoplay: { delay: 3000 } });
    </script>


    <!-- ===================== CHỌN NHU CẦU ===================== -->
    <h3 class="nhucau-title">Chọn theo nhu cầu</h3>

    <div class="nhucau-wrapper">
        <?php 
        $nc = [
            ["Văn phòng", "laptop văn phòng.jpg"],
            ["Gaming", "laptop gaming.jpg"],
            ["Đồ hoạ - Kỹ thuật", "laptop đồ hoạ kỹ thuật.jpg"],
            ["Sinh viên", "laptop sinh viên.jpg"],
            ["Cảm ứng", "laptop cảm ứng.jpg"],
            ["Phụ kiện", "phụ kiện.jpg"]
        ];

        foreach ($nc as $item) { ?>
            <div class="nhucau-item">
                <img src="../images/<?= $item[1] ?>">
                <p><?= $item[0] ?></p>
            </div>
        <?php } ?>
    </div>




<h3 class="nhucau-title">Sản phẩm</h3> 

<div class="filter-topbar">

    <!-- Nút mở popup lọc -->
    <div class="filter-pill filter-open">
        <img src="../images/filter-icon.png" class="icon">
        <span>Lọc</span>
    </div>

    <!-- Hãng -->
    <div class="filter-pill">
        <img src="../images/asus.png" class="brand-icon">
        
    </div>

    <div class="filter-pill">
        <img src="../images/hp.png" class="brand-icon">
        
    </div>

    <div class="filter-pill">
        <img src="../images/dell.png" class="brand-icon">
        
    </div>


    <div class="filter-pill">
        <img src="../images/lenovo.png" class="brand-icon">
        
    </div>

</div>
<!-- =============== POP-UP LỌC =============== -->
<div id="filterModal" class="filter-modal">

    <div class="filter-modal-content">

        <div class="filter-modal-header">
            <h2>Tất cả bộ lọc</h2>
            <span class="filter-close">&times;</span>
        </div>

        <div class="filter-section">

            <!-- Hãng -->
            <h4>Hãng</h4>
            <div class="filter-grid">
                <label class="filter-option">
                    <img src="../images/hp.png">
                </label>
                <label class="filter-option">
                    <img src="../images/asus.png">
                </label>
                <label class="filter-option">
                    <img src="../images/acer.png">
                </label>
                <label class="filter-option">
                    <img src="../images/lenovo.png">
                </label>
                <label class="filter-option">
                    <img src="../images/dell.png">
                </label>
                <label class="filter-option">
                    <img src="../images/msi.png">
                </label>
            </div>

            <!-- Giá -->
            <h4>Giá</h4>
            <div class="filter-grid">
                <div class="filter-price-btn">Dưới 10 triệu</div>
                <div class="filter-price-btn">10 – 15 triệu</div>
                <div class="filter-price-btn">15 – 20 triệu</div>
                <div class="filter-price-btn">20 – 25 triệu</div>
                <div class="filter-price-btn">25 – 30 triệu</div>
                <div class="filter-price-btn">Trên 30 triệu</div>
            </div>

            <!-- Loại sản phẩm -->
            <h4>Loại sản phẩm</h4>
            <div class="filter-grid">
                <div class="filter-type">
                    <img src="../images/laptopAI.png">
                    Laptop AI
                </div>
                <div class="filter-type">
                    <img src="../images/gaming.png">
                    Gaming
                </div>
                <div class="filter-type">
                    <img src="../images/student.png">
                    Học tập
                </div>
                <div class="filter-type">
                    <img src="../images/work.png">
                    Văn phòng
                </div>
                <div class="filter-type">
                    <img src="../images/design.png">
                    Đồ hoạ
                </div>
                <div class="filter-type">
                    <img src="../images/lightweight.png">
                    Mỏng nhẹ
                </div>
            </div>

        </div>

        <div class="filter-modal-footer">
            <button class="apply-btn">Áp dụng</button>
        </div>
    </div>

</div>


    <!-- ===================== SẮP XẾP THEO ===================== -->
    

<div class="sort-box">

    <a href="?sort=hot" class="sort-btn">
        🔥 Khuyến mãi HOT
    </a>

    <a href="?sort=low" class="sort-btn">
        ⬆ Giá Thấp – Cao
    </a>

    <a href="?sort=high" class="sort-btn">
        ⬇ Giá Cao – Thấp
    </a>

</div>




    <!-- ===================== DANH SẢN PHẨM ===================== -->
    

<div class="product-grid">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <?php 
            // Tính giảm giá %
            $gia = $row['giasp'];
            $km = $row['khuyenmai']*100; // ví dụ 15 (tức 15%)
            $giagiam = $gia - ($gia * ($km / 100));
        ?>

        <div class="product-item">

            <!-- Nhãn giảm giá -->
            <?php if ($km > 0) { ?>
                <div class="discount-badge">Giảm <?= $km ?>%</div>
            <?php } ?>

            <!-- Ảnh -->
            <img src="../<?= $row['hinhanh'] ?>" alt="">


            <!-- Tên sản phẩm -->
            <h5><?= $row['tensp'] ?></h5>

            <!-- Hãng -->
            <p class="brand"><?= $row['hang'] ?></p>

            <!-- Giá sau giảm + giá gốc -->
            <p class="price">
                <?= number_format($giagiam) ?>đ
                <?php if ($km > 0) { ?>
                    <span class="old-price"><?= number_format($gia) ?>đ</span>
                <?php } ?>
            </p>

            <!-- Nút -->
            <div class="btn-group">
                <button class="btn-buy">Mua hàng</button>

<div class="cart-btn-wrapper">
    <a href="giohang_them.php?masp=<?= $row['masp'] ?>" class="btn-cart-icon">
        <i class="fa fa-shopping-cart"></i>
    </a>
    <span class="tooltip">Thêm vào giỏ hàng</span>
</div>

            </div>

        </div>
    <?php } ?>
</div>




    <!-- ===================== PHÂN TRANG ===================== -->
    <div class="pagination text-center">
        <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
            <a class="<?= ($i == $page) ? 'active' : '' ?>" href="?page=<?= $i ?>"><?= $i ?></a>
        <?php } ?>
    </div>

</div>

<div class="feedback-box">

    <p class="fb-title">
        Bạn có hài lòng với trải nghiệm tìm kiếm thông tin, sản phẩm trên website không?
    </p>

    <div class="fb-options">

        <div class="fb-option" data-value="yes">
            <span class="emoji">🥰</span>
            <span class="label">Hài lòng</span>
        </div>

        <div class="fb-option" data-value="no">
            <span class="emoji">😔</span>
            <span class="label">Không hài lòng</span>
        </div>

    </div>

</div>

<?php include '../footer/footer.php'; ?>

</body>

<script>
    
const openBtn = document.querySelector('.filter-open');
const modal = document.getElementById('filterModal');
const closeBtn = document.querySelector('.filter-close');

openBtn.onclick = () => modal.style.display = "flex";
closeBtn.onclick = () => modal.style.display = "none";

window.onclick = e => {
    if (e.target == modal) modal.style.display = "none";
};

document.querySelectorAll('.fb-option').forEach(opt => {
    opt.onclick = function() {
        // Bỏ selected ở tất cả
        document.querySelectorAll('.fb-option').forEach(o => o.classList.remove('selected'));

        // Chọn cái được click
        this.classList.add('selected');

        // Giá trị khách chọn
        console.log("Khách chọn:", this.dataset.value);

        // TODO: Gửi về DB hoặc AJAX tuỳ bạn
    };
});

    </script>
</html>
