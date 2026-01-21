<?php
require_once("../config/koneksi.php");

// Tambah Pelanggan
if (isset($_POST['tambah'])) {
  $nama = $_POST['nama_pelanggan'];
  $alamat = $_POST['alamat'];
  $telepon = $_POST['telepon'];

  $stmt = $conn->prepare("INSERT INTO pelanggan (nama_pelanggan, alamat, telepon) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $nama, $alamat, $telepon);
  $stmt->execute();
  header("Location: ../pages/pelanggan.php");
  exit;
}

// Edit Pelanggan
if (isset($_POST['edit'])) {
  $id = $_POST['id_pelanggan'];
  $nama = $_POST['nama_pelanggan'];
  $alamat = $_POST['alamat'];
  $telepon = $_POST['telepon'];

  $stmt = $conn->prepare("UPDATE pelanggan SET nama_pelanggan=?, alamat=?, telepon=? WHERE id_pelanggan=?");
  $stmt->bind_param("sssi", $nama, $alamat, $telepon, $id);
  $stmt->execute();
  header("Location: ../pages/pelanggan.php");
  exit;
}

// Hapus Pelanggan
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $stmt = $conn->prepare("DELETE FROM pelanggan WHERE id_pelanggan=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: ../pages/pelanggan.php");
  exit;
}
?>
