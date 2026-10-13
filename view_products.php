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

    $varify_wishlist = $conn->prepare(
        "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?"
    );
    $varify_wishlist->execute([$user_id, $product_id]);

    if ($varify_wishlist->rowCount() > 0) {
        $warning_msg[] = 'Sản phẩm đã có trong mục yêu thích';
    } else {
        $select_Giaban = $conn->prepare(
            "SELECT Giaban FROM sanpham WHERE id = ? LIMIT 1"
        );
        $select_Giaban->execute([$product_id]);
        $fetch_Giaban = $select_Giaban->fetch(PDO::FETCH_ASSOC);

        $insert_wishlist = $conn->prepare(
            "INSERT INTO wishlist (id, user_id, product_id, Giaban)
             VALUES (?,?,?,?)"
        );
        $insert_wishlist->execute([
            $id,
            $user_id,
            $product_id,
            $fetch_Giaban['Giaban']
        ]);

        $success_msg[] = 'Đã thêm vào mục yêu thích';
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
    "INSERT INTO cart (user_id, product_id, qty) VALUES (?,?,?)"
);
	$insert_cart->execute([$user_id, $product_id, $qty]);
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
        <h1>Cửa hàng</h1>
    </div>
	<div class="title2">
		<a href="home.php">Trang chủ </a><span>/ Cửa hàng của chúng tôi</span>
	</div>
	<section class="sanpham">
   <div class="box-container">
      <?php
         $select_sanpham = $conn->prepare("SELECT * FROM `sanpham`");
			 $select_sanpham->execute();
if ($select_sanpham->rowCount() > 0) {
   while ($fetch_sanpham = $select_sanpham->fetch(PDO::FETCH_ASSOC)) {
	   
	   
?>
<form action="" method="post" class="box">
   <img src="img/<?= $fetch_sanpham['image']; ?>" class="img">

   <div class="button">
      <button type="button" class="add-cart" data-id="<?= $fetch_sanpham['id']; ?>"><i class="bx bx-cart"></i></button>
      <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
      <a href="view_page.php?get_id=<?= $fetch_sanpham['id']; ?>" class="bx bxs-show"></a>
   </div>

   <h3 class="name"><?= $fetch_sanpham['Name']; ?></h3>

   <input type="hidden" name="product_id" value="<?= $fetch_sanpham['id']; ?>">

   <div class="flex">
      <p class="Giaban">Giá bán: <?= $fetch_sanpham['Giaban']; ?> nghìn đồng</p>
      <input type="number" name="qty" required min="1" value="1" max="99" class="qty">
   </div>

   <a href="order.php?get_id=<?= $fetch_sanpham['id']; ?>" class="btn">Mua ngay</a>

</form>
<?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
?>
   </div>
</section>
		<?php include 'components/footer.php'; ?>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	<script src="script.js?v=<?php echo time(); ?>"></script>
	<?php include 'components/alert.php'; ?>
</body>
</html>
