<?php
session_start();
require_once("../config/koneksi.php");

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Kasir App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard.php">KasirApp</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="produk.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="pelanggan.php">Pelanggan</a></li>
        <li class="nav-item"><a class="nav-link" href="transaksi.php">Transaksi</a></li>
        <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
      </ul>

      <div class="d-flex align-items-center">
        <span class="text-white me-3">Halo, <?= htmlspecialchars($user['nama']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
        <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      </div>
    </div>
  </div>
</nav>

<!-- DASHBOARD CONTENT -->
<div class="container py-5">
  <h2 class="mb-4">Dashboard</h2>

  <div class="row g-4">
    <!-- Total Produk -->
    <div class="col-md-3">
      <div class="card text-center shadow-sm border-0 p-3">
        <h5 class="fw-semibold">Total Produk</h5>
        <h3 class="text-primary fw-bold">
          <?php
          $result = $conn->query("SELECT COUNT(*) AS total FROM produk");
          $data = $result->fetch_assoc();
          echo $data['total'];
          ?>
        </h3>
      </div>
    </div>

    <!-- Total Pelanggan -->
    <div class="col-md-3">
      <div class="card text-center shadow-sm border-0 p-3">
        <h5 class="fw-semibold">Total Pelanggan</h5>
        <h3 class="text-success fw-bold">
          <?php
          $result = $conn->query("SELECT COUNT(*) AS total FROM pelanggan");
          $data = $result->fetch_assoc();
          echo $data['total'];
          ?>
        </h3>
      </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col-md-3">
      <div class="card text-center shadow-sm border-0 p-3">
        <h5 class="fw-semibold">Total Transaksi</h5>
        <h3 class="text-warning fw-bold">
          <?php
          $result = $conn->query("SELECT COUNT(*) AS total FROM penjualan");
          $data = $result->fetch_assoc();
          echo $data['total'];
          ?>
        </h3>
      </div>
    </div>

    <!-- Penjualan Hari Ini -->
    <div class="col-md-3">
      <div class="card text-center shadow-sm border-0 p-3">
        <h5 class="fw-semibold">Penjualan Hari Ini</h5>
        <h3 class="text-danger fw-bold">
          <?php
          $result = $conn->query("SELECT IFNULL(SUM(total_harga),0) AS total FROM penjualan WHERE DATE(tanggal)=CURDATE()");
          $data = $result->fetch_assoc();
          echo "Rp " . number_format($data['total'], 0, ',', '.');
          ?>
        </h3>
      </div>
    </div>
  </div>

  <div class="mt-5 text-center">
    <p class="text-muted">
      Selamat datang di sistem kasir sederhana.  
      Gunakan menu navigasi di atas untuk mengelola data produk, pelanggan, transaksi, dan laporan.
    </p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
