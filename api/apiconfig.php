<?php
$conn = new mysqli("localhost", "root", "", "your_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
?>
