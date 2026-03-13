<?php
require './config/db_connection.php';

$bookname = trim($_GET['bookName'] ?? '');

if ($bookname === '') {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$searchKeyword = "%{$bookname}%";
$sql_query = $conn->prepare('SELECT * FROM bookstore.books WHERE bookName LIKE ?');
$sql_query->bind_param('s', $searchKeyword);
$sql_query->execute();
$result = $sql_query->get_result();

if ($result && mysqli_num_rows($result) > 0) {
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($rows);
    exit();
}

header('Content-Type: application/json');
echo json_encode([]);
exit();
