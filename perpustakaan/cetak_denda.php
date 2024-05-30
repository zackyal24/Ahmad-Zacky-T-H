<?php
require('fpdf/fpdf.php');

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

// Get the no_pinjam from the URL
$no_pinjam = isset($_GET['no_pinjam']) ? intval($_GET['no_pinjam']) : 0;

if ($no_pinjam > 0) {
    // Query to fetch data from denda table
    $sql = "SELECT d.no_pinjam, l.nama, b.judul, p.tgl_kembali, d.harga_denda, d.status, d.id_denda 
            FROM denda d
            JOIN pengembalian p ON d.no_pinjam = p.no_pinjam
            JOIN login l ON p.n_id = l.n_id
            JOIN buku b ON p.isbn = b.isbn
            WHERE d.no_pinjam = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $no_pinjam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Create instance of FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Add content to the PDF
        $pdf->Cell(0, 10, 'Detail Denda', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'No Denda:', 1);
        $pdf->Cell(0, 10, $row['id_denda'], 1, 1);
        $pdf->Cell(50, 10, 'Nama:', 1);
        $pdf->Cell(0, 10, $row['nama'], 1, 1);
        $pdf->Cell(50, 10, 'Buku:', 1);
        $pdf->Cell(0, 10, $row['judul'], 1, 1);
        $pdf->Cell(50, 10, 'Tanggal Pinjam:', 1);
        $pdf->Cell(0, 10, $row['tgl_kembali'], 1, 1);
        $pdf->Cell(50, 10, 'Denda:', 1);
        $pdf->Cell(0, 10, 'Rp' . number_format($row['harga_denda'], 0, ',', '.'), 1, 1);
        $pdf->Cell(50, 10, 'Keterangan:', 1);
        $pdf->Cell(0, 10, $row['status'], 1, 1);

        // Output the PDF
        $pdf->Output('I', 'denda_' . $row['id_denda'] . '.pdf');
    } else {
        echo "No record found.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>
