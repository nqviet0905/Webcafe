<header class="header">
	<div class="flex" >
		<a href="dashboard.php" class="logo"><img src="img/logo.jpg"></a>
		<nav class="navbar">
			<a href="dashboard.php">Bảng điều khiển</a>
			<a href="add_products.php">Thêm sản phẩm</a>
			<a href="view_product.php">Xem sản phẩm</a>
			<a href="user_account.php">Tài khoản</a>
			<a href="admin_message.php">Đánh giá</a>
			
			
		</nav>
		<div class="icons">
			<i class="bx bxs-user" id="user-btn"></i>
			<i class="bx bx-list-plus" id="menu-btn"></i>
		</div>
		<div class="profile-detail">
			<?php
			$select_profile = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
			$select_profile-> execute(['admin']);
			
			if($select_profile-> rowCount() >0){
				$fetch_profile = $select_profile-> fetch(PDO::FETCH_ASSOC);
			?>
			<div class="Hoso">
				<p><?= $fetch_profile['Name'];?></p>
			</div>
			<div class="flex-btn">
				<a href="view_profile.php" class="btn">Hồ sơ</a>
				<a href="components/admin_logout.php" onclick=" return confirm('Đăng xuất khỏi trang web này');" class="btn">Đăng xuất</a>
			</div>
			<?php
			}
			?>
		</div>
	</div>

</header>