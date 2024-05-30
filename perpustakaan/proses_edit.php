<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $npm = $_POST['npm'];
    $email = $_POST['email'];
    $tlp = $_POST['tlp'];

    // Cek apakah ada file gambar yang diupload
    if ($_FILES['pp']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/profil/";
        $target_file =$target_dir . basename($_FILES["pp"]["name"]);
        move_uploaded_file($_FILES["pp"]["tmp_name"], $target_file);
        $pp = $target_file;
    } else {
        // Ambil file gambar lama dari database
        $query = "SELECT pp FROM login WHERE n_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $npm);
        $stmt->execute();
        $stmt->bind_result($pp_lama);
        $stmt->fetch();
        $pp = $pp_lama;
        $stmt->close();
    }


    // Update data ke database
    $query = "UPDATE login SET email = ?, tlp = ?, pp = ? WHERE n_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $email, $tlp, $pp, $npm);

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
