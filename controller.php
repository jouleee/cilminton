<?php
include_once("database/config.php");
session_start();


function registerCustomer($name, $phone)
{
    $conn = dbConnect("db_badminton");

    // Cek apakah nomor telepon sudah ada
    $checkQuery = "SELECT * FROM t_customer WHERE kontak_customer = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, 's', $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        return "Nomor telepon sudah terdaftar.";
    } else {
        // Tambahkan data ke tabel customer
        $insertQuery = "INSERT INTO t_customer (nama_customer, kontak_customer) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, 'ss', $name, $phone);

        if (mysqli_stmt_execute($stmt)) {
            return "success";
        } else {
            return "Registrasi gagal: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
function getAvailableTimes($selectedDate)
{
    $conn = dbConnect("db_badminton");

    // Check if the date exists in t_tanggal
    $checkDateQuery = "SELECT id_tanggal FROM t_tanggal WHERE tanggal = ?";
    $stmt = mysqli_prepare($conn, $checkDateQuery);
    mysqli_stmt_bind_param($stmt, 's', $selectedDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return false; // Date not found in t_tanggal
    }

    $row = mysqli_fetch_assoc($result);
    $id_tanggal = $row['id_tanggal'];

    // Check if the date exists in t_jadwal
    $checkScheduleQuery = "SELECT id_jadwal FROM t_jadwal WHERE id_tanggal = ?";
    $stmt = mysqli_prepare($conn, $checkScheduleQuery);
    mysqli_stmt_bind_param($stmt, 'i', $id_tanggal);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return []; // Date found in t_tanggal but not in t_jadwal
    }

    // Query to get available time slots based on t_jadwal
    $query = "SELECT t_waktu.id_waktu, t_waktu.waktu_mulai, t_waktu.waktu_selesai 
              FROM t_jadwal
              JOIN t_waktu ON t_jadwal.id_waktu = t_waktu.id_waktu
              WHERE t_jadwal.id_tanggal = ?
              AND t_jadwal.status = 0
              OR t_waktu.id_waktu NOT IN (
                  SELECT tj.id_waktu
                  FROM t_jadwal tj
                  WHERE tj.id_tanggal = ?
                  AND tj.status = 1
              )";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $id_tanggal, $id_tanggal);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $timeSlots = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $timeSlots[] = $row;
    }

    mysqli_stmt_close($stmt); // Close the prepared statement
    mysqli_close($conn);
    return $timeSlots;
}



function getAvailableFacilities($selectedDate, $selectedTime)
{
    $conn = dbConnect("db_badminton");

    // Query to get available facilities based on t_jadwal
    $query = "SELECT t_fasilitas.id_fasilitas, t_fasilitas.nama_fasilitas 
              FROM t_jadwal
              JOIN t_fasilitas ON t_jadwal.id_fasilitas = t_fasilitas.id_fasilitas
              WHERE t_jadwal.id_tanggal = (SELECT id_tanggal FROM t_tanggal WHERE tanggal = ?)
              AND t_jadwal.id_waktu = ?
              AND t_jadwal.status = 0";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $selectedDate, $selectedTime);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $facilities = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $facilities[] = $row;
    }

    mysqli_close($conn);
    return $facilities;
}

function getVouchersFromDatabase()
{
    $conn = dbConnect("db_badminton");

    // Query to retrieve vouchers from database
    $query = "SELECT id_voucher, nama_voucher FROM t_voucher";
    $result = mysqli_query($conn, $query);

    $vouchers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $vouchers[] = $row;
    }

    mysqli_close($conn);
    return $vouchers;
}

function getExtrasFromDatabase()
{
    $conn = dbConnect("db_badminton");

    // Query to retrieve extras from database
    $query = "SELECT id_barang, nama_barang, harga_barang FROM t_barang";
    $result = mysqli_query($conn, $query);

    $extras = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $extras[] = $row;
    }

    mysqli_close($conn);
    return $extras;
}

function bookFacility($selectedDate, $selectedTime, $selectedFacility, $voucherID = null, $extraID = null, $kuantitas_extra = null)
{
    $conn = dbConnect("db_badminton");
    $statusBooking = "Menunggu Pembayaran";

    // Retrieve customer ID from session
    if (isset($_SESSION['id_customer'])) {
        $customerID = $_SESSION['id_customer'];
    } else {
        header("Location: login.php");
        exit();
    }

    // First, find the id_tanggal
    $tanggalQuery = "SELECT id_tanggal FROM t_tanggal WHERE tanggal = ?";
    $stmt = mysqli_prepare($conn, $tanggalQuery);
    mysqli_stmt_bind_param($stmt, 's', $selectedDate);

    if (!$stmt) {
        echo "Error preparing statement for tanggalQuery: " . mysqli_error($conn);
        return;
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_tanggal);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Check if $id_tanggal is set
    if (!$id_tanggal) {
        echo "Tanggal tidak ditemukan.\n";
        return;
    }

    // Find the id_jadwal
    $jadwalQuery = "SELECT id_jadwal FROM t_jadwal
                    WHERE id_tanggal = ?
                    AND id_waktu = ?
                    AND id_fasilitas = ?
                    AND status = 0";
    $stmt = mysqli_prepare($conn, $jadwalQuery);
    mysqli_stmt_bind_param($stmt, 'iii', $id_tanggal, $selectedTime, $selectedFacility);

    if (!$stmt) {
        echo "Error preparing statement for jadwalQuery: " . mysqli_error($conn);
        return;
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_jadwal);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Check if $id_jadwal is set
    if (!$id_jadwal) {
        echo "Jadwal tidak ditemukan atau sudah dibooking.\n";
        return;
    }

    // Insert booking into t_booking
    $insertQuery = "INSERT INTO t_booking (tanggal_booking, status_booking, id_customer, id_jadwal, id_fasilitas, id_voucher)
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, 'ssiiii', $selectedDate, $statusBooking, $customerID, $id_jadwal, $selectedFacility, $voucherID);

    if (!$stmt) {
        echo "Error preparing statement for insertQuery: " . mysqli_error($conn);
        return;
    }

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Get the last inserted booking ID
        $bookingID = mysqli_insert_id($conn);

        // Update status in t_jadwal
        $updateQuery = "UPDATE t_jadwal SET status = 1 WHERE id_jadwal = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'i', $id_jadwal);
        mysqli_stmt_execute($stmt);

        // If extras are selected, insert into t_extra
        if ($extraID && $kuantitas_extra) {
            $extraQuery = "INSERT INTO t_extra (kuantitas_extra, id_barang, id_booking) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $extraQuery);
            mysqli_stmt_bind_param($stmt, 'iii', $kuantitas_extra, $extraID, $bookingID);

            if (!$stmt) {
                echo "Error preparing statement for extraQuery: " . mysqli_error($conn);
                return;
            }

            $result = mysqli_stmt_execute($stmt);

            if (!$result) {
                echo "Error executing extraQuery: " . mysqli_error($conn);
                return;
            }

            // Get the last inserted extra ID
            $extraID = mysqli_insert_id($conn);
        }

        // Insert booking ID and extra ID into t_pembayaran
        $insertPaymentQuery = "INSERT INTO t_pembayaran (id_booking, id_extra) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insertPaymentQuery);
        mysqli_stmt_bind_param($stmt, 'ii', $bookingID, $extraID);
        mysqli_stmt_execute($stmt);

        // Redirect user to payment page after successful booking
        header("Location: pembayaran.php?bookingID=$bookingID&selectedDate=$selectedDate&selectedTime=$selectedTime&selectedFacility=$selectedFacility&voucherID=$voucherID&extraID=$extraID&kuantitas_extra=$kuantitas_extra");
        exit;
    } else {
        echo "Booking gagal: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == "bookFacility") {
        $selectedDate = $_POST['date'];
        $selectedTime = $_POST['timeSlot'];
        $selectedFacility = $_POST['facility'];
        $voucherID = !empty($_POST['voucher']) ? $_POST['voucher'] : null;
        $extraID = !empty($_POST['extra']) ? $_POST['extra'] : null;
        $kuantitas_extra = !empty($_POST['kuantitas_extra']) ? $_POST['kuantitas_extra'] : null;

        bookFacility($selectedDate, $selectedTime, $selectedFacility, $voucherID, $extraID, $kuantitas_extra);
    } elseif (isset($_POST['action']) && $_POST['action'] == "registerCustomer") {
        $name = $_POST['name'];
        $phone = $_POST['phone'];

        registerCustomer($name, $phone);
    }
}



function getBooking($customerID)
{
    $conn = dbConnect("db_badminton");

    $query = "SELECT 
                tb.tanggal_booking AS Tanggal,
                tw.waktu_mulai AS Jam,
                tf.nama_fasilitas AS Lapangan,
                tbk.nama_barang AS Extra,
                te.kuantitas_extra AS Kuantitas_Extra,
                tb.status_booking AS Status
              FROM 
                t_booking tb
              JOIN 
                t_jadwal tj ON tb.id_jadwal = tj.id_jadwal
              JOIN 
                t_waktu tw ON tj.id_waktu = tw.id_waktu
              JOIN 
                t_fasilitas tf ON tb.id_fasilitas = tf.id_fasilitas
              LEFT JOIN 
                t_extra te ON tb.id_booking = te.id_booking
              LEFT JOIN 
                t_barang tbk ON te.id_barang = tbk.id_barang
              WHERE 
                tb.id_customer = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $customerID);
    $stmt->execute();

    $result = $stmt->get_result();

    $bookings = [];

    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $bookings;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == "bookFacility") {
        $selectedDate = $_POST['date'];
        $selectedTime = $_POST['timeSlot'];
        $selectedFacility = $_POST['facility'];
        $voucherID = !empty($_POST['voucher']) ? $_POST['voucher'] : null;
        $extraID = !empty($_POST['extra']) ? $_POST['extra'] : null;
        $kuantitas_extra = !empty($_POST['kuantitas_extra']) ? $_POST['kuantitas_extra'] : null;

        bookFacility($selectedDate, $selectedTime, $selectedFacility, $voucherID, $extraID, $kuantitas_extra);
    } elseif (isset($_POST['action']) && $_POST['action'] == "registerCustomer") {
        $name = $_POST['name'];
        $phone = $_POST['phone'];

        registerCustomer($name, $phone);
    }
}

function getBookingInfo($bookingID)
{
    $conn = dbConnect("db_badminton");

    $sql = "SELECT
                p.harga_total,
                p.status_pembayaran,
                w.nama_waktu AS selectedTime,
                f.nama_fasilitas AS selectedFacility,
                v.nama_voucher AS voucherID,
                e.id_extra,
                b.nama_barang AS extraID
            FROM t_pembayaran p
            JOIN t_booking b ON p.id_booking = b.id_booking
            JOIN t_jadwal j ON b.id_jadwal = j.id_jadwal
            JOIN t_waktu w ON j.id_waktu = w.id_waktu
            JOIN t_fasilitas f ON j.id_fasilitas = f.id_fasilitas
            LEFT JOIN t_voucher v ON b.id_voucher = v.id_voucher
            LEFT JOIN t_extra e ON p.id_extra = e.id_extra
            LEFT JOIN t_barang b ON e.id_barang = b.id_barang
            WHERE p.id_booking = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $bookingID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $bookingInfo = mysqli_fetch_assoc($result);

    mysqli_close($conn);

    return $bookingInfo;
}

function processPayment($bookingID, $harga_pembayaran)
{
    $conn = dbConnect("db_badminton");

    // Ambil tanggal saat ini
    $tanggal_pembayaran = isset($_POST['tanggal_pembayaran']) ? $_POST['tanggal_pembayaran'] : date("Y-m-d");

    // Tentukan jenis pembayaran
    $jenis_pembayaran = "Transfer";

    // Update status pembayaran menjadi "Lunas", masukkan tanggal pembayaran, dan jenis pembayaran
    $updatePaymentStatusQuery = "UPDATE t_pembayaran SET status_pembayaran = 'Lunas', tanggal_pembayaran = ?, jenis_bayar = ? WHERE id_booking = ?";
    $stmt = mysqli_prepare($conn, $updatePaymentStatusQuery);
    mysqli_stmt_bind_param($stmt, 'ssi', $tanggal_pembayaran, $jenis_pembayaran, $bookingID);
    $result = mysqli_stmt_execute($stmt);

    // Jika pembayaran berhasil, tambahkan log atau notifikasi ke pengguna

    mysqli_close($conn);

    return $result;
}
