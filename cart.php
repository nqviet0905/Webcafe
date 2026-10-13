<?php
include 'components/connection.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php");
    exit;
}
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
    <title>Green Coffee - Trang giỏ hàng</title>
</head>
<body>

<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Giỏ hàng của tôi</h1>
    </div>

    <div class="title2">
        <a href="home.php">Trang Chủ </a><span>/ Giỏ hàng</span>
    </div>

<section class="cart">
    <div class="box-container">

        <div id="cart-items"></div>
        <div id="cart-total"></div>
    </div>

</section>

<?php include 'components/footer.php'; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="script.js"></script>

<script>
    // Tải dữ liệu giỏ hàng 
    function loadCart() {
        fetch('api/get_cart.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderCart(data.data, data.grand_total);
            } else {
                document.getElementById('cart-items').innerHTML = '<p class="empty">Chưa có sản phẩm nào trong giỏ hàng!</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('cart-items').innerHTML = '<p class="empty">Lỗi khi tải dữ liệu!</p>';
        });
    }

    // Hiển thị giỏ hàng
    function renderCart(cartItems, grandTotal) {
        const cartItemsDiv = document.getElementById('cart-items');
        const cartTotalDiv = document.getElementById('cart-total');

        if (cartItems.length === 0) {
            cartItemsDiv.innerHTML = '<p class="empty">Chưa có sản phẩm nào trong giỏ hàng!</p>';
            cartTotalDiv.innerHTML = '';
            return;
        }

        let html = '';
        cartItems.forEach(item => {
            html += `
                <div class="box" data-product-id="${item.product_id}">
                    <img src="img/${item.image}" class="img">
                    <h3 class="name">${item.name}</h3>
                    
                    <div class="flex">
                        <p class="price">Giá: ${item.price} nghìn đồng</p>
                        <input type="number" name="qty"
                               min="1" max="99"
                               value="${item.qty}"
                               class="qty"
                               onchange="updateCart(${item.product_id}, this.value)">
                    </div>
                    
                    <p class="sub-total">Giá: <span>${item.sub_total} nghìn đồng</span></p>
                    
                    <button type="button" class="btn" 
                            onclick="deleteCart(${item.product_id})">Xóa</button>
                </div>
            `;
        });

        cartItemsDiv.innerHTML = html;

        // Hiển thị tổng tiền
        cartTotalDiv.innerHTML = `
            <div class="cart-total">
                <p>Số tiền cần thanh toán: <span>${grandTotal} nghìn đồng</span></p>
                <div class="button">
                    <button type="button" class="btn" onclick="emptyCart()">Xóa giỏ hàng</button>
                    <a href="checkout.php" class="btn">Tiến hành thanh toán</a>
                </div>
            </div>
        `;
    }

    // Cập nhật số lượng sản phẩm via API
    function updateCart(productId, qty) {
        if (qty < 1) {
            alert('Số lượng phải từ 1 trở lên');
            return;
        }

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('qty', qty);

        fetch('api/update_cart.php', {
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
                    loadCart();
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
            alert('Có lỗi xảy ra khi cập nhật');
        });
    }

    // Xóa sản phẩm via API
    function deleteCart(productId) {
        if (!confirm('Bạn chắc muốn xóa sản phẩm này?')) {
            return;
        }

        const formData = new FormData();
        formData.append('product_id', productId);

        fetch('api/delete_cart.php', {
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
                    loadCart();
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
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        });
    }

    // Xóa toàn bộ giỏ hàng via API
    function emptyCart() {
        if (!confirm('Bạn chắc muốn xóa toàn bộ giỏ hàng?')) {
            return;
        }

        fetch('api/empty_cart.php', {
            method: 'POST'
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
                    loadCart();
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

    // Load cart khi trang tải
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
    });
</script>

<?php include 'components/alert.php'; ?>

</body>
</html>
