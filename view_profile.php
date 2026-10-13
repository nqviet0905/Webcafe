<?php
include 'components/connection.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    header('location: login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'] ? $_POST['password'] : null;
    if($password === null){
       
        $select_password = $conn->prepare("SELECT Matkhau FROM `users` WHERE id = ? AND user_type = ?");
        $select_password->execute([$admin_id, 'admin']);
        $fetch_password = $select_password->fetch(PDO::FETCH_ASSOC);
        $password = $fetch_password['Matkhau'];
    } else {
        
        $password = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE `users` SET Name = ?, Email = ?, Matkhau = ? WHERE id = ? AND user_type = ?");
        $update->execute([$name, $email, $password, $admin_id, 'admin']);
    }
    $success_msg[] = 'Cập nhật thành công';
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
			<h1>Hồ sơ quản lý</h1>
		</div>
		<div class="title2">
			<a href="dashboard.php"	>Bảng điều khiển</a><span> / Hồ sơ quản lý</span>
		</div>
		<section class="view_profile">
			<h1 class="heading">Hồ sơ quản lý</h1>
			<div class="box-container">
				<?php
$select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? AND user_type = ?");
$select_user->execute([$admin_id, 'admin']);

if($select_user->rowCount() > 0){
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
?>
<div class="box">
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?= $fetch_user['id']; ?>">

	<div class="input-flex">
        <div class="inputBox">
            <span>Họ và tên</span>
            <input type="text" name="name" value="<?= $fetch_user['Name']; ?>" class="box">
            <span>Email</span>
            <input type="email" name="email" value="<?= $fetch_user['Email']; ?>" class="box">
            <span>Mật khẩu</span>
            <input type="text" name="password" value="" class="box" placeholder="Nhập mật khẩu mới nếu muốn thay đổi">

        </div>
   </div>
   <div class="flex-btn">
      <button type="submit" name="update" class="btn">Cập nhật</button>
   </div>

</form>
</div>
<?php
}else{
   echo '<div class="empty"><p>Không tìm thấy thông tin quản lý</p></div>';
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
document.getElementById("updateForm").addEventListener("submit", function(e){
    e.preventDefault();

    let formData = new FormData(this);

    fetch("http://localhost/api/admin_profile.php", {   
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            swal("Thành công!", data.msg, "success");
        } else {
            swal("Lỗi!", data.msg, "error");
        }
    })
    .catch(err => {
        swal("Lỗi!", "Không thể kết nối API", "error");
        console.log(err);
    });
});
</script>
</body>
</html>