<?php
// Include file koneksi
include 'koneksi.php';

session_start();
// Periksa apakah sudah login
if (!isset($_SESSION['nama'])) {
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

// Ambil nama dari sesi
$nama = $_SESSION['nama'];

// Query untuk mengambil informasi profil dari database berdasarkan nama pengguna
$query = "SELECT * FROM login WHERE nama = '$nama'";

// Jalankan query
$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nama = $row['nama'];
        $status = $row['status'];
        $pp = $row['pp'];
        // Tambahan jika ada informasi lainnya yang ingin ditampilkan
    } else {
        echo "Query tidak mengembalikan baris hasil.";
    }
} else {
    // Jika query gagal, tampilkan pesan kesalahan
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}

// Proses menambahkan data pengembalian dan mengurangi data peminjaman
if (isset($_POST['no_pinjam']) && isset($_POST['jumlah_pengembalian'])) {
    $no_pinjam = $_POST['no_pinjam'];
    $jumlah_pengembalian = $_POST['jumlah_pengembalian'];

    // Kurangi data peminjaman
    $update_peminjaman = "UPDATE peminjaman SET status = 'Dipinjam' WHERE no_pinjam = '$no_pinjam'";
    mysqli_query($conn, $update_peminjaman);

    // Tambah data pengembalian
    $insert_pengembalian = "INSERT INTO pengembalian (no_pinjam, jumlah_pengembalian, status) VALUES ('$no_pinjam', '$jumlah_pengembalian', 'Dikembalikan')";
    mysqli_query($conn, $insert_pengembalian);

    // Update stok buku
    $get_isbn_query = "SELECT isbn FROM peminjaman WHERE no_pinjam = '$no_pinjam'";
    $result = mysqli_query($conn, $get_isbn_query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $isbn = $row['isbn'];
        $update_stok = "UPDATE buku SET stok = stok + $jumlah_pengembalian WHERE isbn = '$isbn'";
        mysqli_query($conn, $update_stok);
    }
}

// Proses menghapus data pengembalian dan menambah kembali data peminjaman
if (isset($_POST['delete_no_pinjam'])) {
    $no_pinjam = $_POST['delete_no_pinjam'];

    // Ambil jumlah pengembalian sebelum dihapus
    $select_pengembalian = "SELECT jumlah_pengembalian, isbn FROM pengembalian WHERE no_pinjam = '$no_pinjam'";
    $result = mysqli_query($conn, $select_pengembalian);
    $row = mysqli_fetch_assoc($result);
    $jumlah_pengembalian = $row['jumlah_pengembalian'];
    $isbn = $row['isbn'];

    // Hapus data pengembalian
    $delete_pengembalian = "DELETE FROM pengembalian WHERE no_pinjam = '$no_pinjam'";
    mysqli_query($conn, $delete_pengembalian);

    // Kurangi stok buku
    $update_stok = "UPDATE buku SET stok = stok - $jumlah_pengembalian WHERE isbn = '$isbn'";
    mysqli_query($conn, $update_stok);
}

// Query untuk mengambil data pengembalian beserta jumlah pengembalian
$query = "SELECT no_pinjam, jumlah_pengembalian FROM pengembalian";
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil dijalankan
if ($result) {
    // Tampilkan hasil
    while ($row = mysqli_fetch_assoc($result)) {
        // echo "No. Pinjam: " . $row['no_pinjam'] . " - Jumlah Pengembalian: " . $row['jumlah_pengembalian'] . "<br>";
    }
} else {
    // Jika query gagal, tampilkan pesan kesalahan
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}

// Tutup koneksi database
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Perpustakaan - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>

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
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- Content Row -->
<div class="row">

    <?php
    // Koneksi ke database
    $koneksi = mysqli_connect("localhost", "root", "", "perpustakaan");

    // Periksa koneksi
    if (mysqli_connect_errno()) {
        echo "Koneksi database gagal: " . mysqli_connect_error();
    }

    // Query untuk mengambil jumlah peminjaman
    $query_peminjaman = "SELECT COUNT(*) as total_peminjaman FROM peminjaman";
    $result_peminjaman = mysqli_query($koneksi, $query_peminjaman);
    $data_peminjaman = mysqli_fetch_assoc($result_peminjaman);
    $total_peminjaman = $data_peminjaman['total_peminjaman'];

    // Query untuk mengambil jumlah pengembalian
    $query_pengembalian = "SELECT COUNT(*) as total_pengembalian FROM pengembalian";
    $result_pengembalian = mysqli_query($koneksi, $query_pengembalian);
    $data_pengembalian = mysqli_fetch_assoc($result_pengembalian);
    $total_pengembalian = $data_pengembalian['total_pengembalian'];

    // Query untuk mengambil jumlah anggota
    $query_anggota = "SELECT COUNT(*) as total_anggota FROM login";
    $result_anggota = mysqli_query($koneksi, $query_anggota);
    $data_anggota = mysqli_fetch_assoc($result_anggota);
    $total_anggota = $data_anggota['total_anggota'];

    // Query untuk mengambil jumlah buku
    $query_buku = "SELECT COUNT(*) as total_buku FROM buku";
    $result_buku = mysqli_query($koneksi, $query_buku);
    $data_buku = mysqli_fetch_assoc($result_buku);
    $total_buku = $data_buku['total_buku'];

    // Tutup koneksi database
    mysqli_close($koneksi);
    ?>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Peminjaman</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_peminjaman; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa fa-book fa-2x text-success"></i>
                        <i class="fa fa-angle-double-up text-success" aria-hidden="true"></i>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pengembalian</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_pengembalian; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa fa-book fa-2x text-warning"></i>
                        <i class="fa fa-angle-double-down text-warning" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Anggota</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_anggota; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa fa-user fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Buku</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_buku; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa fa-book fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Tabel CRUD-->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Akun</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">                        
            <a href="tambah-anggota.php" class="d-none d-inline-block btn btn-md btn-primary shadow-sm mt-2 "><i
                class="fas fa-plus-circle fa-lg text-white-50"></i> Tambah Akun</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NPM</th>
                            <th>User</th>                                        
                            <th>Status</th>                                                                                
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NPM</th>
                            <th>email</th>                    
                            <th>Status</th>                                                            
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        // Koneksi ke database
                        $koneksi = mysqli_connect("localhost", "root", "", "perpustakaan");
                        
                        // Periksa koneksi
                        if (mysqli_connect_errno()) {
                            echo "Koneksi database gagal: " . mysqli_connect_error();
                        }
                        
                        // Query untuk mengambil data dari database
                        $query = "SELECT * FROM login";
                        $result = mysqli_query($koneksi, $query);
                        
                        // Periksa apakah query berhasil dijalankan
                        if ($result) {
                            // Jika data ditemukan, tampilkan dalam tabel
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $no . "</td>";
                                echo "<td>" . $row['nama'] . "</td>";
                                echo "<td>" . $row['n_id'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "<td>
                                    <a href='edit-mhs.php?npm=" . $row['n_id'] . "' class='btn btn-sm bg-primary text-white my-1 my-lg-0' data-id='" . $row['n_id'] . "'><i class='fa fa-pencil-square' aria-hidden='true'></i></a>
                                    <a href='' class='btn btn-sm bg-danger text-white my-1 my-lg-0 delete-btn' data-id='" . $row['n_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>
                                </td>";
                                echo "</tr>";
                                $no++;
                            }
                        } else {
                            // Jika query gagal, tampilkan pesan kesalahan
                            echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
                        }
                        
                        // Tutup koneksi database
                        mysqli_close($koneksi);
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
                <a class="btn btn-primary" href="index.php?action=logout">Logout</a>
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

<script>
    $(document).ready(function() {
        // Menangani klik pada tombol hapus
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            // Mengambil id data yang akan dihapus
            var id = $(this).data('id');
            // Konfirmasi penghapusan
            if (confirm("Anda yakin ingin menghapus data ini?")) {
                // Kirim permintaan AJAX ke file PHP yang akan menghapus data
                $.ajax({
                    type: 'POST',
                    url: 'hapus_data.php', // Ganti 'hapus_data.php' dengan nama file PHP yang sesuai
                    data: { id: id }, // Kirim id data yang akan dihapus
                    success: function(response) {
                        // Muat ulang halaman setelah data dihapus
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
</script>


</body>

</html>


