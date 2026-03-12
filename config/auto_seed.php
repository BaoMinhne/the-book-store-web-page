<?php

function seed_has_table(mysqli $conn, string $tableName): bool
{
    $stmt = $conn->prepare('SELECT COUNT(*) AS total FROM information_schema.tables WHERE table_schema = DATABASE() AND LOWER(table_name) = LOWER(?)');
    $stmt->bind_param('s', $tableName);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return (int) ($result['total'] ?? 0) > 0;
}

function seed_has_procedure(mysqli $conn, string $procedureName): bool
{
    $stmt = $conn->prepare('SELECT COUNT(*) AS total FROM information_schema.routines WHERE routine_schema = DATABASE() AND routine_type = "PROCEDURE" AND routine_name = ?');
    $stmt->bind_param('s', $procedureName);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return (int) ($result['total'] ?? 0) > 0;
}

function seed_has_column(mysqli $conn, string $tableName, string $columnName): bool
{
    $stmt = $conn->prepare('SELECT COUNT(*) AS total FROM information_schema.columns WHERE table_schema = DATABASE() AND LOWER(table_name) = LOWER(?) AND LOWER(column_name) = LOWER(?)');
    $stmt->bind_param('ss', $tableName, $columnName);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return (int) ($result['total'] ?? 0) > 0;
}

function seed_exec_sql_file(mysqli $conn, string $relativePath): void
{
    $fullPath = dirname(__DIR__) . '/' . ltrim($relativePath, '/');
    if (!file_exists($fullPath)) {
        return;
    }

    $sql = file_get_contents($fullPath);
    if ($sql === false || trim($sql) === '') {
        return;
    }

    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $statement) {
        $conn->query($statement);
    }
}

function seed_legacy_data(mysqli $conn): void
{
    if (seed_has_table($conn, 'genres')) {
        $genres = [
            ['SGK', 'Sách giáo khoa'],
            ['TT', 'Tiểu thuyết'],
            ['TRT', 'Truyện tranh'],
            ['KD', 'Kinh doanh'],
            ['KH', 'Khoa học'],
        ];
        $stmt = $conn->prepare('INSERT INTO genres (genID, genName) VALUES (?, ?) ON DUPLICATE KEY UPDATE genName = VALUES(genName)');
        foreach ($genres as $genre) {
            [$genID, $genName] = $genre;
            $stmt->bind_param('ss', $genID, $genName);
            $stmt->execute();
        }
    }

    if (seed_has_table($conn, 'publishers')) {
        $stmtPub = $conn->prepare('INSERT INTO publishers (pubID) VALUES (?) ON DUPLICATE KEY UPDATE pubID = VALUES(pubID)');
        foreach (['NXB Trẻ', 'NXB Kim Đồng', 'NXB Giáo Dục'] as $pubID) {
            $stmtPub->bind_param('s', $pubID);
            $stmtPub->execute();
        }
    }

    if (seed_has_table($conn, 'origins')) {
        $stmtOrigin = $conn->prepare('INSERT INTO origins (oriName) VALUES (?) ON DUPLICATE KEY UPDATE oriName = VALUES(oriName)');
        foreach (['Việt Nam', 'Mỹ', 'Nhật Bản'] as $oriName) {
            $stmtOrigin->bind_param('s', $oriName);
            $stmtOrigin->execute();
        }
    }

    if (seed_has_table($conn, 'userinfos')) {
        $adminCheck = $conn->prepare("SELECT userID FROM userinfos WHERE userName = ? LIMIT 1");
        $adminName = 'admin';
        $adminCheck->bind_param('s', $adminName);
        $adminCheck->execute();
        $adminResult = $adminCheck->get_result();

        if ($adminResult && $adminResult->num_rows > 0) {
            $conn->query("UPDATE userinfos SET userRole = 1 WHERE userName = 'admin'");
        } else {
            $stmtAdmin = $conn->prepare('INSERT INTO userinfos (userName, userPass, userRole) VALUES (?, ?, ?)');
            $adminPass = 'admin123';
            $adminRole = 1;
            $stmtAdmin->bind_param('ssi', $adminName, $adminPass, $adminRole);
            $stmtAdmin->execute();
        }

        $customerCheck = $conn->prepare("SELECT userID FROM userinfos WHERE userName = ? LIMIT 1");
        $customerName = 'customer01';
        $customerCheck->bind_param('s', $customerName);
        $customerCheck->execute();
        $customerResult = $customerCheck->get_result();

        if (!$customerResult || $customerResult->num_rows === 0) {
            $stmtUser = $conn->prepare('INSERT INTO userinfos (userName, userPass, userRole) VALUES (?, ?, ?)');
            $userPass = '123456';
            $userRole = 0;
            $stmtUser->bind_param('ssi', $customerName, $userPass, $userRole);
            $stmtUser->execute();
        }
    }

    if (seed_has_table($conn, 'books')) {
        $check = $conn->query('SELECT COUNT(*) AS total FROM books');
        $count = (int) ($check->fetch_assoc()['total'] ?? 0);

        if ($count === 0) {
            $defaultImage = 'assets/img/logo/logo2.png';
            $books = [
                ['Dế Mèn Phiêu Lưu Ký', 65000, 40, 'TT', 'NXB Trẻ', 1],
                ['Tôi Thấy Hoa Vàng Trên Cỏ Xanh', 90000, 30, 'TT', 'NXB Trẻ', 1],
                ['Conan Tập 1', 25000, 120, 'TRT', 'NXB Kim Đồng', 3],
                ['Sách Giáo Khoa Toán 1', 18000, 200, 'SGK', 'NXB Giáo Dục', 1],
            ];

            $stmt = $conn->prepare('INSERT INTO books (bookName, bookPrice, bookQuantity, genID, pubID, oriID, bgURL) VALUES (?, ?, ?, ?, ?, ?, ?)');
            foreach ($books as $book) {
                [$bookName, $bookPrice, $bookQuantity, $genID, $pubID, $oriID] = $book;
                $stmt->bind_param('siissis', $bookName, $bookPrice, $bookQuantity, $genID, $pubID, $oriID, $defaultImage);
                $stmt->execute();
            }
        }
    }
}

function seed_legacy_procedures(mysqli $conn): void
{
    $procedures = [
        'p_register' => "CREATE PROCEDURE p_register(IN p_userName VARCHAR(255), IN p_userPass VARCHAR(255))\nBEGIN\n    INSERT INTO userinfos(userName, userPass, userRole) VALUES (p_userName, p_userPass, 0);\nEND",
        'p_view_gen_books' => "CREATE PROCEDURE p_view_gen_books(IN p_genName VARCHAR(255))\nBEGIN\n    SELECT b.bookID AS MaSach, b.bookName, b.bookPrice, b.bookQuantity, g.genName\n    FROM books b\n    JOIN genres g ON b.genID = g.genID\n    WHERE g.genName LIKE p_genName;\nEND",
        'p_del_book' => "CREATE PROCEDURE p_del_book(IN p_bookID INT)\nBEGIN\n    DELETE FROM books WHERE bookID = p_bookID;\nEND",
        'p_up_book' => "CREATE PROCEDURE p_up_book(IN p_bookID INT, IN p_bookName VARCHAR(255), IN p_bookPrice INT, IN p_bookQuantity INT, IN p_genID VARCHAR(20), IN p_bgURL VARCHAR(255), IN p_pubID VARCHAR(255), IN p_oriID INT)\nBEGIN\n    UPDATE books SET bookName = p_bookName, bookPrice = p_bookPrice, bookQuantity = p_bookQuantity, genID = p_genID, bgURL = p_bgURL, pubID = p_pubID, oriID = p_oriID WHERE bookID = p_bookID;\nEND",
        'p_add_books' => "CREATE PROCEDURE p_add_books(IN p_bookName VARCHAR(255), IN p_bookPrice INT, IN p_bookQuantity INT, IN p_genID VARCHAR(20), IN p_bgURL VARCHAR(255), IN p_pubID VARCHAR(255), IN p_oriID INT)\nBEGIN\n    INSERT INTO books(bookName, bookPrice, bookQuantity, genID, bgURL, pubID, oriID) VALUES (p_bookName, p_bookPrice, p_bookQuantity, p_genID, p_bgURL, p_pubID, p_oriID);\nEND",
        'p_add_to_cart' => "CREATE PROCEDURE p_add_to_cart(IN p_userName VARCHAR(255), IN p_bookID INT, IN p_quantity INT)\nBEGIN\n    DECLARE v_userID INT;\n    DECLARE v_price INT;\n    DECLARE v_orderID INT;\n\n    SELECT userID INTO v_userID FROM userinfos WHERE userName = p_userName LIMIT 1;\n    SELECT bookPrice INTO v_price FROM books WHERE bookID = p_bookID LIMIT 1;\n\n    INSERT INTO orders(userID, status) VALUES (v_userID, 'chưa thanh toán');\n    SET v_orderID = LAST_INSERT_ID();\n\n    INSERT INTO detailOrders(orderID, bookID, orderQuantity, orderCost) VALUES (v_orderID, p_bookID, p_quantity, v_price * p_quantity);\n    UPDATE books SET bookQuantity = bookQuantity - p_quantity WHERE bookID = p_bookID;\nEND",
    ];

    foreach ($procedures as $name => $sql) {
        if (!seed_has_procedure($conn, $name)) {
            $conn->query($sql);
        }
    }
}

function seed_new_schema(mysqli $conn): void
{
    seed_exec_sql_file($conn, 'database/seeds/01_schema.sql');

    if (seed_has_table($conn, 'USERS') && !seed_has_column($conn, 'USERS', 'USER_Password')) {
        $conn->query("ALTER TABLE USERS ADD COLUMN USER_Password VARCHAR(255) NOT NULL DEFAULT '' AFTER USER_Name");
    }

    seed_exec_sql_file($conn, 'database/seeds/02_seed_data.sql');

    if (seed_has_column($conn, 'USERS', 'USER_Password')) {
        $conn->query("UPDATE USERS SET USER_Password = 'admin123' WHERE USER_Name = 'admin' AND (USER_Password IS NULL OR USER_Password = '')");
        $conn->query("UPDATE USERS SET USER_Password = '123456' WHERE USER_Name = 'customer01' AND (USER_Password IS NULL OR USER_Password = '')");
    }

    $conn->query('DROP PROCEDURE IF EXISTS p_register');
    $conn->query("CREATE PROCEDURE p_register(IN p_userName VARCHAR(255), IN p_userPass VARCHAR(255))\nBEGIN\n    INSERT INTO USERS (USER_Name, USER_Password) VALUES (p_userName, p_userPass);\n    INSERT INTO USER_ROLE (USER_ID, UR_ROLE) VALUES (LAST_INSERT_ID(), 0);\nEND");

    if (!seed_has_procedure($conn, 'p_view_gen_books')) {
        $conn->query("CREATE PROCEDURE p_view_gen_books(IN p_genName VARCHAR(255))\nBEGIN\n    SELECT b.BOOK_ID AS MaSach, b.BOOK_Name AS bookName, b.BOOK_PRICE AS bookPrice, b.BOOK_Amount AS bookQuantity, g.GEN_Name AS genName\n    FROM BOOKS b\n    JOIN GENRES g ON b.GEN_ID = g.GEN_ID\n    WHERE g.GEN_Name LIKE p_genName;\nEND");
    }
}

function run_auto_seed(mysqli $conn): void
{
    static $seeded = false;

    if ($seeded) {
        return;
    }

    if (seed_has_table($conn, 'userinfos')) {
        seed_legacy_data($conn);
        seed_legacy_procedures($conn);
    } else {
        seed_new_schema($conn);
    }

    $seeded = true;
}
