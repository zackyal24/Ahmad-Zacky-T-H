<?php
session_start();

// Sertakan file koneksi.php
include 'koneksi.php';

// Periksa apakah pengguna sudah login
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

// Ambil nama pengguna dari sesi
$nama = $_SESSION['nama'];

// Query untuk mengambil informasi profil dari database berdasarkan nama pengguna
$query = "SELECT * FROM login WHERE nama = '$nama'";
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil
if ($result) {
    // Ambil baris hasil query sebagai asosiatif array
    $row = mysqli_fetch_assoc($result);

    // Ambil informasi profil dari baris hasil query
    $npm = $row['n_id'];
    $nama = $row['nama'];
    $status = $row['status'];
    $pp = $row['pp'];
} else {
    // Jika query gagal, tampilkan pesan kesalahan
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
    exit();
}

// Ambil id_rak dari parameter URL
if (isset($_GET['id'])) {
    $id_rak = $_GET['id'];
} else {
    // Jika id_rak tidak ada, arahkan ke halaman lain atau tampilkan pesan kesalahan
    echo "ID rak tidak ditemukan.";
    exit();
}

// Query untuk mengambil data rak berdasarkan id_rak
$query_rak = "SELECT * FROM rak WHERE id_rak = '$id_rak'";
$result_rak = mysqli_query($conn, $query_rak);

if ($result_rak && mysqli_num_rows($result_rak) > 0) {
    $row_rak = mysqli_fetch_assoc($result_rak);
    $nama_rak = $row_rak['nama_rak'];
} else {
    echo "Error: " . $query_rak . "<br>" . mysqli_error($conn);
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

    <title>Perpustakaan - Edit Rak</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
</head>

<body id="page-top">

    <div id="wrapper">
        <?php include 'sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="d-none d-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100">
                        <div class="mb-0 text-gray-800">Dashboard | <span class="text-primary">Data</span> | <span class="text-primary">Kategori</span></div>
                    </div>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <?php include 'topbar.php'; ?>
                    </ul>
                </nav>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Edit Rak</h1>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Form Edit Rak</h6>
                        </div>
                        <div class="card-body">
                            <form action="proses_edit_rak.php" method="POST">
                                <input type="hidden" name="id_rak" value="<?php echo $id_rak; ?>">
                                <div class="form-group">
                                    <label for="nama_rak">Nama Rak</label>
                                    <input type="text" class="form-control" id="nama_rak" name="nama_rak" value="<?php echo $nama_rak; ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="rak.php" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
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
                    <a class="btn btn-primary" href="login.php?action=logout">Logout</a>
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
