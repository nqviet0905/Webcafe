<?php
include 'components/connection.php';

if(isset($_POST['register'])){

    $id = unique_id();

    
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    
    $cpass = $_POST['cpass'];
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $mk = password_hash($cpass, PASSWORD_DEFAULT);

    $select_admin = $conn->prepare("SELECT * FROM `users` WHERE Email = ?");
    $select_admin->execute([$email]);

    if($select_admin->rowCount() > 0){
        $warning_msg[] = 'Email đã tồn tại';
    } else {
        if($pass != $cpass){
            $warning_msg[] = 'Xác nhận mật khẩu không khớp';
        } else {
            $insert_admin = $conn->prepare("INSERT INTO `users`(id, Name, Email, Matkhau, user_type) VALUES(?,?,?,?,?)");
            $insert_admin->execute([$id, $name, $email, $mk, 'admin']);


            $success_msg[] = 'Đăng ký thành công';
        }
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
			<h1>Thêm tài khoản ADMIN</h1>
		</div>
		<div class="title2">
			<a href="dashboard.php"	>Bảng điều khiển</a><span> / Thêm tài khoản ADMIN</span>
		</div>
		<section>
			<div class="form-container" id="admin_login">
				<form action="" method="post" enctype="multipart/form-data">
    <h3>Đăng Ký Tài Khoản</h3>

    <div class="input-field">
        <label>Nhập tên đăng nhập <sup>*</sup></label>
        <input type="text" name="name" maxlength="20" required placeholder="Nhập tên đăng nhập:" oninput="this.value=this.value.replace(/[ ]/g,'')">
    </div>

    <div class="input-field">
        <label>Nhập email <sup>*</sup></label>
        <input type="email" name="email" maxlength="50" required placeholder="Nhập email:" >
    </div>

    <div class="input-field">
        <label>Nhập mật khẩu <sup>*</sup></label>
        <input type="password" name="pass" maxlength="20" required placeholder="Nhập mật khẩu:" >
    </div>

    <div class="input-field">
        <label>Nhập lại mật khẩu<sup>*</sup></label>
        <input type="password" name="cpass" maxlength="20" required placeholder="Nhập lại mật khẩu:" >
    </div>

    <button type="submit" name="register" class="btn">Đăng ký</button>
   
</form>	
			</div>
		</section>
	</div>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
	</script>
	
	<script type="text/javascript" src="script.js" ></script>
	
	<?php include 'components/alert.php'; ?>

</body>
</html>