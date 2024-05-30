<?php
// Check if a session is already started before calling session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Assume a session or authentication system that sets the user status
$status = $_SESSION['status']; // This should be set to 'admin' or 'member'

// Start of the sidebar HTML

echo '<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">';

if ($status == 'admin'){
    echo '
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div>
                <img src="img/icon_perpus.png" alt="Library icon" class="img-fluid">
            </div>
            <div class="sidebar-brand-text mx-2">Perpustakaan <sup>' . ucfirst($status) . '</sup></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">
    ';
}else{
    echo '
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php">
            <div>
                <img src="img/icon_perpus.png" alt="Library icon" class="img-fluid">
            </div>
            <div class="sidebar-brand-text mx-2">Perpustakaan <sup>' . ucfirst($status) . '</sup></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">
    ';
};


// Conditional Dashboard Link
if ($status == 'admin') {
    echo '
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    ';
} else {
    echo '
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="home.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    ';
}

echo '
    <!-- Divider -->
    <hr class="sidebar-divider">
';

// Admin-only Data Section
if ($status == 'admin') {
    echo '
    <!-- Nav Item - Data-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fa fa-table" aria-hidden="true"></i>
            <span>Data</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="daftar_buku.php"><i class="fa fa-book text-gray-600 p-2"></i>Daftar Buku</a>
                <a class="collapse-item" href="kategori.php"><i class="fas fa-hashtag text-gray-600 p-2"></i>Kategori</a>
                <a class="collapse-item" href="rak.php"><i class="fa fa-th-list text-gray-600 p-2"></i>Rak</a>
            </div>
        </div>
    </li>
    ';
}

// Transaksi Section with Conditional Links
echo '
    <!-- Nav Item - Transaksi -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fa fa-exchange"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">';

if ($status == 'admin') {
    echo '
                <a class="collapse-item" href="peminjaman.php"><i class="fa fa-angle-double-up text-success p-2" aria-hidden="true"></i>Peminjaman</a>
                <a class="collapse-item" href="pengembalian.php"><i class="fa fa-angle-double-down text-warning p-2" aria-hidden="true"></i>Pengembalian</a>';
} else {
    echo '
                <a class="collapse-item" href="peminjaman-mhs.php"><i class="fa fa-angle-double-up text-success p-2" aria-hidden="true"></i>Peminjaman</a>
                <a class="collapse-item" href="pengembalian-mhs.php"><i class="fa fa-angle-double-down text-warning p-2" aria-hidden="true"></i>Pengembalian</a>';
}

echo '
            </div>
        </div>
    </li>
';

// Admin-specific Denda Section
if ($status == 'admin') {
    echo '
    <!-- Nav Item - Denda-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="denda.php">
            <i class="fa fa-money" aria-hidden="true"></i>
            <span>Denda</span>
        </a>
    </li>
    ';
}

echo '
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
';
?>
