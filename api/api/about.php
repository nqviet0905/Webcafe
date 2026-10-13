<?php include 'components/connection.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Green Coffee - Giới thiệu</title>
</head>
<body>

<?php include 'components/header.php'; ?>

<div class="main">

    <div class="banner">
        <h1>Tinh Hoa Trà</h1>
    </div>

    <div class="title2">
        <a href="home.php">Trang chủ</a> <span> / Giới thiệu</span>
    </div>

    <!-- CATEGORY -->
    <div class="about-category" id="category-container">
        <!-- JS render -->
    </div>

    <!-- FEATURES -->
    <div class="box-container">
        <div class="box">
            <img src="img/icon2.png">
            <h3>Hỗ trợ 24/7</h3>
            <p>Hỗ trợ 1-1</p>
        </div>

        <div class="box">
            <img src="img/icon0.png">
            <h3>Phiếu giảm giá</h3>
            <p>Tặng mỗi tuần</p>
        </div>

        <div class="box">
            <img src="img/icon.png">
            <h3>Giao hàng</h3>
            <p>Toàn cầu</p>
        </div>
    </div>

    <!-- ABOUT -->
    <div class="about">
        <div class="row">
            <div class="img-box">
                <img src="img/3.png"> 
            </div>
            <div class="detail">
                <h1>Tham quan cửa hàng</h1>
                <a href="view_products.php" class="btn">Mua ngay</a>
            </div>
        </div>
    </div>

    <!-- TESTIMONIAL -->
    <section class="testimonial-container">
        <div class="container">
            <div class="title">
                <h2>Đánh giá</h2>
            </div>

            <div class="testimonial-box" id="testimonial-container">
                <!-- JS render -->
            </div>
        </div>
    </section>

</div>

<?php include 'components/footer.php'; ?>

<script>
async function loadAbout() {
    try {
        // ✅ ĐÚNG ĐƯỜNG DẪN
        const res = await fetch('api/api_about.php');

        if (!res.ok) {
            throw new Error("HTTP lỗi: " + res.status);
        }

        const data = await res.json();
        console.log("DATA:", data);

        // ===== CATEGORY =====
        let cateHTML = '';
        data.categories.forEach(item => {
            cateHTML += `
                <div class="box">
                    <img src="${item.image}">
                    <div class="detail">
                        <span>${item.type}</span>
                        <h1>${item.name}</h1>
                        <a href="view_products.php" class="btn">Mua ngay</a>
                    </div>
                </div>
            `;
        });
        document.getElementById('category-container').innerHTML = cateHTML;

        // ===== TESTIMONIAL =====
        let testHTML = '';
        data.testimonials.forEach(item => {
            testHTML += `
                <div class="testimonial-item">
                    <h3>${item.name}</h3>
                    <p>${item.content}</p>
                </div>
            `;
        });
        document.getElementById('testimonial-container').innerHTML = testHTML;

    } catch (err) {
        console.error("LỖI:", err);
    }
}

loadAbout();
</script>

</body>
</html>
