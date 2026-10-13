<?php
include "config.php";

session_destroy();

echo json_encode([
    "status" => "success",
    "message" => "Đăng xuất thành công"
]);
?>
