<?php
include 'components/connection.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    header('location: login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];


if(isset($_POST['delete'])){
    $id = $_POST['id'];

    
    if($id == $admin_id){
        $error_msg[] = 'Không thể xóa tài khoản đang đăng nhập!';
    } else {
        $delete_admin = $conn->prepare("DELETE FROM `users` WHERE id = ? AND user_type = ?");
        $delete_admin->execute([$id, 'admin']);
        $success_msg[] = 'Xóa tài khoản thành công';
    }
}

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'] ? $_POST['password'] : null;
    if($password === null){
        // Nếu không nhập mật khẩu mới, giữ nguyên mật khẩu cũ
        $select_password = $conn->prepare("SELECT Matkhau FROM `users` WHERE id = ? AND user_type = ?");
        $select_password->execute([$id, 'admin']);
        $fetch_password = $select_password->fetch(PDO::FETCH_ASSOC);
        $password = $fetch_password['Matkhau'];
    } else {
        // Nếu nhập mật khẩu mới, hash nó
        $password = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE `users` SET Name = ?, Email = ?, Matkhau = ? WHERE id = ? AND user_type = ?");
        $update->execute([$name, $email, $password, $id, 'admin']);
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
			<h1>Tài khoản quản lý</h1>
	  </div>
		<div class="title2">
			<a href="dashboard.php"	>Bảng điều khiển</a><span> / Tài khoản quản lý</span>
		</div>
		<section class="admin_accounts">
			<h1 class="heading">Tài khoản quản lý</h1>
			<div class="box-container">
		<?php
$select_admin = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
$select_admin->execute(['admin']);

if ($select_admin->rowCount() > 0) {
    while ($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)) {
?>
<div class="box">
    <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $fetch_admin['id']; ?>">

		<div class="input-flex">
        <label for="name_<?= $fetch_admin['id']; ?>">Tên:</label>
        <input type="text" id="name_<?= $fetch_admin['id']; ?>" name="name" value="<?= $fetch_admin['Name']; ?>">
        
		<label for="password_<?= $fetch_admin['id']; ?>">Mật khẩu:</label>
        <input type="text" id="password_<?= $fetch_admin['id']; ?>" name="password" value="" placeholder="Nhập mật khẩu mới nếu muốn thay đổi">
						
        <label for="email_<?= $fetch_admin['id']; ?>">Email:</label>
        <input type="email" id="email_<?= $fetch_admin['id']; ?>" name="email" value="<?= $fetch_admin['Email']; ?>">

       
         </div>
        <div class="flex-btn">
            <button type="submit" name="update" class="btn">Cập nhật</button>
            <button type="submit" name="delete" class="btn" onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?');">Xóa</button>
        </div>
    </form>
</div>
<?php
    }
} else {
    echo '<div class="empty"><p>Chưa có tài khoản admin</p></div>';
}
?>



			</div>
			
		</section>
</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
	</script>
	
	<script type="text/javascript" src="file:///C|/xampp/htdocs/webcafe/admin/script.js" ></script>
	
	<?php include 'components/alert.php'; ?>
<script>
function updateAdmin(btn, id) {
    let form = btn.closest("form");

    let name = form.querySelector("input[name='name']").value;
    let email = form.querySelector("input[name='email']").value;
    let password = form.querySelector("input[name='password']").value;

    fetch('api/update_admin.php', {
        method: 'POST',
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id: id,
            name: name,
            email: email,
            password: password
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.msg);
    })
    .catch(err => {
        console.error(err);
        alert("Lỗi API");
    });
}


function deleteAdmin(btn, id) {
    if (!confirm("Bạn có chắc muốn xóa?")) return;

    fetch('api/delete_admin.php', {
        method: 'POST',
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id: id
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.msg);

        if (data.status) {
            btn.closest(".box").remove();
        }
    })
    .catch(err => {
        console.error(err);
        alert("Lỗi API");
    });
}
</script>
</body>
</html>