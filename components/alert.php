<?php
if (!empty($success_msg)) {
    foreach ($success_msg as $msg) {
        echo '<script>swal("'.$msg.'", "", "success");</script>';
    }
}

if (!empty($warning_msg)) {
    foreach ($warning_msg as $msg) {
        echo '<script>swal("'.$msg.'", "", "warning");</script>';
    }
}

if (!empty($info_msg)) {
    foreach ($info_msg as $msg) {
        echo '<script>swal("'.$msg.'", "", "info");</script>';
    }
}

if (!empty($error_msg)) {
    foreach ($error_msg as $msg) {
        echo '<script>swal("'.$msg.'", "", "error");</script>';
    }
}
?>
