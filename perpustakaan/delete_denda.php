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
    // Get the no_pinjam value and sanitize it
    $no_pinjam = $conn->real_escape_string($_GET['no_pinjam']);
    
    // Create the SQL query
    $sql = "DELETE FROM denda WHERE no_pinjam = '$no_pinjam'";

    // Execute the query and check the result
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href='denda.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>
