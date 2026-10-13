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
if(isset($_POST['place_order'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['DienThoai'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

   $address = filter_var(
      $_POST['flat'].','.$_POST['street'].','.$_POST['city'].','.$_POST['country'].','.$_POST['pincode'],
      FILTER_SANITIZE_STRING
   );

   $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

   $varify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id=?");
   $varify_cart->execute([$user_id]);

   if($varify_cart->rowCount() == 0){
      $warning_msg[] = 'Giỏ hàng trống';
   }else{

      while($f_cart = $varify_cart->fetch(PDO::FETCH_ASSOC)){
// LẤY GIÁ SẢN PHẨM TẠI THỜI ĐIỂM ĐẶT HÀNG
$select_price = $conn->prepare("SELECT Giaban FROM sanpham WHERE id = ?");
$select_price->execute([$f_cart['product_id']]);
$fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

$giaban = $fetch_price['Giaban'];

$insert_order = $conn->prepare(
 "INSERT INTO dathang
 (id,user_id,Name,DienThoai,Email,address,address_type,method,
  product_id,Giaban,qty,date,Trangthai,payment_status)
 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
);


     $insert_order->execute([
   unique_id(),
   $user_id,
   $name,
   $number,
   $email,
   $address,
   $address_type,
   $method,
   $f_cart['product_id'],
   $giaban,
   $f_cart['qty'],
   date('Y-m-d H:i:s'),
   'Chờ xử lý',
   'Chưa thanh toán'
]);

      }

      $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id=?");
      $delete_cart->execute([$user_id]);

      header('location: order.php');
      exit;
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
    <title>Green Coffee - checkout page</title>
</head>
<body>
<?php include 'components/header.php'; ?>
<div class="main">
    <div class="banner">
        <h1>Thông tin chi tiết</h1>
    </div>
	<div class="title2">
		<a href="home.php">Trang chủ </a><span>Thông tin chi tiết</span>
	</div>
	<section class="Checkout">
      <div class="title">
		   <img src="img/download.png" class="logo">
		  <h1>Thông tin chi tiết</h1>
		</div> 
		  <div class="row">
		       <form method="post">
			      <h3>Chi tiết thanh toán</h3>
				   <div class="flex">
				       <div class="box">
					       <div class="input-field">
						     <p>Tên<span>*</span></p>
							   <input type="text" name="name" required maxlength="50" placeholder="Nhập tên" class="input">
						   </div>
						   <div class="input-field">
						     <p>Số Điện Thoại<span>*</span></p>
							   <input type="number" name="DienThoai" required maxlength="10" placeholder="Nhập SĐT" class="input">
						   </div>
						   <div class="input-field">
						     <p>Email<span>*</span></p>
							   <input type="email" name="email" required maxlength="50" placeholder="Email" class="input">
						   </div>
						   <div class="input-field">
						     <p>phương thức thanh toán<span>*</span></p>
							   <select name="method" class="input">
							   <option value="cash on delivery">Tiền mặt khi giao hàng</option>
							   <option value="credit or debit card">Thanh toán thẻ</option>
							   <option value="net banking"> Banking</option>
								<option value="paytm">Ví điện tử</option> 
							   </select>
						   </div>
						    <div class="input-field">
						      <p>Loại địa chỉ<span>*</span></p>
							   <select name="address_type" class="input">
							   <option value="home">Nhà</option>
							   <option value="office">Cơ quan</option>
							   </select>
						   </div>
					   </div> 
					   <div class="input-field">
					       <p>địa chỉ 01<span>*</span></p>
						   <input type="text" name="flat" required maxlength="50" placeholder="Ví dụ: số căn hộ và số tòa nhàr" class="input">
					   </div>
					    <div class="input-field">
					       <p>địa chỉ 02<span>*</span></p>
						   <input type="text" name="street" required maxlength="50" placeholder="ví dụ: tên đường" class="input">
					   </div>
					    <div class="input-field">
					       <p>Tên thành phố<span>*</span></p>
						   <input type="text" name="city" required maxlength="50" placeholder="..." class="input">
					   </div>
					   <div class="input-field">
					       <p>Quốc gia<span>*</span></p>
						   <input type="text" name="country" required maxlength="50" placeholder="..." class="input">
					   </div>
					   <div class="input-field">
					       <p>Mã Pin<span>*</span></p>
						   <input type="text" name="pincode" required maxlength="6" placeholder="110022" min="0" max="9999999" class="input">
					   </div>
				   </div>
				   <button type="submit" name="place_order" class="btn">Đặt hàng</button>
			  </form>
			  <div class="summary">
				  <h3>Giỏ hàng của tôi</h3>
				  <div class=" box-container">
<?php
$grand_total = 0;

if(isset($_GET['get_id'])){
    $select_get = $conn->prepare("SELECT * FROM sanpham WHERE id=?");
    $select_get->execute([$_GET['get_id']]);
    while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
        $sub_total = $fetch_get['Giaban'];
        $grand_total += $sub_total;
?>
    <div class="flex">
        <img src="img/<?=$fetch_get['image'];?>" class="image">
        <div>
            <h3 class="name"><?=$fetch_get['Name'];?></h3>
            <p class="price"><?=$fetch_get['Giaban'];?></p>
        </div>
    </div>
<?php
    }
} else {
    $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $select_cart->execute([$user_id]);

    if($select_cart->rowCount() > 0){
        while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
            $select_product = $conn->prepare("SELECT * FROM sanpham WHERE id=?");
            $select_product->execute([$fetch_cart['product_id']]);
            if($select_product->rowCount() > 0){
                $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
                $sub_total = $fetch_cart['qty'] * $fetch_product['Giaban'];
                $grand_total += $sub_total;
?>
                <div class="flex">
                    <img src="img/<?=$fetch_product['image'];?>">
                    <div>
                        <h3 class="name"><?=$fetch_product['Name'];?></h3>
                        <p class="price"><?=$fetch_product['Giaban'];?> x <?=$fetch_cart['qty'];?></p>
                    </div>
                </div>
<?php
            }
        }
    } else {
        echo '<p class="empty">Giỏ hàng của bạn trống</p>';
    }
}
?>
<div class="grand-total"><span>Tổng tiền thanh toán: </span><?=$grand_total ?> nghìn đồng </div>

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