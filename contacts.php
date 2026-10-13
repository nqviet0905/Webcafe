<?php
include 'components/connection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liên hệ</title>

    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

<?php include 'components/header.php'; ?>

<div class="main">

    <div class="banner">
        <h1>Thông tin liên hệ</h1>
    </div>

    <div class="title2">
        <a href="home.php">Trang chủ</a> / Liên Hệ
    </div>

    <!-- FORM -->
    <div class="form-container">
        <form id="contactForm" onsubmit="return false;">
            <div class="title">
                <img src="img/download.png" class="logo">
                <h1>Lời nhắn gửi</h1>
            </div>

            <div class="input-field">
                <p>Tên</p>
                <input type="text" name="name" required>
            </div>

            <div class="input-field">
                <p>Email</p>
                <input type="email" name="email" required>
            </div>

            <div class="input-field">
                <p>SĐT</p>
                <input type="text" name="number" required>
            </div>

            <div class="input-field">
                <p>Nội dung</p>
                <textarea name="message" required></textarea>
            </div>

            <button type="submit" class="btn">Gửi</button>
        </form>
    </div>

</div>

<?php include 'components/footer.php'; ?>

<!-- SweetAlert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("contactForm");

    form.addEventListener("submit", function(e){
        e.preventDefault();

        const data = {
            name: form.querySelector("[name='name']").value.trim(),
            email: form.querySelector("[name='email']").value.trim(),
            number: form.querySelector("[name='number']").value.trim(),
            message: form.querySelector("[name='message']").value.trim()
        };

        fetch("api/api_contact.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(res => res.text())
        .then(res => {
            console.log("RAW:", res);

            let json;
            try {
                json = JSON.parse(res);
            } catch (e) {
                swal("Lỗi", "Server trả về không hợp lệ!", "error");
                return;
            }

            if (json.status) {
                swal("Thành công", json.message, "success");
                form.reset();
            } else {
                swal("Lỗi", json.message, "error");
            }
        })
        .catch(err => {
            console.error(err);
            swal("Lỗi", "Không kết nối được server!", "error");
        });

    });

});
</script>

</body>
</html>
