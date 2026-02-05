<?php
$conn = mysqli_connect("localhost", "root", "", "cuci_sepatu");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
