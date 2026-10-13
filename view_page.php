<?php
include 'components/connection.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php");
}
//adding products in wishlish
if (isset($_POST['add_to_wishlist'])) {
    $id = unique_id();
    $product_id = $_POST['product_id'];

    $varify_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
    $varify_wishlist->execute([$user_id, $product_id]);

    $cart_num = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
    $cart_num->execute([$user_id, $product_id]);

    if ($varify_wishlist->rowCount() > 0) {
        $warning_msg[] = 'Sản phẩm đã được yêu thích';
    } else if ($cart_num->rowCount() > 0) {
        $warning_msg[] = 'Sản phẩm đã được yêu thích';
    } else {
        $select_Giaban = $conn->prepare("SELECT * FROM `sanpham` WHERE id = ? LIMIT 1");
        $select_Giaban->execute([$product_id]);
        $fetch_Giaban = $select_Giaban->fetch(PDO::FETCH_ASSOC);

		$insert_wishlist = $conn->prepare("INSERT INTO wishlist (id, user_id, product_id, Giaban) VALUES (?,?,?,?)");
		$insert_wishlist->execute([$id, $user_id, $product_id, $fetch_Giaban['Giaban']]);
		$success_msg[] = 'Sản phẩm đã được yêu thích';
    }
}
//adding products in cart
if (isset($_POST['add_to_cart'])) {
    $id = unique_id();
    $product_id = $_POST['product_id'];

    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    $varify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
    $varify_cart->execute([$user_id, $product_id]);
	
	$max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$max_cart_items->execute([$user_id]);

if ($varify_cart->rowCount() > 0) {
    $warning_msg[] = 'Sản phẩm đã được thêm vào giỏ hàng';
} else if ($max_cart_items->rowCount() > 20) {
    $warning_msg[] = 'Giỏ hàng đã đầy!';
} else {
    $select_Giaban = $conn->prepare("SELECT * FROM `sanpham` WHERE id = ? LIMIT 1");
    $select_Giaban->execute([$product_id]);
    $fetch_Giaban = $select_Giaban->fetch(PDO::FETCH_ASSOC);

    $insert_cart = $conn->prepare(
        "INSERT INTO cart (id, user_id, product_id, Giaban, qty) VALUES (?,?,?,?,?)"
    );
    $insert_cart->execute([$id, $user_id, $product_id, $fetch_Giaban['Giaban'], $qty]);
    $success_msg[] = 'Sản phẩm đã được thêm vào giỏ hàng';
    }
}

?>
<style type="text/css">
<?php include 'style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>Green Coffee - shop page</title>
</head>
<body>
<?php include 'components/header.php'; ?>
<div class="main">
    <div class="banner">
        <h1>Chi tiết sản phẩm</h1>
    </div>
	<div class="title2">
		<a href="home.php">Trang chủ </a><span>/ Chi tiết sản phẩm</span>
	</div>
	<section class="view_page">
     <?php
if (isset($_GET['get_id'])) {
    $pid = $_GET['get_id'];

    $select_sanpham = $conn->prepare("SELECT * FROM `sanpham` WHERE id = ?");
    $select_sanpham->execute([$pid]);

    if ($select_sanpham->rowCount() > 0) {
        $fetch_sanpham = $select_sanpham->fetch(PDO::FETCH_ASSOC);
?>
<form method="post">
    <img src="img/<?php echo htmlspecialchars($fetch_sanpham['image']); ?>">

    <div class="product-box">
        <div class="price"><?php echo $fetch_sanpham['Giaban']; ?></div>
	<div class="name"><?php echo $fetch_sanpham['Name']; ?></div>
		
	<div class="product-detail">
    <?php echo nl2br(htmlspecialchars($fetch_sanpham['product_detail'])); ?>
	</div>
        <input type="hidden" name="product_id" value="<?php echo $fetch_sanpham['id']; ?>">

        <div class="button">
            <button type="submit" name="add_to_wishlist" class="btn">
                Thêm vào mục yêu thích <i class="bx bx-heart"></i>
            </button>
            <input type="hidden" name="qty" value="1">
            <button type="submit" name="add_to_cart" class="btn">
                Thêm vào giỏ hàng <i class="bx bx-cart"></i>
            </button>
        </div>
   	 </div>
	</form>
	<?php
		}
	}
		?>
</section>
		<?php include 'components/footer.php'; ?>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	<script src="components/script.js"></script>
	<?php include 'components/alert.php'; ?>
</body>
</html>