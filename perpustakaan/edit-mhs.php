<?php
session_start();
include 'koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['nama'])) {
    // Jika belum, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Proses logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Ambil nama pengguna dan n_id dari sesi
$nama_login = $_SESSION['nama'];

// Query untuk mengambil informasi profil dari database berdasarkan nama pengguna yang sedang login
$query_login = "SELECT * FROM login WHERE nama = '$nama_login'";
$result_login = mysqli_query($conn, $query_login);

// Periksa apakah query berhasil
if ($result_login) {
    // Ambil baris hasil query sebagai asosiatif array
    $row_login = mysqli_fetch_assoc($result_login);

    // Ambil informasi profil dari baris hasil query
    $npm_login = $row_login['n_id'];
    $nama_login = $row_login['nama'];
    $status_login = $row_login['status'];
    $pp_login = $row_login['pp'];
} else {
    // Jika query gagal, tampilkan pesan kesalahan
    echo "Error: " . $query_login . "<br>" . mysqli_error($conn);
    exit();
}

// Cek apakah parameter npm tersedia dalam URL untuk mengedit pengguna lain
if (isset($_GET['npm'])) {
    $npm_edit = $_GET['npm'];

    // Query untuk mengambil data mahasiswa berdasarkan npm
    $query_edit = "SELECT nama, n_id, email, status, tlp, pp FROM login WHERE n_id = ?";
    $stmt_edit = $conn->prepare($query_edit);

    // Bind parameter npm ke pernyataan SQL
    $stmt_edit->bind_param("s", $npm_edit);
    $stmt_edit->execute();
    $stmt_edit->store_result();

    // Bind hasil dari pernyataan SQL ke variabel
    $stmt_edit->bind_result($nama_edit, $n_id_edit, $email_edit, $status_edit, $tlp_edit, $pp_edit);

    // Ambil baris hasil
    $stmt_edit->fetch();

    // Tutup pernyataan SQL
    $stmt_edit->close();
} else {
    // Jika parameter npm tidak tersedia dalam URL, berikan pesan error
    echo "Parameter npm tidak tersedia dalam URL.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Perpustakaan - Edit Profile</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .profile-card {
            margin: 50px auto;
            max-width: 400px;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 20px auto;
        }
        #two {
            margin-bottom: 20px;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- End of Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="d-none d-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100">
                        <div class="mb-0 text-gray-800">Dashboard | <span class="text-primary">Edit Profil</span></div>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 medium"><?= $nama_login; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?=$pp_login ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profil_admin.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>                            
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>
                </nav>
                <!-- End of Topbar -->

                <div class="container-fluid">
                    <div class="container mt-5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-primary">Edit Profile</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="proses_edit.php" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="nama">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama_edit; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="npm">NPM</label>
                                        <input type="text" class="form-control" id="npm" name="npm" value="<?php echo $n_id_edit; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email_edit; ?>" placeholder="contoh@example.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="tlp">No Telepon</label>
                                        <input type="tel" class="form-control" id="tlp" name="tlp" value="<?php echo $tlp_edit; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pp">Photo Profile ( JPG / PNG )</label>
                                        <input type="file" class="form-control" id="pp" name="pp">
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                                    <a href="index.php" class="btn btn-secondary">Batal</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2021</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/demo/datatables-demo.js"></script>
</body>
</html>
