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
?>

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
    <title>Green Coffee - Trang yêu thích</title>
</head>
<body>
<?php include 'components/header.php'; ?>
<div class="main">
    <div class="banner">
        <h1>Danh sách yêu thích của tôi</h1>
    </div>
	<div class="title2">
		<a href="home.php">Trang Chủ</a><span>/ Danh sách yêu thích</span>
	</div>
	<section class="products">
		<h1 class="title">Sản phẩm đã thêm vào danh sách yêu thích</h1>
		<div class="box-container" id="wishlist-items">
		</div>
	</section>
		<?php include 'components/footer.php'; ?>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	<script src="script.js"></script>

	<script>
		// Tải danh sách yêu thích từ API
		function loadWishlist() {
			fetch('api/get_wishlist.php')
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					renderWishlist(data.data);
				} else {
					document.getElementById('wishlist-items').innerHTML = '<p class="empty">Danh sách yêu thích trống !</p>';
				}
			})
			.catch(error => {
				console.error('Error:', error);
				document.getElementById('wishlist-items').innerHTML = '<p class="empty">Lỗi khi tải dữ liệu!</p>';
			});
		}

		// Hiển thị danh sách yêu thích
		function renderWishlist(wishlistItems) {
			const wishlistDiv = document.getElementById('wishlist-items');

			if (!wishlistItems || wishlistItems.length === 0) {
				wishlistDiv.innerHTML = '<p class="empty">Danh sách yêu thích trống !</p>';
				return;
			}

			let html = '';
			wishlistItems.forEach(item => {
				html += `
					<div class="box">
						<img src="img/${item.image}" alt="Ảnh sản phẩm">

						<div class="button">
							<button type="button" onclick="addToCartWishlist(${item.product_id})"><i class="bx bx-cart"></i></button>

							<a href="view_page.php?pid=${item.product_id}" class="bx bxs-show"></a>

								<button type="button" onclick="deleteWishlistItem(${item.product_id})">
								<i class="bx bx-x"></i>
							</button>
						</div>

						<h3 class="name">${item.name}</h3>
						<p class="price">Giá: ${item.price} nghìn đồng</p>

						<a href="checkout.php?get_id=${item.product_id}" class="btn">Mua ngay</a>
					</div>
				`;
			});

			wishlistDiv.innerHTML = html;
		}

		// Thêm sản phẩm vào giỏ hàng từ wishlist
		function addToCartWishlist(productId) {
			const formData = new FormData();
			formData.append('product_id', productId);
			formData.append('qty', 1);

			fetch('api/add_to_cart_wishlist.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					swal({
						title: 'Thành công',
						text: data.message,
						icon: 'success',
						timer: 2000
					});
				} else {
					swal({
						title: 'Thông báo',
						text: data.message,
						icon: 'warning'
					});
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('Có lỗi xảy ra');
			});
		}

		// Xóa sản phẩm từ danh sách yêu thích
		function deleteWishlistItem(productId) {
			if (!confirm('Xóa sản phẩm khỏi danh sách yêu thích?')) {
				return;
			}

			const formData = new FormData();
			formData.append('product_id', productId);

			fetch('api/delete_wishlist.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					swal({
						title: 'Thành công',
						text: data.message,
						icon: 'success',
						timer: 2000
					});
					setTimeout(() => {
						loadWishlist();
					}, 2000);
				} else {
					swal({
						title: 'Lỗi',
						text: data.message,
						icon: 'warning'
					});
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('Có lỗi xảy ra');
			});
		}

		// Load wishlist khi trang tải
		document.addEventListener('DOMContentLoaded', function() {
			loadWishlist();
		});
	</script>

	<?php include 'components/alert.php'; ?>
</body>
</html>