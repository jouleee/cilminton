<?php

include_once("controller.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    // Validasi data input
    if (!empty($name) && !empty($phone)) {
        $registrationResult = registerCustomer($name, $phone);
        if ($registrationResult === "success") {
            $_SESSION['message'] = "Akun anda telah terdaftar, silahkan Login"; // Set pesan dalam session
            header("Location: login.php");
            exit();
        } else {
            $message = $registrationResult;
        }
    } else {
        $message = "Data tidak valid.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Registrasi</h2>
            <?php if (!empty($message)) : ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" required>
                <label for="phone">Nomor Telepon:</label>
                <input type="text" id="phone" name="phone" required>
                <button type="submit">Daftar</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>

</html>