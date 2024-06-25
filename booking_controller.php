<?php
include_once("database/config.php");

$conn = dbConnect("db_badminton");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tanggal = $_POST['tanggal'];

    // Get id_tanggal from t_tanggal table
    $sql_tanggal = "SELECT id_tanggal FROM t_tanggal WHERE tanggal = '$tanggal'";
    $result_tanggal = $conn->query($sql_tanggal);

    if ($result_tanggal->num_rows > 0) {
        $row_tanggal = $result_tanggal->fetch_assoc();
        $id_tanggal = $row_tanggal['id_tanggal'];

        // Get available times from t_jadwal and t_waktu tables
        $sql_waktu = "SELECT t_waktu.id_waktu, t_waktu.waktu_mulai 
                      FROM t_jadwal 
                      JOIN t_waktu ON t_jadwal.id_waktu = t_waktu.id_waktu 
                      WHERE t_jadwal.id_tanggal = $id_tanggal AND t_jadwal.status = 0";
        $result_waktu = $conn->query($sql_waktu);

        if ($result_waktu->num_rows > 0) {
            echo '<select id="waktu" name="waktu" required>';
            while ($row_waktu = $result_waktu->fetch_assoc()) {
                echo '<option value="' . $row_waktu['id_waktu'] . '">' . $row_waktu['waktu_mulai'] . '</option>';
            }
            echo '</select>';
        } else {
            echo 'No available times for the selected date.';
        }
    } else {
        echo 'Invalid date selected.';
    }
}

$conn->close();
