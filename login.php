<?php

include_once("loginController.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];

    // Validasi data input
    if (!empty($phone) && preg_match('/^[0-9]{10,15}$/', $phone)) {
        $message = loginCustomer($phone);
        if ($message === "Login berhasil!") {
            header("Location: index.php");
            exit();
        }
    } else {
        $message = "Data tidak valid.";
    }
}

if (isset($_SESSION['message'])) {
    $message = htmlspecialchars($_SESSION['message']);
    unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login dengan Nomor Telepon</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">

</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            <?php if (!empty($message)) : ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <label for="phone">Nomor Telepon:</label>
                <input type="text" id="phone" name="phone" required>
                <button type="submit">Login</button>
            </form>
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>
</body>

</html>