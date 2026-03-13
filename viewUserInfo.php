<?php

if (!isset($conn) || !($conn instanceof mysqli)) {
    include './config/db_connection.php';
}

if (!function_exists('uinfo_find_first_table')) {
    function uinfo_find_first_table(mysqli $conn, array $candidateTables): ?string
    {
        foreach ($candidateTables as $table) {
            $escaped = $conn->real_escape_string($table);
            $result = $conn->query("SHOW TABLES LIKE '{$escaped}'");
            if ($result && $result->num_rows > 0) {
                return $table;
            }
        }

        return null;
    }
}

if (!function_exists('uinfo_find_first_column')) {
    function uinfo_find_first_column(mysqli $conn, string $table, array $candidateColumns): ?string
    {
        $escapedTable = $conn->real_escape_string($table);
        $result = $conn->query("SHOW COLUMNS FROM `{$escapedTable}`");
        if (!$result) {
            return null;
        }

        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[strtolower($row['Field'])] = $row['Field'];
        }

        foreach ($candidateColumns as $column) {
            $key = strtolower($column);
            if (isset($columns[$key])) {
                return $columns[$key];
            }
        }

        return null;
    }
}

if (isset($_SESSION['username']) && $_SESSION['username'] !== 'admin') {
    $uname = $_SESSION['username'];

    $legacyTable = uinfo_find_first_table($conn, ['userinfos']);
    $newTable = uinfo_find_first_table($conn, ['USERS', 'users']);

    $mail = '';
    $name = $uname;
    $addr = '';
    $phone = '';

    if ($legacyTable) {
        $userNameCol = uinfo_find_first_column($conn, $legacyTable, ['userName']);
        $emailCol = uinfo_find_first_column($conn, $legacyTable, ['email']);
        $fullNameCol = uinfo_find_first_column($conn, $legacyTable, ['fullName']);
        $addressCol = uinfo_find_first_column($conn, $legacyTable, ['address']);
        $phoneCol = uinfo_find_first_column($conn, $legacyTable, ['phone']);

        if ($userNameCol && $emailCol && $addressCol && $phoneCol) {
            if ($fullNameCol) {
                $sql = "SELECT `{$emailCol}`, `{$fullNameCol}`, `{$addressCol}`, `{$phoneCol}` FROM `{$legacyTable}` WHERE `{$userNameCol}` = ? LIMIT 1";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('s', $uname);
                    if ($stmt->execute()) {
                        $stmt->bind_result($dbEmail, $dbName, $dbAddress, $dbPhone);
                        if ($stmt->fetch()) {
                            $mail = $dbEmail ?? '';
                            $name = $dbName ?? $uname;
                            $addr = $dbAddress ?? '';
                            $phone = $dbPhone ?? '';
                        }
                    }
                    $stmt->close();
                }
            } else {
                $sql = "SELECT `{$emailCol}`, `{$addressCol}`, `{$phoneCol}` FROM `{$legacyTable}` WHERE `{$userNameCol}` = ? LIMIT 1";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('s', $uname);
                    if ($stmt->execute()) {
                        $stmt->bind_result($dbEmail, $dbAddress, $dbPhone);
                        if ($stmt->fetch()) {
                            $mail = $dbEmail ?? '';
                            $addr = $dbAddress ?? '';
                            $phone = $dbPhone ?? '';
                        }
                    }
                    $stmt->close();
                }
            }
        }
    } elseif ($newTable) {
        $userNameCol = uinfo_find_first_column($conn, $newTable, ['USER_Name', 'userName']);
        $emailCol = uinfo_find_first_column($conn, $newTable, ['USER_Email', 'email']);
        $addressCol = uinfo_find_first_column($conn, $newTable, ['USER_Address', 'address']);
        $phoneCol = uinfo_find_first_column($conn, $newTable, ['USER_Phone', 'phone']);

        if ($userNameCol && $emailCol && $addressCol && $phoneCol) {
            $sql = "SELECT `{$emailCol}`, `{$addressCol}`, `{$phoneCol}` FROM `{$newTable}` WHERE `{$userNameCol}` = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('s', $uname);
                if ($stmt->execute()) {
                    $stmt->bind_result($dbEmail, $dbAddress, $dbPhone);
                    if ($stmt->fetch()) {
                        $mail = $dbEmail ?? '';
                        $addr = $dbAddress ?? '';
                        $phone = $dbPhone ?? '';
                    }
                }
                $stmt->close();
            }
        }
    }

    echo '
    <div class="user-info__form-container">
    <table>
        <tr>
            <th><label for="">Username :</label></th>
            <td><p class="output-data">' . htmlspecialchars($uname, ENT_QUOTES, 'UTF-8') . '</p></td>
        </tr>
        <tr>
            <th><label for="">Email :</label></th>
            <td><p class="output-data">' . htmlspecialchars((string) $mail, ENT_QUOTES, 'UTF-8') . '</p></td>
        </tr>
        <tr>
            <th><label for="">Họ và Tên :</label></th>
            <td><p class="output-data">' . htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8') . '</p></td>
        </tr>
        <tr>
            <th><label for="">Địa chỉ :</label></th>
            <td><p class="output-data">' . htmlspecialchars((string) $addr, ENT_QUOTES, 'UTF-8') . '</p></td>
        </tr>
        <tr>
            <th><label for="">Số Điện Thoại :</label></th>
            <td><p class="output-data">' . htmlspecialchars((string) $phone, ENT_QUOTES, 'UTF-8') . '</p></td>
        </tr>
    </table>
    </div>
    ';
}
