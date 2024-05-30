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

// Proses penambahan kategori
if (isset($_POST['tambah_rak'])) {
    $nama_rak = $_POST['nama_rak'];
    
    // Query untuk menambah kategori ke database
    $query_tambah = "INSERT INTO rak (nama_rak) VALUES ('$nama_rak')";
    
    if (mysqli_query($conn, $query_tambah)) {
        // Jika penambahan berhasil, arahkan kembali ke halaman kategori
        header("Location: rak.php");
        exit();
    } else {
        // Jika penambahan gagal, tampilkan pesan kesalahan
        echo "Error: " . $query_tambah . "<br>" . mysqli_error($conn);
        exit();
    }
}

// Proses penghapusan kategori
if (isset($_GET['hapus_rak'])) {
    $id_rak = $_GET['hapus_rak'];
    
    // Query untuk menghapus kategori dari database
    $query_hapus = "DELETE FROM rak WHERE id_rak = '$id_rak'";
    
    if (mysqli_query($conn, $query_hapus)) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman kategori
        header("Location: rak.php");
        exit();
    } else {
        // Jika penghapusan gagal, tampilkan pesan kesalahan
        echo "Error: " . $query_hapus . "<br>" . mysqli_error($conn);
        exit();
    }
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

    <title>Perpustakaan - Tambah Peminjaman</title>

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
                    <div class="mb-0 text-gray-800 font-weight-bold">Dashboard</div>
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
                                <h3 class="text-primary">Tambah Peminjaman</h3>
                            </div>
                            <div class="card-body">
                            <?php
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

// Initialize error messages
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $n_id = sanitize($conn, $_POST['n_id']);
    $isbn = sanitize($conn, $_POST['isbn']);
    $tgl_pinjam = sanitize($conn, $_POST['tgl_pinjam']);
    $tgl_kembali = sanitize($conn, $_POST['tgl_kembali']);

    // Check if n_id exists in login table
    $sql_check_npm = "SELECT n_id FROM login WHERE n_id=?";
    $stmt_check_npm = $conn->prepare($sql_check_npm);
    $stmt_check_npm->bind_param("s", $n_id);
    $stmt_check_npm->execute();
    $result_check_npm = $stmt_check_npm->get_result();

    if ($result_check_npm->num_rows == 0) {
        $errors[] = "NPM tidak ada di Daftar Anggota";
    }
    $stmt_check_npm->close();

    // Check if isbn exists in buku table
    $sql_check_isbn = "SELECT isbn FROM buku WHERE isbn=?";
    $stmt_check_isbn = $conn->prepare($sql_check_isbn);
    $stmt_check_isbn->bind_param("s", $isbn);
    $stmt_check_isbn->execute();
    $result_check_isbn = $stmt_check_isbn->get_result();

    if ($result_check_isbn->num_rows == 0) {
        $errors[] = "ISBN tidak ada di Daftar Buku";
    }
    $stmt_check_isbn->close();

    // If no errors, insert the data
    if (empty($errors)) {
        $status = "Dipinjam";
        $sql_insert = "INSERT INTO peminjaman (n_id, isbn, tgl_pinjam, tgl_kembali, status) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssss", $n_id, $isbn, $tgl_pinjam, $tgl_kembali, $status);

        if ($stmt_insert->execute()) {
            echo "<script>window.location.href='peminjaman.php';</script>";
        } else {
            $errors[] = "Error inserting record: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    }
}

// Function to sanitize input data
function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

// Close connection
$conn->close();
?>
            
            <form action="" method="POST">
                    <div class="form-group">
                        <label for="n_id">NPM</label>
                        <input type="text" class="form-control" id="n_id" name="n_id" placeholder="Masukkan NPM" value="<?php echo isset($n_id) ? $n_id : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" placeholder="Masukkan ISBN" value="<?php echo isset($isbn) ? $isbn : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_pinjam">Tanggal Pinjam</label>
                        <input type="date" class="form-control" id="tgl_pinjam" name="tgl_pinjam" value="<?php echo isset($tgl_pinjam) ? $tgl_pinjam : date('Y-m-d'); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="tgl_kembali">Tanggal Kembali</label>
                        <input type="date" class="form-control" id="tgl_kembali" name="tgl_kembali" value="<?php echo isset($tgl_kembali) ? $tgl_kembali : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input class="form-control" type="text" id="status" placeholder="Dipinjam" readonly>
                    </div>
                    <?php
                    if (!empty($errors)) {
                        echo '<div class="alert alert-danger">';
                        foreach ($errors as $error) {
                            echo '<p>' . $error . '</p>';
                        }
                        echo '</div>';
                    }
                    ?>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                    <a href="peminjaman.php" class="btn btn-secondary" name="batal">Kembali</a>
                </form>
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