<?php
include 'components/connection.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location: login.php');
    exit();
}

$get_id = $_GET['post_id'];

if (isset($_POST['delete'])){
    $p_id = $_POST['product_id'];
    $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);

    $delete_image = $conn->prepare("SELECT * FROM `sanpham` WHERE id= ?");
    $delete_image->execute([$p_id]);

    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

    if ($fetch_delete_image['image'] != ''){
        unlink('img/'.$fetch_delete_image['image']);
    }

    $delete_product = $conn->prepare("DELETE FROM `sanpham` WHERE id=?");
    $delete_product->execute([$p_id]);

    header('location:view_product.php');
    exit();
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time();?>">
<title>Xem sản phẩm</title>
</head>
<body>

<?php include 'components/admin_header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Xem sản phẩm</h1>
    </div>

    <div class="title2">
        <a href="dashboard.php">Bảng điều khiển</a> <span>/ Xem sản phẩm</span>
    </div>

    <section class="read-post">
        <h1 class="heading">Xem sản phẩm</h1>

       <?php
				$select_products = $conn->prepare("SELECT * FROM `sanpham`");
				$select_products->execute();	
				
				if($select_products-> rowCount()>0){
					while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC))
					{
						
					
				 
				?>
				<form action="" method="post">	
					<input type="hidden" name="product_id" value="<?= $fetch_products['id'];?>">
					
					<div class="Trangthai" style="color: <?php if($fetch_products['Trangthai']=='Hoạt động'){echo "green";}else{echo"red";} ?>;"><?= $fetch_products['Trangthai'];?></div>
					
					
					<?php if($fetch_products['image'] !=''){?>
					<img src="img/<?= $fetch_products['image']; ?>" class="image">
					<?php } ?>
					  <div class="Giaban"><?= $fetch_products['Giaban'];?></div>
		             <div class="title"><?= $fetch_products['Name'];?></div>
		             <div class="content"><?= $fetch_products['product_detail'];?></div>
					<div class="flex-btn">
						<a href="view_product.php?id= $get_id; ?>" class="btn">Quay lại</a>
					</div>
				</form>
				<?php
					}
				}else{
					echo
						'<div class="empty">
					<p>Chưa có sản phẩm nào được thêm vào <br> <a href="add_products.php" style="margin-top: 1.5rem;" class="btn">Thêm sản phẩm</a></p>
				</div>';
				}
				
				?>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="script.js"></script>

<?php include 'components/alert.php'; ?>

</body>
</html>
