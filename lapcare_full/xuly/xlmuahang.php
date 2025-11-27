<?php
session_start();
include('connect.php');
// ===== XỬ LÝ THÊM VÀO GIỎ =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['matk'])) {//nếu không tồn tại session matk
        // Lưu lại trang hiện tại để login xong quay lại
        $_SESSION['redirect_url'] = "detail.php?masp=" . $_POST['masp'];
        echo "<script>alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ!');window.location='login.php';</script>";

        exit;
    }

    $matk = $_SESSION['matk'];
    $masp = $_POST['masp'];
    $soluong = (int)$_POST['soluong'];//lấy giá trị nguyên của số lượng truyền từ form thông qua phương thức post
    $ngaychon = date('Y-m-d');

    // kiểm tra có sản phẩm đó trong giỏ chưa
    $stmt = $conn->prepare("SELECT soluong FROM giohang WHERE matk=? AND masp=?");
    $stmt->bind_param("ss", $matk, $masp);
    $stmt->execute();
    $result = $stmt->get_result();
   if ($result->num_rows > 0) {

        // Đã có → tăng số lượng
        $stmt = $conn->prepare("UPDATE giohang SET soluong = soluong + ? WHERE matk=? AND masp=?");
        $stmt->bind_param("iss", $soluong, $matk, $masp);
        $stmt->execute();

    } else {

        // Chưa có → thêm bản ghi mới
        $stmt = $conn->prepare("
            INSERT INTO giohang (matk, masp, ngaychon, soluong)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("sssi", $matk, $masp, $ngaychon, $soluong);
        $stmt->execute();
    }

    echo "<script>alert('Đã thêm sản phẩm vào giỏ hàng!');window.location='cart.php';</script>";
    exit;
}
?>