<?php
session_start();
include('includes/config.php');
//include 'includes/header.php';
// ===== LẤY THÔNG TIN SẢN PHẨM =====
//$masp = isset($_GET['masp']) ? $_GET['masp'] : '';
$masp="SP001";
$sql = "SELECT sp.*, l.tenloai 
        FROM sanpham sp 
        JOIN loaisp l ON sp.maloaisp = l.maloaisp 
        WHERE sp.masp = '$masp'";
$result = $conn->query($sql);
/*$stmt = $conn->prepare("SELECT s.*, l.tenloai 
                        FROM sanpham s 
                         JOIN loaisp l ON s.maloaisp = l.maloaisp
                        WHERE masp = ?");
$stmt->bind_param("s", $masp);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();*/
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "<h2>Không tìm thấy sản phẩm!</h2>";
    exit;
}

// lấy ra sản phẩm cùng loại
$current_masp = $row['masp'];
$current_maloai_prefix = substr($row['maloaisp'], 0, 2); // 2 ký tự đầu
$sql_related = "SELECT * FROM sanpham
                WHERE LEFT(maloaisp, 2) = '$current_maloai_prefix'
                  AND masp != '$current_masp'
                ORDER BY RAND()
                LIMIT 4";

$query_related = mysqli_query($conn, $sql_related);

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="assets/css/detail.css">
    <link rel="stylesheet" href="../assets/framework/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/framework/css/">
    <link rel="stylesheet" href="../assets/framework/css/font-icon/bootstrap-icons.min.css">
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">-->

</head>
<body>
<!--noi dung-->
<?php
//while ()
?>
<div class="container mt-5">
    <div class="product-container">
        <div class="product-images">
            <div class="main-image">
                <img id="main-product-image" src="<?php echo $row['hinhanh']; ?>" alt="<?php echo $row['tensp'];?>">
            </div>
        </div>
        
        <div class="product-info">
            <h2 class="fw-bold" style="font-size: 2em; font-weight: bold;"><?php echo $row['tensp'];?></h2>
            <p class="text-muted description"><?php echo nl2br(htmlspecialchars($row['mota']));?></p>
            <div class="d-flex align-items-center gap-3 mb-3">
                <?php 
            $gia = (float)$row['giasp'];
            $km = (float)$row['khuyenmai'];
            $gia_km = $km > 0 ? $gia * (1 - $km) : $gia;
            ?>
            <p class="m-0" style="font-size: 1.5rem; font-weight: bold; color: #d0021b;">
        <?php echo number_format($gia_km, 0, ',', '.'); ?> đ
    </p>

    <!-- Giá gốc -->
    <?php if ($km > 0): ?>
        <p class="m-0" 
           style="font-size: 1.2rem; color: #777; text-decoration: line-through;">
            <?php echo number_format($gia, 0, ',', '.'); ?> đ
        </p>
    <?php endif; ?>
            </div>
            

            <div class="description">
                <strong>Thông số kỹ thuật</strong>
                <ul>
                    <?php if (!empty($row['cpu'])): ?>
                        <li><p class="indam"><strong>CPU: </strong><?php echo $row['cpu']; ?></p></li>
                    <?php endif; ?>
                    <?php if (!empty($row['ram'])): ?>
                        <li><p class="indam"><strong>RAM: </strong><?php echo $row['ram']; ?></p></li>
                    <?php endif; ?>

                    <?php if (!empty($row['ocung'])): ?>
                        <li><p class="indam"><strong>Ổ cứng: </strong><?php echo $row['ocung']; ?></p></li>
                    <?php endif; ?>

                    <?php if (!empty($row['manhinh'])): ?>
                        <li><p class="indam"><strong>Màn hình: </strong><?php echo $row['manhinh']; ?></p></li>
                    <?php endif; ?>
                    <?php if ($product['hang']): ?>
                        <li>p class="indam"><strong>Hãng:</strong> <?php echo htmlspecialchars($product['hang']); ?></p></li>
                    <?php endif; ?>
                    <li><p class="indam"><strong>Thời gian bảo hành: </strong><?php echo $row['thoigian'];?></p></li>
                </ul>
            </div>

            <div class="card-button">
                <div>
                    <form action="xuly/xlmuahang.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $row['masp']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" name="add_to_cart" class="buy-now-btn">Thêm vào giỏ</button>
                    </form>
                    <!--<button class="buy-now-btn" data-bs-toggle="modal" data-bs-target="#sanpham">Thêm vào giỏ</button>-->
                </div>
                <div>
                <form action="" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['masp']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" name="buy_now" class="buy-now-btn">Mua ngay</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Phần content-container -->
    <div class="content-container">
        <h3><?php echo $row['tensp'];?> – thiết bị được thiết kế để định hình lại cách bạn làm việc, học tập và giải trí.</h3>
        <p> Với bộ xử lý hiệu năng cao (như CPU thế hệ mới nhất) cùng bộ nhớ RAM dung lượng lớn, <strong><?php echo $row['tensp'];?></strong> không chỉ đáp ứng mà còn vượt xa mọi yêu cầu tác vụ của bạn, 
            từ đa nhiệm mượt mà, xử lý đồ họa chuyên nghiệp, cho đến những trận game gay cấn.Thiết kế tinh tế, hiện đại (và di động linh hoạt nếu là laptop/tablet) 
            biến sản phẩm này thành một tuyên ngôn phong cách, trong khi công nghệ màn hình sắc nét, sống động mang đến trải nghiệm hình ảnh tuyệt vời. Đừng bỏ lỡ cơ hội sở hữu trợ thủ đắc lực này! 
            Mua ngay hôm nay để nhận ưu đãi độc quyền và khám phá tiềm năng không giới hạn mà sản phẩm mang lại!</p>
    </div>

    <!-- Phần sản phẩm nổi bật -->
    <div id="sanphamnoibat">
        <div class="container my-5">
            <h2 class="text-center mb-4 " style="text-align:center; color:aliceblue;padding-top: 1em; 
            animation: blinkingText 1s infinite;"> SẢN PHẨM LIÊN QUAN </h2>
            <div class="product-grid">
                <?php while ($sp = mysqli_fetch_assoc($query_related)) { ?>
                <div class="col product mb-5">
                    <div class="card d-block hvr-glow">
                        <a href="detail.php?masp=<?php echo $sp['masp']; ?>" style="text-decoration:none;">
                            <img src="<?php echo $sp['hinhanh']; ?>" class="card-img-top product-image" alt="<?php echo $sp['tensp']; ?>">
                        <div class="card-body ms-1">
                            <p class="fw-bold text-truncate"><?php echo $sp['tensp']; ?></p>
                            <div class="product-specs">
                                <?php if (!empty($row['cpu'])): ?>
                        <li><p class="indam">CPU: <?php echo $row['cpu']; ?></p></li>
                    <?php endif; ?>
                    <?php if (!empty($row['ram'])): ?>
                        <li><p class="indam">RAM: <?php echo $row['ram']; ?></p></li>
                    <?php endif; ?>

                    <?php if (!empty($row['ocung'])): ?>
                        <li><p class="indam">Ổ cứng: <?php echo $row['ocung']; ?></p></li>
                    <?php endif; ?>
                            </div>

                            <div class="product-price">
                                <?php echo number_format($sp['giasp'], 0, ',', '.'); ?>VND
                            </div>
                        </div>
                    </a>
                    </div>
                </div>
            <?php } ?>

            </div>
        </div>
    </div>
</div>

</body>
</html>