<?php
session_start(); // Mulai session

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error_message = '';

    $npm = $_POST['npm'];
    $password = $_POST['password'];

    // Cek apakah NPM dan password telah diisi
    if (empty($npm) || empty($password)) {
        $error_message = "NPM dan Password harus diisi";
        $_SESSION['error_message'] = $error_message;
        header("Location: login.php"); // Redirect kembali ke halaman login
        exit();
    } else {
        // Proses login jika NPM dan password diisi
        $sql = "SELECT * FROM login WHERE n_id = '$npm' AND pass = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['status'] == 'Admin') {
                $_SESSION['user_role'] = 'Admin';
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['user_role'] = 'Anggota';
                header("Location: home.php");
                exit();
            }
        } else {
            $error_message = "NPM atau Password salah";
            $_SESSION['error_message'] = $error_message;
            header("Location: login.php"); // Redirect kembali ke halaman login
            exit();
        }
    }
}

$conn->close();
?>
