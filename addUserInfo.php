<?php
session_start();
include './config/db_connection.php';

if (isset($_SESSION['username']) && $_SESSION['username'] !== 'admin') {
    $uname = $_SESSION['username'];
    if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        //tên procedure
        $addUser = 'p_add_user_info';

        $email = $conn->real_escape_string($_POST['Email']);
        $fname = $conn->real_escape_string($_POST['fullname']);
        $add = $conn->real_escape_string($_POST['add']);
        $phone = $conn->real_escape_string($_POST['phone']);

        $sql_query = $conn->prepare("CALL $addUser(?, ?, ?, ?, ?)");
        $sql_query->bind_param("sssss", $uname, $email, $fname, $add, $phone);
        $sql_query->execute();
        echo "<script> 
        alert('NHẬP THÀNH CÔNG!!!');
        window.location.href = 'personalPage.php';
        </script>";
        $sql_query->close();
    }
}
