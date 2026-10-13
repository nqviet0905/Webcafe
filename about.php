<?php include 'components/connection.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="style.css">
        <title>Green Coffee- Về chúng tôi </title>
    </head>
    <body>
        <?php include 'components/header.php'; ?>
        <div class="main">
            <div class="banner">
                <h1>Tinh Hoa Trà</h1>
            </div>
            <div class="title2">
                <a href="home.php">Trang chủ</a><span>  Giới thiệu </span>
            </div>
            <div class="about-category">
                <div class="box">
                    <img src="img/3.webp">
                    <div class="detail">
                        <span>Cà phê</span>
                        <h1>Matcha</h1>
                        <a href="view_products.php" class="btn">Mua ngay</a>
                    </div>
                </div>
                <div class="box">
                    <img src="img/2.webp">
                    <div class="detail">
                        <span>Cà phê</span>
                        <h1>Chè Thái Nguyên</h1>
                        <a href="view_products.php" class="btn">Mua ngay</a>
                    </div>
                </div>
                                <div class="box">
                    <img src="img/about.png">
                    <div class="detail">
                        <span>Cà phê</span>
                        <h1>Capuchino</h1>
                        <a href="view_products.php" class="btn">Mua ngay</a>
                    </div>
                </div>
                <div class="box">
                    <img src="img/1.webp">
                    <div class="detail">
                        <span>Cà phê</span>
                        <h1>Chè khô</h1>
                        <a href="view_products.php" class="btn">Mua ngay</a>        </div>
                </div>
            </div>
                            <section class="services">
                                <div class="title">
                                    <img src="img/download.png" class="logo">
                                    <h1>Giá Trị chúng tôi mang lại</h1>
                                </div>
                    <div class="box-container">
                        <div class="box">
                            <img src="img/icon2.png">
                            <div class="detail">
                                <h3>Tiết kiệm</h3>
                                <p>Tiết kiệm hơn khi là thành viên </p>
                            </div>
                        </div>
                        <div class="box">
                            <img src="img/icon1.png">
                            <div class="detail">
                                <h3>Hỗ trợ 24/7</h3>
                                <p>Hỗ trợ 1-1</p>
                            </div> 
                        </div>       
                        <div class="box">
                            <img src="img/icon0.png">
                            <div class="detail">
                                <h3>Phiếu giảm giá</h3>
                                <p>Tặng phiếu giảm giá mỗi tuần  </p>
                            </div>
                        </div>
                        <div class="box">
                            <img src="img/icon.png">
                            <div class="detail">
                                <h3>Giao hàng toàn cầu</h3>
                                <p>Giao hàng toàn cầu</p>
                            </div>
                        </div>  
                    </div>
                </section>
                <div class="about">
                    <div class="row">
                        <div class="img-box">
                           <img src="img/3.png"> 
                        </div>
                        <div class="detail">
                            <h1>Tham quan cửa hàng của chúng tôi</h1>
                            <a href="view_products.php" class="btn">Mua ngay</a>
                        </div>
                        
                    </div>
                    
                </div>
                    <section class="testimonial-container">
    <div class="container">
        <div class="title">
            <img src="img/download.png" class="logo">
            <h2>Các chuyên gia đánh giá</h2>
        </div>

        <div class="testimonial-box">
            <div class="testimonial-item active">
                <img src="img/01.jpg" alt="">
                <h3>Alexander Đức</h3>
                <p>Trà xanh có hương vị tự nhiên,hậu ngọt phù hợp với mọi người</p>
            </div>

            <div class="testimonial-item">
                <img src="img/02.jpg" alt="">
                <h3>PGS.TS Khanh</h3>
                <p>Một lựa chọn tin cậy cho người tiêu dùng quan tâm đến sức khỏe và lối sống xanh</p>
            </div>

            <div class="testimonial-item">
                <img src="img/03.jpg" alt="">
                <h3>Mai cồ Việt</h3>
                <p>Sản phẩm đạt chất lượng,chuẩn an toàn nguyên liệu chọn lựa kỹ lưỡng từ vùng trồng chất lượng cao</p>
            </div>

            <div class="left-arrow"><i class='bx bx-chevron-left'></i></div>
           <div class="right-arrow"><i class='bx bx-chevron-right'></i></div>
        </div>
    </div>
</section>
<?php include 'components/footer.php'; ?>
 </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
        <script src="script.js"></script>
        <?php include 'components/alert.php' ;?>
</body>
</html>