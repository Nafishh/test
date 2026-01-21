<?php
session_start();
require_once("../config/koneksi.php");

if (!isset($_POST['checkout'])) {
  header("Location: ../pages/transaksi.php");
  exit;
}

$user_id = $_SESSION['user']['id'];
$id_pelanggan = $_POST['id_pelanggan'] ?: NULL;
$metode = $_POST['metode_pembayaran'];
$total = $_POST['total_harga'];
$keranjang = json_decode($_POST['keranjang'], true);

$conn->begin_transaction();

try {
  // insert ke penjualan
  $stmt = $conn->prepare("INSERT INTO penjualan (tanggal, id_user, id_pelanggan, total_harga, metode_pembayaran, status) VALUES (NOW(), ?, ?, ?, ?, 'paid')");
  $stmt->bind_param("iids", $user_id, $id_pelanggan, $total, $metode);
  $stmt->execute();
  $id_penjualan = $conn->insert_id;

  // detail_penjualan
  $stmtDetail = $conn->prepare("INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah, harga_satuan) VALUES (?, ?, ?, ?)");
  foreach ($keranjang as $item) {
    $stmtDetail->bind_param("iiid", $id_penjualan, $item['id'], $item['jumlah'], $item['harga']);
    $stmtDetail->execute();

    // kurangi stok produk
    $update = $conn->prepare("UPDATE produk SET stok = stok - ? WHERE id_produk = ?");
    $update->bind_param("ii", $item['jumlah'], $item['id']);
    $update->execute();
  }

  $conn->commit();
  header("Location: ../pages/transaksi.php?success=1");
} catch (Exception $e) {
  $conn->rollback();
  echo "Transaksi gagal: " . $e->getMessage();
}
?>
