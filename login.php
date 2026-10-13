<?php
include 'components/connection.php';
session_start();

/* ===== ĐÃ LOGIN → REDIRECT ===== */
if (isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'admin') {
        header("location: dashboard.php");
    } else {
        header("location: home.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Tea - Đăng nhập</title>

    <!-- ✅ FIX CSS -->
    <link rel="stylesheet" href="style.css">

    <style>
        .message{
            background:#f8d7da;
            color:#721c24;
            padding:10px;
            margin-bottom:10px;
            border-radius:5px;
            text-align:center;
        }
    </style>
</head>
<body>

<div class="main-container">
    <section class="form-container">

        <div class="title">
            <img src="img/download.png" alt="logo">
            <h1>Đăng nhập</h1>
        </div>

        <!-- thông báo -->
        <div id="messageBox"></div>

        <!-- form -->
        <form id="loginForm">

            <div class="input-field">
                <p>Email của bạn</p>
                <input type="email" name="email" required maxlength="50"
                       placeholder="Nhập Email">
            </div>

            <div class="input-field">
                <p>Mật khẩu của bạn</p>
                <input type="password" name="pass" required maxlength="50"
                       placeholder="Nhập mật khẩu">
            </div>

            <input type="submit" value="Đăng nhập" class="btn">
            <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>

        </form>
    </section>
</div>

<!-- JS LOGIN -->
<script>
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    document.getElementById("messageBox").innerHTML =
        `<div class="message">Đang đăng nhập...</div>`;

    fetch("api/apilogin.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        console.log("API:", data);

        try {
            let json = JSON.parse(data);

            document.getElementById("messageBox").innerHTML =
                `<div class="message">${json.message}</div>`;

            if (json.status === "success") {
                setTimeout(() => {
                    window.location.href =
                        json.role === "admin"
                        ? "dashboard.php"
                        : "home.php";
                }, 800);
            }

        } catch (e) {
            document.getElementById("messageBox").innerHTML =
                `<div class="message">Lỗi API: ${data}</div>`;
        }
    })
    .catch(() => {
        document.getElementById("messageBox").innerHTML =
            `<div class="message">Lỗi server!</div>`;
    });
});
</script>

</body>
</html>
