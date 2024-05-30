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

// Function to sanitize input data
function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

// Update status in pengembalian table based on denda status
function updatePengembalianStatus($conn, $no_pinjam, $status) {
    $sql_update = "UPDATE pengembalian SET status = ? WHERE no_pinjam = ?";
    $stmt_update = $conn->prepare($sql_update);
    if ($stmt_update) {
        $stmt_update->bind_param("ss", $status, $no_pinjam);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        echo "Error preparing update statement: " . $conn->error;
    }
}

// Insert or update denda and synchronize status
if (isset($_POST['no_pinjam']) && isset($_POST['harga_denda']) && isset($_POST['status'])) {
    $no_pinjam = sanitize($conn, $_POST['no_pinjam']);
    $harga_denda = sanitize($conn, $_POST['harga_denda']);
    $status = sanitize($conn, $_POST['status']);

    // Check if denda already exists
    $sql_check = "SELECT * FROM denda WHERE no_pinjam = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $no_pinjam);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Update existing denda
        $sql_update_denda = "UPDATE denda SET harga_denda = ?, status = ? WHERE no_pinjam = ?";
        $stmt_update_denda = $conn->prepare($sql_update_denda);
        $stmt_update_denda->bind_param("sss", $harga_denda, $status, $no_pinjam);
        $stmt_update_denda->execute();
        $stmt_update_denda->close();
    } else {
        // Insert new denda
        $sql_insert_denda = "INSERT INTO denda (no_pinjam, harga_denda, status) VALUES (?, ?, ?)";
        $stmt_insert_denda = $conn->prepare($sql_insert_denda);
        $stmt_insert_denda->bind_param("sss", $no_pinjam, $harga_denda, $status);
        $stmt_insert_denda->execute();
        $stmt_insert_denda->close();
    }
    $stmt_check->close();

    // Update status in pengembalian table
    updatePengembalianStatus($conn, $no_pinjam, $status);
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

    <title>Perpustakaan - Pengembalian</title>

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
                <div class="mb-0 text-gray-800 font-weight-bold">Dashboard | <span class="text-primary">Transaksi</span> | <span class="text-primary">Pengembalian</span></div>
                </div>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                <div class="topbar-divider d-none d-sm-block"></div>
                <!-- include Topbar -->
                    <?php include 'topbar.php'; ?>
            </nav>
            <!-- End Topbar-->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h2 mb-2 text-gray-800">Pengembalian</h1>

                    <!-- DataTales Example -->
        <div class="card-body">

            <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary text-center">Daftar Pengembalian</h6>                                
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>No</th>
                                        <th>No Pinjam</th>
                                        <th>NPM</th>                           
                                        <th>Nama</th>                           
                                        <th>Pinjam</th>
                                        <th>Balik</th>
                                        <th>Status</th>
                                        <th>Denda</th>
                                        <th>Aksi</th>                                                                              
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>No Pinjam</th>
                                        <th>NPM</th >                           
                                        <th>Nama</th >                           
                                        <th>Pinjam</th>
                                        <th>Balik</th>
                                        <th>Status</th>
                                        <th>Denda</th>
                                        <th>Aksi</th>                                        
                                    </tr>
                                </tfoot>
                                <tbody>
                                <?php
// Fetch data from pengembalian table
$sql = "SELECT p.no_pinjam, p.n_id, l.nama, p.tgl_pinjam, p.tgl_kembali, p.status, d.harga_denda 
        FROM pengembalian p
        LEFT JOIN login l ON p.n_id = l.n_id
        LEFT JOIN denda d ON p.no_pinjam = d.no_pinjam";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $no = 1;
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['no_pinjam']) . "</td>";
        echo "<td>" . htmlspecialchars($row['n_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tgl_pinjam']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tgl_kembali']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . (isset($row['harga_denda']) ? 'Rp' . number_format($row['harga_denda'], 0, ',', '.') . ',-' : 'Rp0,-') . "</td>";
        echo "<td>                                                    
                <a href='delete_pengembalian.php?no_pinjam=" . urlencode($row['no_pinjam']) . "' class='btn btn-sm bg-danger text-white my-1 my-lg-0'><i class='fa fa-trash' aria-hidden='true'></i></a>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
}

// Close connection
$conn->close();
?>



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
            <!-- /.container-fluid -->
            
        </div>
        <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer> -->
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
                    <a class="btn btn-primary" href="login.php?action=logout">Logout</a>
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