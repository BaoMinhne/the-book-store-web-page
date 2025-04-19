<?php
include './config/db_connection.php';

if (isset($_SESSION['username']) && $_SESSION['username'] !== 'admin') {
    $uname = $_SESSION['username'];

    $uInfo = "SELECT userName, email, fullName, address, phone FROM userinfos 
    WHERE userName = '$uname'";

    $resultUInfo = mysqli_query($conn, $uInfo);

    if (mysqli_num_rows($resultUInfo) > 0) {
        $row = mysqli_fetch_assoc($resultUInfo);

        $mail = $row['email'];
        $name = $row['fullName'];
        $addr = $row['address'];
        $phone = $row['phone'];

        echo '
        <div class="user-info__form-container">
        <table>
            <tr>
                <th><label for="">Username :</label></th>
                <td>
                    <p class="output-data"> ' . $uname . ' </p>
                </td>
            </tr>

            <tr>
                <th><label for="">Email :</label></th>
                <td>
                    <p class="output-data">' . $mail . '</p>
                </td>
            </tr>

            <tr>
                <th><label for="">Họ và Tên :</label></th>
                <td>
                    <p class="output-data">' . $name . '</p>
                </td>
            </tr>

            <tr>
                <th><label for="">Địa chỉ :</label></th>
                <td>
                    <p class="output-data">' . $addr . '</p>
                </td>
            </tr>

            <tr>
                <th><label for="">Số Điện Thoại :</label></th>
                <td>
                    <p class="output-data">' . $phone . '</p>
                </td>
            </tr>
        </table>
        </div>
        ';
    }
}
