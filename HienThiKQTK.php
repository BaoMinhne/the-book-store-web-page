<?php
require './config/db_connection.php';

header('Content-Type: application/json; charset=utf-8');

$bookname = trim($_GET['bookName'] ?? '');

if ($bookname === '') {
    echo json_encode([]);
    exit();
}


function search_has_table(mysqli $conn, string $tableName): bool
{
    $stmt = $conn->prepare('SELECT COUNT(*) AS total FROM information_schema.tables WHERE table_schema = DATABASE() AND LOWER(table_name) = LOWER(?)');
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('s', $tableName);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return (int) ($result['total'] ?? 0) > 0;
}

$searchKeyword = "%{$bookname}%";
$sql = null;

if (search_has_table($conn, 'books')) {
    $sql = 'SELECT bookID, bookName FROM books WHERE bookName LIKE ? ORDER BY bookName LIMIT 8';
} elseif (search_has_table($conn, 'BOOKS')) {
    $sql = 'SELECT BOOK_ID AS bookID, BOOK_Name AS bookName FROM BOOKS WHERE BOOK_Name LIKE ? ORDER BY BOOK_Name LIMIT 8';
}

if ($sql === null) {
    echo json_encode([]);
    exit();
}

$sql_query = $conn->prepare($sql);
if (!$sql_query) {
    http_response_code(500);
    echo json_encode([]);
    exit();
}

$sql_query->bind_param('s', $searchKeyword);
$sql_query->execute();
$result = $sql_query->get_result();

if ($result && mysqli_num_rows($result) > 0) {
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    echo json_encode($rows);
    exit();
}

echo json_encode([]);
exit();
