<?php
session_start();
include_once("database/config.php");

function loginCustomer($phone)
{
    $conn = dbConnect("db_badminton");

    // Cek apakah nomor telepon ada di tabel t_customer
    $checkQuery = "SELECT id_customer FROM t_customer WHERE kontak_customer = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, 's', $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['loggedin'] = true;
        $_SESSION['id_customer'] = $row['id_customer'];
        $_SESSION['phone'] = $phone;
        mysqli_close($conn);
        return "Login berhasil!";
    } else {
        mysqli_close($conn);
        return "Nomor telepon tidak terdaftar.";
    }
}


function logoutCustomer()
{
    session_start();
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    logoutCustomer();
}
