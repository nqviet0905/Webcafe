<?php
include 'components/connection.php';
session_start();

// 👉 Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('location: login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="admin_style.css?v=<?php echo time(); ?>">

    <title>Quản lý đánh giá</title>
</head>

<body>

        <?php include 'components/admin_header.php'; ?>

    <div class="main">

        <div class="banner">
            <h1>Đánh Giá</h1>
        </div>

        <div class="title2">
            <a href="dashboard.php">Bảng điều khiển</a>
            <span> / Đánh Giá</span>
        </div>

        <section class="admin_message">
            <h1 class="heading">Đánh Giá</h1>

            <div class="box-container" id="messageContainer">
                <div class="empty">Đang tải...</div>
            </div>

        </section>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script>
        // ===== LOAD MESSAGES =====
        function loadMessages() {
            fetch("api/api_get_messages.php")
                .then(res => res.json())
                .then(json => {
                    const container = document.getElementById("messageContainer");

                    if (!json.status) {
                        container.innerHTML = '<div class="empty">Lỗi tải dữ liệu!</div>';
                        return;
                    }

                    if (!json.data || json.data.length === 0) {
                        container.innerHTML = '<div class="empty">Chưa có dữ liệu</div>';
                        return;
                    }

                    container.innerHTML = json.data.map(msg => `
                <div class="box" data-id="${msg.id}">
                    <p><b>Tên:</b> ${msg.Name}</p>
                    <p><b>SĐT:</b> ${msg.DienThoai}</p>
                    <p><b>Nội dung:</b> ${msg.message}</p>
                    <button class="btn delete-btn" data-id="${msg.id}">Xóa</button>
                </div>
            `).join('');

                    bindDeleteButtons();
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById("messageContainer").innerHTML =
                        '<div class="empty">Không kết nối được server!</div>';
                });
        }

        // ===== BIND DELETE =====
        function bindDeleteButtons() {
            document.querySelectorAll(".delete-btn").forEach(btn => {
                btn.addEventListener("click", function () {
                    const id = this.getAttribute("data-id");
                    const box = this.closest(".box");

                    if (!id) {
                        swal("Lỗi", "Không lấy được ID!", "error");
                        return;
                    }

                    swal({
                        title: "Bạn có chắc?",
                        text: "Xóa đánh giá này!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((willDelete) => {
                            if (willDelete) {
                                fetch("api/api_delete_message.php", {
                                    method: "POST",
                                    headers: { "Content-Type": "application/json" },
                                    body: JSON.stringify({ id: id })
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
                                            box.remove();
                                        } else {
                                            swal("Lỗi", json.message, "error");
                                        }
                                    })
                                    .catch(err => {
                                        console.error("Fetch lỗi:", err);
                                        swal("Lỗi", "Không kết nối server!", "error");
                                    });
                            }
                        });
                });
            });
        }

        // Khởi chạy khi trang load
        loadMessages();
    </script>

</body>

</html>