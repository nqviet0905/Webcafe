<?php
include '../webcafe/components/connection.php';
session_start();

header('Content-Type: application/json');

// ðŸ”“ Táº¡m táº¯t check login Ä‘á»ƒ test Postman
// if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
//     echo json_encode([
//         "status" => "error",
//         "message" => "Unauthorized"
//     ]);
//     exit();
// }

// ================= UPDATE ORDER =================
if(isset($_POST['update_order'])){

    $orders_id = isset($_POST['orders_id']) ? $_POST['orders_id'] : '';
    $update_payment = isset($_POST['update_payment']) ? $_POST['update_payment'] : '';

    // Debug náº¿u cáº§n
    // print_r($_POST); exit;

    if($orders_id != '' && $update_payment != ''){

        $update_pay = $conn->prepare("UPDATE dathang SET payment_status=? WHERE id=?");
        $update_pay->execute([$update_payment, $orders_id]);

        echo json_encode([
            "status" => "success",
            "message" => "C?p nh?t don hàng thành công"
        ]);
        exit();

    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Thi?u tr?ng thái don hàng"
        ]);
        exit();
    }
}

// ================= DELETE ORDER =================
if(isset($_POST['delete_order'])){

    $orders_id = isset($_POST['orders_id']) ? $_POST['orders_id'] : '';

    if($orders_id != ''){

        $check = $conn->prepare("SELECT * FROM dathang WHERE id=?");
        $check->execute([$orders_id]);

        if($check->rowCount() > 0){

            $delete = $conn->prepare("DELETE FROM dathang WHERE id=?");
            $delete->execute([$orders_id]);

            echo json_encode([
                "status" => "success",
                "message" => "Ðon hàng dã b? hu?"
            ]);
            exit();

        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Không tìm th?y don hàng"
            ]);
            exit();
        }

    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Thi?u orders_id"
        ]);
        exit();
    }
}

// ================= KHÃ”NG Gá»¬I GÃŒ =================
echo json_encode([
    "status" => "error",
    "message" => "Không có action"
]);