<?php
session_start();

/* ===== ĐÃ LOGIN → VỀ HOME ===== */
if (isset($_SESSION['user_id'])) {
    header('location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Green Tea - Đăng ký</title>

    <style>
        <?php include 'style.css'; ?>
    </style>
</head>
<body>

<div class="main-container">
    <section class="form-container">
        <div class="title">
            <img src="img/download.png" alt="logo">
            <h1>Đăng ký tài khoản</h1>
        </div>

        <!-- FORM -->
        <form id="registerForm">
            <div class="input-field">
                <p>Họ và Tên</p>
                <input type="text" name="name" required placeholder="Họ và tên">
            </div>

            <div class="input-field">
                <p>Email</p>
                <input type="email" name="email" required placeholder="Email">
            </div>

            <div class="input-field">
                <p>Mật khẩu</p>
                <input type="password" name="pass" required placeholder="Mật khẩu">
            </div>

            <div class="input-field">
                <p>Xác nhận mật khẩu</p>
                <input type="password" name="cpass" required placeholder="Nhập lại mật khẩu">
            </div>

            <input type="submit" value="Đăng ký" class="btn">
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </form>
    </section>
</div>

<script>
document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    let btn = form.querySelector("input[type='submit']");
    btn.value = "Đang xử lý...";
    btn.disabled = true;

    fetch("api/apiregister.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text()) 
    .then(data => {
        console.log("API trả về:", data);

        try {
            let json = JSON.parse(data);

            alert(json.message);

            if (json.status === "success") {
                window.location.href = "home.php";
            }

        } catch (e) {
            alert("Lỗi :\n" + data);
        }
    })
    .catch(err => {
        console.error(err);
        alert("Không kết nối được API");
    })
    .finally(() => {
        btn.value = "Đăng ký";
        btn.disabled = false;
    });
});
</script>

</body>
</html>
