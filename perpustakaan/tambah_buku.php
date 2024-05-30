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
// Ambil nama pengguna dan n_id dari sesi
$nama = $_SESSION['nama'];


// Query untuk mengambil informasi profil dari database berdasarkan nama pengguna
$query = "SELECT * FROM login WHERE nama = '$nama'";

// Jalankan query
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
    // Tambahan jika ada informasi lainnya yang ingin ditampilkan
} else {
    // Jika query gagal, tampilkan pesan kesalahan
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
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

    <title>Perpustakaan - Tambah Buku</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

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
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="d-none d-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100">
                        <div class="mb-0 text-gray-800">Dashboard | <span class="text-primary">Data</span> | <span class="text-primary">Kategori</span></div>
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
                                <h3 class="text-primary">Tambah Buku</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="proses_tambah_buku.php" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="title">Judul</label>
                                        <input type="text" name="judul" class="form-control" id="nama" placeholder="Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_kategori">Kategori</label>
                                        <select name="nama_kategori" id="nama_kategori" class="form-control">
                                        <?php
                                            // Query untuk mengambil data kategori dari tabel kategori
                                            $query_kategori = "SELECT * FROM kategori";
                                            $result_kategori = mysqli_query($conn, $query_kategori);

                                            // Periksa apakah query berhasil
                                            if ($result_kategori) {
                                                // Loop untuk menampilkan opsi kategori
                                                while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
                                                    echo "<option value='" . $row_kategori['id_kategori'] . "'>" . $row_kategori['nama_kategori'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>Tidak ada kategori tersedia</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_rak">Rak Buku</label>
                                        <select name="id_rak" id="id_rak" class="form-control">
                                            <?php
                                            // Query untuk mengambil data rak buku dari tabel rak
                                            $query_rak = "SELECT * FROM rak";
                                            $result_rak = mysqli_query($conn, $query_rak);

                                            // Periksa apakah query berhasil
                                            if ($result_rak && mysqli_num_rows($result_rak) > 0) {
                                                while ($row_rak = mysqli_fetch_assoc($result_rak)) {
                                                    echo "<option value='" . $row_rak['id_rak'] . "'>" . $row_rak['nama_rak'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>Tidak ada rak buku tersedia</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>                                 
                                    <div class="form-group">
                                        <label for="isbn">ISBN</label>
                                        <input type="text" name="isbn" class="form-control" id="isbn" placeholder="ISBN">
                                    </div>
                                    <div class="form-group">
                                        <label for="penerbit">Penerbit</label>
                                        <input type="text" name="penerbit" class="form-control" id="penerbit" placeholder="Penerbit">
                                    </div>
                                    <div class="form-group">
                                        <label for="tgl_masuk">Tanggal Masuk</label>
                                        <input type="date" name="tgl_masuk" class="form-control" id="tgl_masuk">
                                    </div>
                                    <div class="form-group">
                                        <label for="pengarang">Pengarang</label>
                                        <input type="text" name="pengarang" class="form-control" id="pengarang" placeholder="Pengarang">
                                    </div>
                                    <div class="form-group">
                                        <label for="jml">Stok Buku</label>
                                        <input type="text" name="jml" class="form-control" id="jml" placeholder="Stok Buku">
                                    </div>                                   
                                    <div class="form-group">
                                        <label for="sampul">Sampul Buku ( JPG / PNG )</label>
                                        <input type="file" name="sampul" class="form-control" id="sampul">
                                    </div>                                   
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                    <a href="daftar_buku.php" class="btn btn-secondary" name="edit">Batal</a>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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