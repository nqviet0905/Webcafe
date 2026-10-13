<?php
include 'components/connection.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location: login.php');
}

if(isset($_POST['update'])){
    
    $post_id = $_GET['id'];

    $name = $_POST['Name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $price = $_POST['Giaban'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);

    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);

$status = $_POST['Trangthai'] ?? '';
$status = filter_var($status, FILTER_SANITIZE_STRING);

	 
	 $update_product = $conn->prepare("UPDATE `sanpham` SET Name=? , Giaban=?, product_detail=?, Trangthai=? WHERE id=?" );
	 $update_product->execute([$name,$price,$content,$status,$post_id]);
	 
	 $success_msg[] = 'Cập nhạt sản phẩm thành công';
	 
$old_image = $_POST['old_image'];	 
$image = $_FILES['image']['name'];
$image = filter_var($image, FILTER_SANITIZE_STRING);
	
	
$image_size = $_FILES['image']['size'];
$image_tmp_name = $_FILES['image']['tmp_name'];
	
$image_folder = 'img/'.$image;
	 
	 $select_image = $conn->prepare("SELECT * FROM `sanpham` WHERE image=?");
	 $select_image->execute([$image]);
	 
	 if(!empty($image)){
		 if($image_size >2000000){
			 $warning_msg[] = 'Kích thước hình ảnh quá lớn';
		 }elseif($select_image->rowCount() >0 AND $image != ''){
			 $warning_msg[] = 'Vui lòng đặt lại tên cho hình ảnh của bạn';
		 }else{
			 $update_image = $conn->prepare("UPDATE `sanpham` SET image=? WHERE id=?");
			 $update_image->execute([$image, $post_id]);
			 move_uploaded_file($image_tmp_name, $image_folder);
			 
			 if($old_image != $image AND $old_image != ''){
				 	unlink('img' .$old_image);
			 }
			 $success_msg[] = 'Cập nhật ảnh thành công';
		 }
	 }
 }


if (isset($_POST['delete'])){
    $p_id = $_POST['product_id'];
    $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);

   $delete_image = $conn->prepare("SELECT * FROM `sanpham` WHERE id= ?");
   $delete_image->execute([$p_id]);
	
	$fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
	
	if ($fetch_delete_image['image'] !=''){
			unlink('img/'.$fetch_delete_image['image']);
	}
    $delete_product = $conn->prepare("DELETE FROM `sanpham` WHERE id=?");
    $delete_product->execute([$p_id]);

	
	header('location:view_product.php');
    
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time();?>">
<title>Sửa sản phẩm</title>
</head>
<body>

<?php include 'components/admin_header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Sửa sản phẩm</h1>
    </div>

    <div class="title2">
        <a href="dashboard.php">Bảng điều khiển</a> <span>/ Sửa sản phẩm</span>
    </div>

    <section class="edit-post">
        <h1 class="heading">Sửa sản phẩm</h1>
     <?php
		$post_id = $_GET['id'];
		$select_products = $conn->prepare("SELECT * FROM `sanpham` WHERE id=?");
		$select_products->execute([$post_id]);
		
		if($select_products-> rowCount()>0){
			while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
				
		?>
		<div class="form-container">
			<form action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
				
				<input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
				
				<div class="input-field">
					<labe>Cập nhật trạng thái</labe>
					<select name="Trangthai">
						<option selected disabled value="<?= $fetch_products['Trangthai']; ?>"><?= $fetch_products['Trangthai']; ?></option>
						<option value="Hoạt động">Hoạt động</option>
						<option value="Không hoạt động">Không hoạt động</option>
					</select>
				</div>
				
				<div class="input-field">
					<labe>Tên sản phẩm</labe>
					<input type="text" name="Name" value="<?= $fetch_products['Name']; ?>">
				</div>
				
				<div class="input-field">
					<labe>Giá sản phẩm</labe>
					<input type="number" name="Giaban" value="<?= $fetch_products['Giaban']; ?>">
				</div>
				
				<div class="input-field">
					<labe>Thông tin sản phẩm</labe>
					<textarea name="content"><?= $fetch_products['product_detail']; ?></textarea>
				</div>
				
				<div class="input-field">
					<labe>Ảnh sản phẩm</labe>
					<input type="file" name="image" accept="image/*">
					<img src="img/<?= $fetch_products['image']; ?>">
				</div>
				
				<div class="flex-btn">
					<button type="submit" name="update" class="btn">Cập nhật</button>
					<a href="view_product.php" class="btn">Quay lại</a>
					<button type="submit" name="delete" class="btn">Xóa sản phẩm</button>
				</div>
			</form>
		
		</div>
		<?php
					}
				}else{
					echo
						'<div class="empty">
					<p>Chưa có sản phẩm nào được thêm vào <br> <a href="register.php" style="margin-top: 1.5rem;" class="btn">Thêm sản phẩm</a></p>
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
