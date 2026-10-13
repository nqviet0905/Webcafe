<?php
include 'components/connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header('location: login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];


// LẤY THÔNG TIN ADMIN
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
$select_profile->execute(['admin']);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<meta name= "viewport" content="width= device-width, initial-scale=1.0" >
	
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time();?>">
<title></title>
</head>
<body>
	<?php  include'components/admin_header.php';?>
	<div  class="main">
		<div class="banner">
			<h1>Bảng điều khiển</h1>
		</div>
		<div class="title2">
			<a href="dashboard.php"	>Trang chủ</a><span> / Bảng điều khiển</span>
		</div>
		<section class="dashboard">
			<h1 class="heading">Bảng điều khiển</h1>
			<div class="box-container">
				<div class="box">
					<h3>Chào mừng bạn!</h3>
					<p><?= $fetch_profile['Name']; ?></p>
					<a href ="view_profile.php" class="btn">Hồ sơ</a>
				</div>
				<div class="box">
					<?php
					$select_product = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
					$select_product-> execute(['admin']);
					$num_of_products = $select_product-> rowCount();
					?>
					<h3><?= $num_of_products;?></h3>
					<p>Thêm tài khoản ADMIN</p>
					<a href="register_admin.php" class="btn">Thêm tài khoản ADMIN</a>
				</div>
				<div class="box">
					<?php
					$select_active_product = $conn->prepare("SELECT *FROM `sanpham` WHERE Trangthai = ?");
					$select_active_product -> execute(['Hoạt động']);
					$num_of_active_product  = $select_active_product -> rowCount();
					?>
					<h3><?= $num_of_active_product;?></h3>
					<p>Tổng số sản phẩm </p>
					<a href="view_product.php" class="btn">Xem số sản phẩm </a>
				</div>
				
				<div class="box">
					<?php
					$select_users = $conn->prepare("SELECT *FROM `users`");
					$select_users-> execute();
					$num_of_users = $select_users-> rowCount();
					?>
					<h3><?= $num_of_users;?></h3>
					<p>Tài khoản người dùng</p>
					<a href="user_account.php" class="btn">Xem tài khoản người dùng</a>
				</div>
				<div class="box">
					<?php
					$select_admin = $conn->prepare("SELECT *FROM `users` WHERE user_type = ?");
					$select_admin-> execute(['admin']);
					$num_of_admin = $select_admin-> rowCount();
					?>
					<h3><?= $num_of_admin;?></h3>
					<p>Tài khoản admin</p>
					<a href="admin_account.php" class="btn">Tài khoản admin</a>
				</div>
				<div class="box">
					<?php
					$select_orders = $conn->prepare("SELECT *FROM `Dathang`");
					$select_orders-> execute();
					$num_of_orders = $select_orders-> rowCount();
					?>
					<h3><?= $num_of_orders;?></h3>
					<p>Tổng số đơn đặt hàng</p>
					<a href="orders.php" class="btn">Xem số đơn đặt hàng</a>
				</div>
				<div class="box">
					<?php
					$select_confirm_orders = $conn->prepare("SELECT *FROM `Dathang` WHERE payment_status =?");
					$select_confirm_orders-> execute(['Hoàn thành']);
					$num_of_confirm_orders = $select_confirm_orders-> rowCount();
					?>
					<h3><?= $num_of_confirm_orders;?></h3>
					<p>Tổng số đơn đặt hàng đã hoàn thành</p>
					<a href="all_orders.php" class="btn">Xem Tổng số đơn đặt hàng đã hoàn thành</a>
				</div>
				<div class="box">
					<?php
					$select_canceled_orders = $conn->prepare("SELECT *FROM `Dathang` WHERE payment_status =?");
					$select_canceled_orders-> execute(['Đang chờ xử lý']);
					$num_of_canceled_orders = $select_canceled_orders-> rowCount();
					?>
					<h3><?= $num_of_canceled_orders;?></h3>
					<p>Tổng số đơn đặt hàng chưa hoàn thành</p>
					<a href="all_confirm_orders.php" class="btn">Xem Tổng số đơn đặt hàng chưa hoàn thành</a>
				</div>
			</div>
			
		</section>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
	</script>
	
	<script type="text/javascript" src="script.js" ></script>
	
	<?php include 'components/alert.php'; ?>

</body>
</html>