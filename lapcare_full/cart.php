<?php
include 'includes/header.php';

if (!isset($_SESSION['matk'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem giỏ hàng!');window.location='login.php';</script>";
    exit;
}
$matk = $_SESSION['matk'];

// ==================== XỬ LÝ CÁC ACTION ====================
$action = $_GET['action'] ?? null;
$masp = $_GET['masp'] ?? null;

// --- Xoá 1 sản phẩm ---
if ($action === 'remove' && $masp) {
    $conn->query("DELETE FROM giohang WHERE matk='$matk' AND masp='$masp'");
    echo "<script>alert('Đã xóa sản phẩm khỏi giỏ hàng!');window.location='cart.php';</script>";
    exit;
}

// --- Xóa toàn bộ giỏ hàng ---
if ($action === 'clear') {
    $conn->query("DELETE FROM giohang WHERE matk='$matk'");
    echo "<script>alert('Đã xóa toàn bộ giỏ hàng!');window.location='cart.php';</script>";
    exit;
}

// --- Cập nhật số lượng ---
if ($action === 'update' && !empty($_POST['qty'])) {
    foreach ($_POST['qty'] as $id => $soluong) {
        $soluong = max(1, (int)$soluong);

        $conn->query("
            UPDATE giohang 
            SET soluong = $soluong 
            WHERE matk='$matk' AND masp='$id'
        ");
    }

    echo "<script>alert('Cập nhật giỏ hàng thành công!');window.location='cart.php';</script>";
    exit;
}

// ==================== LẤY DỮ LIỆU GIỎ HÀNG ====================
$sql = "
SELECT g.*, s.tensp, s.giasp, s.khuyenmai, s.hinhanh
FROM giohang g
JOIN sanpham s ON g.masp = s.masp
WHERE g.matk = '$matk'
";
$result = $conn->query($sql);

$items = [];
while ($row = $result->fetch_assoc()) {
    $gia = (float)$row['giasp'];
    $km = (float)$row['khuyenmai'];
    $gia_km = $km > 0 ? $gia * (1 - $km) : $gia;

    $row['dongia'] = $gia_km;
    $row['thanhtien'] = $gia_km * $row['soluong'];
    $items[] = $row;
}
?>


<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    
    <style>
        .qty-control {
    display: flex;
    align-items: center;
    width: 120px;
}

.qty-btn {
    width: 35px;
    height: 35px;
    border: 1px solid #ddd;
    background: #f5f5f5;
    font-size: 20px;
    cursor: pointer;
}

.qty-input {
    width: 50px;
    text-align: center;
    border: 1px solid #ddd;
    height: 35px;
    margin: 0 5px;
    font-size: 18px;
}

    </style>    

</head>
<body>
    <div class="container mt-4">
    <h4 class="fw-bold mb-3">Giỏ hàng của bạn</h4>

    <?php if (empty($items)): ?>
        <p>Giỏ hàng đang trống. <a href="index.php">Mua sắm ngay</a></p>
    <?php else: ?>
        <form action="cart.php?action=update" method="post">
            <table class="table align-middle">
                <thead>
                <tr>
                     <th>Chọn</th>   <!-- THÊM -->
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th width="120">Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <tr data-masp="<?php echo $it['masp']; ?>" data-gia="<?php echo $gia_km; ?>">
                        <td><input type="checkbox" class="chonsp"></td>  <!-- THÊM DÒNG NÀY -->
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo htmlspecialchars($it['hinhanh']); ?>"
                                     alt="" width="60" class="me-2">
                                <div>
                                    <strong><?php echo htmlspecialchars($it['tensp']); ?></strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php
                            $gia = (float)$it['giasp'];
                            $km = (float)$it['khuyenmai'];
                            $gia_km = $km > 0 ? $gia * (1 - $km) : $gia;
                            echo number_format($gia_km, 0, ',', '.'); ?> đ
                        </td>
                        <td>
                            <div class="qty-control">
                                <button type="button" class="qty-btn minus">-</button>
                                    <input type="text" 
                                           name="qty[<?php echo $it['masp']; ?>]" 
                                           class="qty-input"
                                           value="<?php echo $it['qty']; ?>" />
                                <button type="button" class="qty-btn plus">+</button>
                            </div>
                        </td>

                        <td><?php echo number_format($it['subtotal'], 0, ',', '.'); ?> đ</td>
                        <td>
                            <a href="cart.php?action=remove&amp;masp=<?php echo urlencode($it['masp']); ?>"
                               class="btn btn-sm btn-outline-danger">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Tổng cộng:</th>
                   <!-- <th><?php echo number_format($total, 0, ',', '.'); ?> đ</th>-->
                    <th><span id="tongtien">0</span> đ</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>

            <div class="d-flex justify-content-between">
                <div>
                    <a href="index.php" class="btn btn-outline-secondary">Tiếp tục mua sắm</a>
                    <a href="cart.php?action=clear" class="btn btn-outline-danger">Xóa giỏ hàng</a>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Cập nhật giỏ hàng</button>
                    <a href="#" class="btn btn-success">Thanh toán (demo)</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
<script>
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("plus")) {
        let input = e.target.parentElement.querySelector(".qty-input");
        input.value = parseInt(input.value) + 1;
    }

    if (e.target.classList.contains("minus")) {
        let input = e.target.parentElement.querySelector(".qty-input");
        let value = parseInt(input.value);
        if (value > 0) input.value = value - 1;
    }
});
//tính tổng tiền
function tinhTien() {
    let tong = 0;

    document.querySelectorAll("tr[data-masp]").forEach(row => {
        let checkbox = row.querySelector(".chonsp");
        if (checkbox.checked) {
            let gia = parseFloat(row.dataset.gia);
            let soluong = parseInt(row.querySelector(".qty-input").value);
            tong += gia * soluong;
        }
    });

    document.getElementById("tongtien").innerText = tong.toLocaleString();
}

document.addEventListener("change", function(e) {
    if (e.target.classList.contains("chonsp") ||
        e.target.classList.contains("qty-input")) {
        tinhTien();
    }
});
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
