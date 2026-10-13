<?php
include 'components/connection.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    header('location: login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];



if (isset($_POST['delete_order'])){
	$orders_id = $_POST['orders_id'];
    $orders_id = filter_var($orders_id, FILTER_SANITIZE_STRING);
	
	$verify_delete = $conn->prepare("SELECT * FROM `dathang` WHERE id= ?");
    $verify_delete->execute([$orders_id]);
	
	if($verify_delete->rowCount() >0){
		$delete_order = $conn->prepare("DELETE  FROM `dathang` WHERE id=?");
        $delete_order->execute([$orders_id]);
		$success_msg[] ='Đơn hàng đã bị huỷ';
	}else{
		$warning_msg[] ='Không thể hủy đơn hàng';
	}
}

if (isset($_POST['update_order'])){
    $orders_id = $_POST['orders_id'];
    $orders_id = filter_var($orders_id, FILTER_SANITIZE_STRING);

    
    $update_payment = $_POST['update_payment'];
    $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);

    if(!empty($update_payment)){
        $update_pay = $conn->prepare("UPDATE `dathang` SET payment_status= ? WHERE id=?");
        $update_pay->execute([$update_payment, $orders_id]);    
        $success_msg[] ='Cập nhật đơn hàng thành công';
    } else {
        $warning_msg[] = 'Vui lòng chọn trạng thái thanh toán trước khi cập nhật';
    }
}


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
			<h1>Danh sách đặt hàng</h1>
		</div>
		<div class="title2">
			<a href="dashboard.php"	>Bảng điều khiển</a><span> / Danh sách đặt hàng</span>
		</div>
		<section class="order-container">
			<h1 class="heading">Danh sách đặt hàng</h1>
			<div class="box-container">
				<?php
				$select_orders =$conn->prepare("SELECT *FROM `dathang`");
				$select_orders->execute();
				
				if($select_orders->rowCount() >0){
					while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
						
				
				?>
				<div class="box">
					<div class="status" style="color: <?php if($fetch_orders['Trangthai'] == 'Đang tiến hành'){echo "green";}else{echo "red";} ?>"><?= $fetch_orders['Trangthai']?></div>
				<div class="detail">
					<p>Tên người dùng: <span><?= $fetch_orders['Name'] ?></span></p>
					<p>Mã: <span><?= $fetch_orders['id'] ?></span></p>
					<p>Ngày đặt: <span><?= $fetch_orders['date'] ?></span></p>
					<p>Số điện thoại: <span><?= $fetch_orders['DienThoai'] ?></span></p>
					<p>Email: <span><?= $fetch_orders['Email'] ?></span></p>
					<p>Tổng đơn: <span><?= $fetch_orders['Giaban'] ?></span></p>
					<p>Địa chỉ: <span><?= $fetch_orders['address'] ?></span></p>
				</div>
				
<form action="" method="post">
    <input type="hidden" name="orders_id" value="<?= $fetch_orders['id']; ?>">
    <select name="update_payment">
        <option disabled selected><?= $fetch_orders['payment_status']; ?></option>
        <option value="Đang chờ xử lý">Đang chờ xử lý</option>
        <option value="Hoàn thành">Hoàn thành</option>
    </select>
	<div class="flex-btn">
    <button type="submit" name="update_order" class="btn">Cập nhật đơn hàng</button>
	</div>
</form>


<form action="" method="post" onsubmit="return confirm('Bạn có chắc muốn xoá đơn này?')">
    <input type="hidden" name="orders_id" value="<?= $fetch_orders['id']; ?>">
	<div class="flex-btn">
    <button type="submit" name="delete_order" class="btn">Xoá đơn đặt hàng</button>
		</div>
</form>


			    </div>
				
				<?php
					}
				}else{
					echo ' <div class="empty"> <p>Đơn hàng chưa được đặt</p> </div> ';
				} 
				?>
			</div>
			
		</section>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
	</script>
	
	<script type="text/javascript" src="script.js" ></script>
	
	<?php  include'components/alert.php';?>
</body>
</html>