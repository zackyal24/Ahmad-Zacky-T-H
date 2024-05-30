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

// Check if no_pinjam is set in the URL
if (isset($_GET['no_pinjam'])) {
    $no_pinjam = $_GET['no_pinjam'];

    // Delete the record from the peminjaman table
    $sql = "DELETE FROM peminjaman WHERE no_pinjam = '$no_pinjam'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href='peminjaman.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>
