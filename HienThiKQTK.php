<?php
header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_OFF);

try {
    require './config/db_connection.php';
} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit();
}

function search_json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit();
}

function search_query_books(mysqli $conn, string $sql, string $keyword): ?array
{
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('s', $keyword);
    if (!$stmt->execute()) {
        $stmt->close();
        return null;
    }

    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        return [];
    }

    $bookId = null;
    $bookName = null;
    $stmt->bind_result($bookId, $bookName);

    $rows = [];
    while ($stmt->fetch()) {
        $rows[] = [
            'bookID' => $bookId,
            'bookName' => $bookName,
        ];
    }

    $stmt->close();
    return $rows;
}

$bookname = trim($_GET['bookName'] ?? '');
if ($bookname === '') {
    search_json_response([]);
}

$searchKeyword = "%{$bookname}%";

try {
    // Ưu tiên schema cũ trước để tương thích dữ liệu hiện tại của project
    $legacyRows = search_query_books(
        $conn,
        'SELECT bookID, bookName FROM books WHERE bookName LIKE ? ORDER BY bookName LIMIT 8',
        $searchKeyword
    );

    if ($legacyRows !== null) {
        search_json_response($legacyRows);
    }

    $newSchemaRows = search_query_books(
        $conn,
        'SELECT BOOK_ID, BOOK_Name FROM BOOKS WHERE BOOK_Name LIKE ? ORDER BY BOOK_Name LIMIT 8',
        $searchKeyword
    );

    if ($newSchemaRows !== null) {
        search_json_response($newSchemaRows);
    }

    // Không tìm thấy bảng/schema phù hợp
    search_json_response([]);
} catch (Throwable $error) {
    // Tránh làm hỏng JSON response trên frontend
    search_json_response([], 500);
}
