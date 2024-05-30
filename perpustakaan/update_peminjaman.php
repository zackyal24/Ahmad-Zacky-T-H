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

// Function to sanitize input data
function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

// Check if no_pinjam is set in the URL
if (isset($_GET['no_pinjam'])) {
    $no_pinjam = sanitize($conn, $_GET['no_pinjam']);

    // Fetch the record from peminjaman
    $sql_fetch = "SELECT * FROM peminjaman WHERE no_pinjam = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    if ($stmt_fetch) {
        $stmt_fetch->bind_param("s", $no_pinjam);
        $stmt_fetch->execute();
        $result = $stmt_fetch->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $npm = $row['n_id'];
            $isbn = $row['isbn'];
            $tgl_pinjam = $row['tgl_pinjam'];
            $tgl_kembali = $row['tgl_kembali'];
            $status = 'Dikembalikan';

            // Insert record into pengembalian table
            $sql_insert = "INSERT INTO pengembalian (no_pinjam, n_id, isbn, tgl_pinjam, tgl_kembali, status, jumlah_pengembalian) VALUES (?, ?, ?, ?, ?, ?, 1)";
            $stmt_insert = $conn->prepare($sql_insert);
            if ($stmt_insert) {
                $stmt_insert->bind_param("ssssss", $no_pinjam, $npm, $isbn, $tgl_pinjam, $tgl_kembali, $status);
                if ($stmt_insert->execute()) {
                    // Delete the record from peminjaman table
                    $sql_delete = "DELETE FROM peminjaman WHERE no_pinjam = ?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    if ($stmt_delete) {
                        $stmt_delete->bind_param("s", $no_pinjam);
                        if ($stmt_delete->execute()) {
                            echo "<script>window.location.href='pengembalian.php';</script>";
                        } else {
                            echo "Error deleting record: " . $stmt_delete->error;
                        }
                    } else {
                        echo "Error preparing delete statement: " . $conn->error;
                    }
                } else {
                    echo "Error inserting record: " . $stmt_insert->error;
                }
            } else {
                echo "Error preparing insert statement: " . $conn->error;
            }
        } else {
            echo "No record found with the provided no_pinjam.";
        }

        // Close statements
        $stmt_fetch->close();
        if (isset($stmt_insert)) {
            $stmt_insert->close();
        }
        if (isset($stmt_delete)) {
            $stmt_delete->close();
        }
    } else {
        echo "Error preparing fetch statement: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>
