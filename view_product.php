       <?php
include 'components/connection.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    header('location: login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];


 if (isset($_POST[ 'delete'])){
	 $p_id = $_POST['product_id'];
	 $p_id = filter_var($p_id,FILTER_SANITIZE_STRING);
	 
	 $delete_product = $conn->prepare("DELETE FROM `sanpham` WHERE id=?");
	 $delete_product->execute([$p_id]);
	 
	 $success_msg[] ='Sản phẩm đã xóa thành công';
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
			<h1>Tất cả sản phẩm</h1>
		</div>
		<div class="title2">
			<a href="dashboard.php"	>Bảng điều khiển</a><span> / Tất cả sản phẩm</span>
		</div>
		<section class="show-post">
			<h1 class="heading">Tất cả sản phẩm</h1>
			<div class="box-container">
				<?php
				$select_products = $conn->prepare("SELECT * FROM `sanpham`");
				$select_products->execute();	
				
				if($select_products-> rowCount()>0){
					while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC))
					{
						
					
				 
				?>
				<form action="" method="post" class="box">
					<input type="hidden" name="product_id" value="<?= $fetch_products['id'];?>">
					<?php if($fetch_products['image'] !=''){?>
					<img src="img/<?= $fetch_products['image']; ?>" class="image">
					
				<?php } ?>
					<div class="Trangthai" style="color: <?php if($fetch_products['Trangthai']=='Hoạt động'){echo "green";}else{echo"red";} ?>;"><?= $fetch_products['Trangthai'];?></div>
					
					<div class="Giaban"><?= $fetch_products['Giaban'];?></div>
					<div class="title"><?= $fetch_products['Name'];?></div>
					<div class="flex-btn">
						<a href="edit_product.php?id=<?= $fetch_products['id'];?>" class="btn">Sửa</a>
						<button type="button" class="btn" onclick="deleteProduct(<?= $fetch_products['id'];?>)">Xóa</button>
						<a href="read_product.php?post_id=<?= $fetch_products['id'];?>" class="btn">Xem sản phẩm</a>
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
				
			</div>
			
		</section>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
	</script>
	
	<script type="text/javascript" src="script.js" ></script>
	
	<?php  include'components/alert.php';?>
	<script>
function deleteProduct(id) {
    if (!confirm("Bạn có chắc muốn xóa sản phẩm?")) return;

    fetch('http://localhost/api/delete_product.php', {
        method: 'POST',
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            product_id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.msg);

        if (data.status === true) {
            
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Lỗi kết nối API");
    });
}
</script>
</body>
</html>