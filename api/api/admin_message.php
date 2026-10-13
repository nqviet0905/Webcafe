<?php
include 'components/connection.php';
session_start();

// 👉 Check admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    header('location: login.php');
    exit();
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="admin_style.css?v=<?php echo time();?>">

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

        <div class="box-container">
            <?php
            $select_message = $conn->prepare("SELECT * FROM message ORDER BY id DESC");
            $select_message->execute();

            if($select_message->rowCount() > 0) {
                while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
            ?>

            <div class="box">
                <p><b>Tên:</b> <?= $fetch_message['Name']; ?></p>
                <p><b>SĐT:</b> <?= $fetch_message['DienThoai']; ?></p>
                <p><b>Nội dung:</b> <?= $fetch_message['message']; ?></p>

                <!-- 👉 NÚT XÓA -->
                <button class="btn delete-btn" data-id="<?= $fetch_message['id']; ?>">
                    Xóa
                </button>
            </div>

            <?php
                }
            } else {
                echo '<div class="empty">Chưa có dữ liệu</div>';
            }
            ?>
        </div>

    </section>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", function(){

        const id = this.getAttribute("data-id");
        const box = this.closest(".box");

        console.log("ID:", id);

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

                fetch("/webcafe/api/api_delete_message.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(res => {
                    console.log("STATUS:", res.status);
                    return res.text(); // 👉 đổi sang text để tránh lỗi JSON
                })
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
</script>

</body>
</html>
