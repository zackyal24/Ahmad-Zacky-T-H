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

// Ambil nama pengguna dari sesi
$nama = $_SESSION['nama'];

// Query untuk mengambil informasi profil dari database berdasarkan nama pengguna
$query = "SELECT * FROM login WHERE nama = '$nama'";

// Jalankan query
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil
if (!$result) {
    // Jika query gagal, tampilkan pesan kesalahan
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
    exit(); // Keluar dari skrip jika query gagal
}

// Ambil baris hasil query sebagai asosiatif array
$row = mysqli_fetch_assoc($result);

// Ambil informasi profil dari baris hasil query
$npm = $row['n_id'];
$nama = $row['nama'];
$status = $row['status'];
$pp = $row['pp'];

// Periksa apakah isbn tersedia dalam $_GET atau $_POST
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
} elseif (isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];
} else {
    echo "ISBN tidak ditemukan!";
    exit();
}

// Query untuk mengambil informasi buku dari database
$query_buku = "SELECT * FROM buku WHERE isbn = '$isbn'"; // Sesuaikan dengan primary key atau kriteria unik buku

// Jalankan query buku
$result_buku = mysqli_query($conn, $query_buku);

// Periksa apakah query buku berhasil
if (!$result_buku) {
    // Jika query buku gagal, tampilkan pesan kesalahan
    echo "Error: " . $query_buku . "<br>" . mysqli_error($conn);
    exit(); // Keluar dari skrip jika query buku gagal
}

// Ambil informasi buku dari baris hasil query buku
$row_buku = mysqli_fetch_assoc($result_buku);

// Pastikan untuk mendefinisikan variabel buku jika query berhasil
if ($row_buku) {
    $judul = $row_buku['judul'];
    $isbn = $row_buku['isbn'];
    $tgl_masuk = $row_buku['tgl_masuk'];
    $pengarang = $row_buku['pengarang'];
    $stok = $row_buku['stok'];
    // Dan seterusnya sesuai kebutuhan
} else {
    // Handle jika buku tidak ditemukan
    echo "Buku tidak ditemukan!";
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
    <title>Perpustakaan - Edit Data Buku</title>

    <!-- CSS -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"
        type='text/css'>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="d-none d-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100">
                        <div class="mb-0 text-gray-800">Dashboard | <span class="text-primary">Data</span> | <span
                                class="text-primary">Kategori</span></div>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- include Topbar -->
                        <?php include 'topbar.php'; ?>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="container mt-5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-primary">Edit Data Buku</h3>
                            </div>
                            <div class="card-body">
                            <form method="POST" action="proses_edit_buku.php" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="title">Judul</label>
                                    <input type="text" class="form-control" id="title" name="judul" value="<?php echo $judul; ?>" placeholder="Judul">
                                </div>
                                <div class="form-group">
                                    <label for="isbn">ISBN</label>
                                    <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo $isbn; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="tgl_masuk">Tanggal Masuk</label>
                                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="<?php echo $tgl_masuk; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="pengarang">Pengarang</label>
                                    <input type="text" class="form-control" id="pengarang" name="pengarang" value="<?php echo $pengarang; ?>" placeholder="Pengarang">
                                </div>
                                <div class="form-group">
                                    <label for="stok">Stok Buku</label>
                                    <input type="text" class="form-control" id="stok" name="stok" value="<?php echo $stok; ?>" placeholder="Stok Buku">
                                </div>
                                <div class="form-group">
                                    <label for="sampul">File JPG/PNG</label>
                                    <input type="file" class="form-control" id="sampul" name="sampul">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="tables.html" class="btn btn-secondary">Batal</a>
                            </form>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
</body>

</html>
