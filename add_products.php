<?php
include 'components/connection.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    header('location: login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];


if (isset($_POST[ 'publish'])) {
$id = unique_id();
	
$name = $_POST['Name'] ;
$name = filter_var($name, FILTER_SANITIZE_STRING) ;
	
$price = $_POST['Giaban'];
$price = filter_var($price, FILTER_SANITIZE_STRING) ;
	
$content = $_POST['content'];
$content = filter_var ($content, FILTER_SANITIZE_STRING) ;
	
$status = 'Hoạt động';
	
$image = $_FILES['image']['name'];
$image = filter_var($image, FILTER_SANITIZE_STRING);
	
	
$image_size = $_FILES['image']['size'];
$image_tmp_name = $_FILES['image']['tmp_name'];
	
$image_folder = 'img/'.$image;

	$select_image = $conn->prepare("SELECT * FROM `sanpham` WHERE image= ?");
	$select_image->execute([$image]);
	
	
  if (isset($image)) {

    if ($select_image->rowCount() > 0) {
        $warning_msg[] = 'Trùng tên sản phẩm';

    } elseif ($image_size > 2000000) {
        $warning_msg[] = 'Kích thước hình ảnh quá lớn';

    } else {
        move_uploaded_file($image_tmp_name, $image_folder);
    }

} else {
    $image = '';
}


if ($select_image->rowCount() > 0 AND $image != '') {

    $warning_msg[] = 'Vui lòng đặt lại tên cho hình ảnh của bạn';

} else {

    $insert_product = $conn->prepare("
        INSERT INTO `sanpham` 
        (id,Name,Giaban,image,product_detail,Trangthai) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $insert_product->execute([$id,$name,$price,$image,$content,$status]);
	$success_msg[] = 'Thêm sản phẩm thành công';
}
}


if (isset($_POST[ 'draft'])) {
$id = unique_id();
	
$name = $_POST['Name'] ;
$name = filter_var($name, FILTER_SANITIZE_STRING) ;
	
$price = $_POST['Giaban'];
$price = filter_var($price, FILTER_SANITIZE_STRING) ;
	
$content = $_POST['content'];
$content = filter_var ($content, FILTER_SANITIZE_STRING) ;
	
$status = ' Không hoạt động';
	
$image = $_FILES['image']['name'];
$image = filter_var($image, FILTER_SANITIZE_STRING);
	
	
$image_size = $_FILES['image']['size'];
$image_tmp_name = $_FILES['image']['tmp_name'];
	
$image_folder = 'img/'.$image;

	$select_image = $conn->prepare("SELECT * FROM `sanpham` WHERE image= ?");
	$select_image->execute([$image]);
	
	
  if (isset($image)) {

    if ($select_image->rowCount() > 0) {
        $warning_msg[] = 'Trùng tên sản phẩm';

    } elseif ($image_size > 2000000) {
        $warning_msg[] = 'Kích thước hình ảnh quá lớn';

    } else {
        move_uploaded_file($image_tmp_name, $image_folder);
    }

} else {
    $image = '';
}


if ($select_image->rowCount() > 0 AND $image != '') {

    $warning_msg[] = 'Vui lòng đặt lại tên cho hình ảnh của bạn';

} else {

    $insert_product = $conn->prepare("
        INSERT INTO `sanpham` 
        (id,Name,Giaban,image,product_detail,Trangthai) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $insert_product->execute([$id,$name,$price,$image,$content,$status]);
	$success_msg[] = 'Lưu sản phẩm thành công';
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
			<h1>Thêm sản phẩm</h1>
		</div>
		<div class="title2">
			<a href="dashboard.php"	>Bảng điều khiển</a><span> / Thêm sản phẩm</span>
		</div>
		<section class="form-container">
			<h1 class="heading">THÊM SẢN PHẨM</h1>
			<form action="" method="post" enctype="multipart/form-data">
				<div class="input-fiefd">
					<label>Tên sản phẩm <sup>*</sup></label>
					<input type="text" name="Name" maxlength="100" required placeholder="Thêm tên sản phẩm">
				</div>
				<div class="input-fiefd">
					<label>Giá sản phẩm <sup>*</sup></label>
					<input type="number" name="Giaban" maxlength="100" required placeholder="Giá sản phẩm">
				</div>
				<div class="input-fiefd">
					<label>Chi tiết sản phẩm <sup>*</sup></label>
					<textarea name="content" required maxlength="10000" required placeholder ="Viết mô tả sản phẩm"></textarea>
				</div>
				<div class="input-fiefd">
					<label>Ảnh sản phẩm <sup>*</sup></label>
					<input type="file" name="image" maxlength="100" accept="img/*" required>
				</div>
				<div class="fiefd-btn">
					<button type="submit" name="publish" class="btn">Công bố sản phẩm </button>
					<button type="submit" name="draft" class="btn"> sản phẩm thử nghiệm</button>
				</div>
				
			</form>
		</section>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
	</script>
	
	<script type="text/javascript" src="script.js" ></script>
	
	<?php  include'components/alert.php';?>
</body>
</html>