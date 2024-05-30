<?php
// Check if a session is already started before calling session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Assume a session or authentication system that sets the user status
$status = $_SESSION['status']; // This should be set to 'admin' or 'member'

// Start of the sidebar HTML
echo '
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

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

// Conditional Dashboard Link
if ($status == 'anggota') {
    echo '
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="home.php">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
    </li>
    ';
} 


echo '
    <!-- Divider -->
    <hr class="sidebar-divider">
';

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

if ($status == 'anggota') {
    echo '
                <a class="collapse-item" href="peminjaman-mhs.php"><i class="fa fa-angle-double-up text-success p-2" aria-hidden="true"></i>Peminjaman</a>
                <a class="collapse-item" href="pengembalian-mhs.php"><i class="fa fa-angle-double-down text-warning p-2" aria-hidden="true"></i>Pengembalian</a>';
}

echo '
            </div>
        </div>
    </li>
';


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
