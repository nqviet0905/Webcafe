<?php
include 'components/connection.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    header('location: login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];

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
			<h1>Tài khoản người dùng</h1>
		</div>
		<div class="title2">
			<a href="dashboard.php"	>Bảng điều khiển</a><span> / Tài khoản người dùng</span>
		</div>
		<section class="user_account">
			<h1 class="heading">Tài khoản người dùng</h1>
			<div class="box-container">
				<?php
				$select_user = $conn->prepare("SELECT * FROM `users`");
				$select_user->execute();
				
				if($select_user->rowCount() >0) {
					while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
						$user_id =$fetch_user['id'];
				
				?>
				<div class="box">
					
					    <p>Mã: <span><?= $user_id; ?></span></p>
                        <p>Tên người dùng: <span><?php echo $fetch_user['Name']; ?></span></p>
                        <p>Email: <span><?php echo $fetch_user['Email']; ?></span></p>
				</div>
				<?php
					}
				}else{
					echo ' <div class="empty"> <p>Người dùng chưa đăng ký</p> </div> ';
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