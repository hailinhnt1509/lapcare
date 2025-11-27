<?php
include 'includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? null;
$masp = $_GET['masp'] ?? null;

if ($action === 'add' && $masp) {
    $_SESSION['cart'][$masp] = ($_SESSION['cart'][$masp] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}

if ($action === 'remove' && $masp) {
    unset($_SESSION['cart'][$masp]);
    header("Location: cart.php");
    exit;
}

if ($action === 'clear') {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}

if ($action === 'update' && !empty($_POST['qty'])) {
    foreach ($_POST['qty'] as $id => $q) {
        $q = max(0, (int)$q);
        if ($q == 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $q;
        }
    }
    header("Location: cart.php");
    exit;
}

$items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('s', count($ids));

    $stmt = $conn->prepare("SELECT * FROM sanpham WHERE masp IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $qty = $_SESSION['cart'][$row['masp']];
        $gia = (float)$row['giasp'];
        $km = (float)$row['khuyenmai'];
        $gia_km = $km > 0 ? $gia * (1 - $km) : $gia;
        $row['qty'] = $qty;
        $row['subtotal'] = $gia_km * $qty;
        $items[] = $row;
        $total += $row['subtotal'];
    }
    $stmt->close();
}
?>

<div class="container mt-4">
    <h4 class="fw-bold mb-3">Giỏ hàng của bạn</h4>

    <?php if (empty($items)): ?>
        <p>Giỏ hàng đang trống. <a href="index.php">Mua sắm ngay</a></p>
    <?php else: ?>
        <form action="cart.php?action=update" method="post">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th width="120">Số lượng</th>
                    <th>Thành tiền</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
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
                            <input type="number" name="qty[<?php echo $it['masp']; ?>]"
                                   class="form-control form-control-sm"
                                   value="<?php echo $it['qty']; ?>" min="0">
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
                    <th><?php echo number_format($total, 0, ',', '.'); ?> đ</th>
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

<?php include 'includes/footer.php'; ?>
