<?php
// proses_tambah_denda.php

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perpustakaan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitize($conn, $_POST['nama']);
    $judul = sanitize($conn, $_POST['judul']);
    $no_pinjam = sanitize($conn, $_POST['no_pinjam']);
    $harga_denda = sanitize($conn, $_POST['harga_denda']);
    $status = sanitize($conn, $_POST['status']);
    $tgl_kembali = sanitize($conn, $_POST['tgl_kembali']);

    // Check if no_pinjam exists in the pengembalian table
    $sql_check = "SELECT * FROM pengembalian WHERE no_pinjam=?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $no_pinjam);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Insert data into denda table
        $sql_insert = "INSERT INTO denda (no_pinjam, harga_denda, status, tgl_kembali) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssss", $no_pinjam, $harga_denda, $status, $tgl_kembali);

        if ($stmt_insert->execute()) {
            // Update the corresponding pengembalian record
            $sql_update = "UPDATE pengembalian SET status=?, tgl_kembali=? WHERE no_pinjam=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sss", $status, $tgl_kembali, $no_pinjam);
            $stmt_update->execute();
            $stmt_update->close();

            echo "Data successfully inserted.";
            // Redirect to denda.php or show a success message
            echo "<script>window.location.href='denda.php';</script>";
        } else {
            echo "Error inserting data: " . $stmt_insert->error;
        }
        $stmt_insert->close();
    } else {
        echo "No pinjam tidak ditemukan.";
    }
    $stmt_check->close();
}

// Close connection
$conn->close();
?>
