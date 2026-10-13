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
if (isset($_GET['get_id'])) {
	$get_id = $_GET['get_id'];
}else{
	$get_id = '';
	header('location:order.php');
}
if (isset($_POST['cancle'])){
	$update_order = $conn->prepare("UPDATE `dathang` SET Trangthai = ? WHERE id=?");
	$update_order->execute(['canceled', $get_id]);
	header('location:order.php');
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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>Green Coffee - order detail page</title>
</head>
<body>
<?php include 'components/header.php'; ?>
<div class="main">
    <div class="banner">
        <h1>Chi tiết đặt hàng</h1>
    </div>
	<div class="title2">
		<a href="home.php">Trang chủ </a><span>/ Chi tiết đặt hàng</span>
	</div>
	<section class="order-detail">
     <div class="title">
	   <img src="img/download.png" class="logo">
		 <h1>Chi tiết đặt hàng</h1>
		 
	   </div>
	   <div class="box-container">
	  		<?php 
		   		$grand_total=0;
		   		$select_orders = $conn->prepare("SELECT * FROM `dathang` WHERE id=? LIMIT 1");
		      
		   		$select_orders->execute([$get_id]);
		   if ($select_orders->rowCount()>0) {
			   while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)){
				   $select_product = $conn->prepare("SELECT * FROM `sanpham` WHERE id=? LIMIT 1");
				   $select_product->execute([$fetch_order['product_id']]);
				   if ($select_product->rowCount()>0) {
					   while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
						   $sub_total= ($fetch_order['Giaban']* $fetch_order['qty']);
						   $grand_total += $sub_total;
						   
						   ?>
		   <div class="box">
			   <div class="col">
			    <p class="title"><i class="bi bi-calender-fill"></i><?= $fetch_order['date']; ?></p>  
				<img src="img/<?= $fetch_product['image']; ?>" class="image">
				<p class="price"><?= $fetch_product['Giaban']; ?> x <?= $fetch_order['qty']; ?></p>
			    <h3 class="name"><?= $fetch_product['Name']; ?></h3>
				<p class="grand-total">Tổng số tiền :       <span> <?= $fetch_product['Giaban']; ?>  nghìn đồng </span></p>
			   </div>
			   <div class="col">
				   <p class="title">Địa chỉ</p>
				   <p class="user"><i class="bi bi-person-bounding-box"></i><?= $fetch_order['Name']; ?></p>
				   <p class="user"><i class="bi bi-phone"></i><?= $fetch_order['DienThoai']; ?></p>
				   <p class="user"><i class="bi bi-envelope"></i><?= $fetch_order['Email']; ?></p>
				   <p class="user"><i class="bi bi-pin-map-fill"></i><?= $fetch_order['address']; ?></p>
				   <p class="titlle">Trạng thái</p>
				   <p class="status" style="color:<?php if ($fetch_order['Trangthai']=='Đã giao hàng'){echo 'green';}elseif($fetch_order['Trangthai']=='Đã hủy bỏ') {echo 'red';}else{echo 'orange';}?>"><?=$fetch_order['Trangthai'] ?></p>
				   <?php if ($fetch_order['Trangthai']=='Đã hủy bỏ') { ?> 
				   <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn">Đặt hàng</a>
						   <?php }else{ ?>
				   				<form method="post">
				   					<button type="submit" name="cancle" class="btn" onClick="return confirm('Bạn có chắc hủy đơn hàng này chứ ?')">Hủy bỏ đặt hàng</button>
				   </form>
				   		   <?php } ?>
			   </div>
			</div>
		   <?php
					   }
				   }else{
					    echo '<p class="empty">Không có đơn hàng nào!</p>';
				   }
			   }
			   
		   }else{
			    echo '<p class="empty">Không tìm thấy đơn hàng</p>';
		   }
		   ?>
	   </div>
   </div>
</section>
		<?php include 'components/footer.php'; ?>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	<script src="script.js"></script>
	<?php include 'components/alert.php'; ?>
</body>
</html>
