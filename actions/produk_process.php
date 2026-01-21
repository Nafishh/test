<?php
require_once("../config/koneksi.php");

// Tambah Produk
if (isset($_POST['tambah'])) {
  $kode = $_POST['kode_produk'];
  $nama = $_POST['nama_produk'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];
  $satuan = $_POST['satuan'];

  $stmt = $conn->prepare("INSERT INTO produk (kode_produk, nama_produk, harga, stok, satuan) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("ssdis", $kode, $nama, $harga, $stok, $satuan);
  $stmt->execute();
  header("Location: ../pages/produk.php");
  exit;
}

// Edit Produk
if (isset($_POST['edit'])) {
  $id = $_POST['id_produk'];
  $kode = $_POST['kode_produk'];
  $nama = $_POST['nama_produk'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];
  $satuan = $_POST['satuan'];

  $stmt = $conn->prepare("UPDATE produk SET kode_produk=?, nama_produk=?, harga=?, stok=?, satuan=? WHERE id_produk=?");
  $stmt->bind_param("ssdisi", $kode, $nama, $harga, $stok, $satuan, $id);
  $stmt->execute();
  header("Location: ../pages/produk.php");
  exit;
}

// Hapus Produk
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $stmt = $conn->prepare("DELETE FROM produk WHERE id_produk=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: ../pages/produk.php");
  exit;
}
?>
