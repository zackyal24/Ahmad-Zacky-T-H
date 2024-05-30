<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

$nama_pengguna = $_SESSION['nama'];
$n_id = $_GET['n_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password_baru = $_POST['konfirmasi_password_baru'];

    // Periksa apakah password baru dan konfirmasi password baru cocok
    if ($password_baru === $konfirmasi_password_baru) {
        // Query untuk mengambil password lama dari database
        $query = "SELECT pass FROM login WHERE n_id = '$n_id'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hashed_password_lama = $row['pass'];

            // Verifikasi password lama
            if (password_verify($password_lama, $hashed_password_lama)) {
                // Hash password baru
                $hashed_password_baru = password_hash($password_baru, PASSWORD_BCRYPT);

                // Update password di database
                $update_query = "UPDATE login SET pass = '$hashed_password_baru' WHERE n_id = '$n_id'";
                if (mysqli_query($conn, $update_query)) {

                    // Redirect berdasarkan jenis akun
                    if ($_SESSION['status'] == 'admin') {
                        header("Location: profil_admin.php");
                    } elseif ($_SESSION['status'] == 'anggota') {
                        header("Location: profil_mhs.php");
                    } else {
                        // Default fallback jika jenis akun tidak dikenal
                        header("Location: login.php");
                    }
                    exit();
                } else {
                    $_SESSION['error'] = "Gagal mengubah password. Silakan coba lagi.";
                }
            } else {
                $_SESSION['error'] = "Password lama tidak sesuai.";
            }
        } else {
            $_SESSION['error'] = "Gagal mengambil data pengguna.";
        }
    } else {
        $_SESSION['error'] = "Password baru dan konfirmasi password baru tidak cocok.";
    }
}

// Jika terjadi error, kembali ke halaman edit password dengan pesan error
header("Location: edit-password.php?n_id=$n_id");
exit();
?>
