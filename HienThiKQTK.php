<?php
require './config/db_connection.php';

$bookname = $_GET['bookName'];

$sql_query = "SELECT * FROM bookstore.books where bookName like '%$bookname%'";
$result = mysqli_query($conn, $sql_query);


if ($result && mysqli_num_rows($result) > 0) {
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    header("Content-Type: application/json");
    $json_data = json_encode($rows);
    echo $json_data;
    exit();
} else {
    header("Content-Type: application/json");
    echo json_encode(array('error' => 'No data found'));
    exit();
}
