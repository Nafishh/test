<?php
session_start();
require_once("../config/koneksi.php");

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

$user = $_SESSION['user'];

// ambil tanggal filter (kalau ada)
$tanggal_mulai = $_GET['mulai'] ?? date('Y-m-01');
$tanggal_selesai = $_GET['selesai'] ?? date('Y-m-d');

// ambil data penjualan
$query = "SELECT p.*, pl.nama_pelanggan, u.nama_lengkap 
          FROM penjualan p
          LEFT JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
          LEFT JOIN users u ON p.id_user = u.id_user
          WHERE DATE(p.tanggal) BETWEEN ? AND ?
          ORDER BY p.tanggal DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $tanggal_mulai, $tanggal_selesai);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Penjualan - KasirApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard.php">KasirApp</a>
    <div class="d-flex align-items-center">
      <span class="text-white me-3">Halo, <?= htmlspecialchars($user['nama']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
      <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h3 class="fw-semibold mb-4">Laporan Penjualan</h3>

  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
      <label>Dari Tanggal</label>
      <input type="date" name="mulai" value="<?= $tanggal_mulai ?>" class="form-control">
    </div>
    <div class="col-md-4">
      <label>Sampai Tanggal</label>
      <input type="date" name="selesai" value="<?= $tanggal_selesai ?>" class="form-control">
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
    </div>
  </form>

  <table class="table table-striped table-bordered align-middle">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Kasir</th>
        <th>Metode</th>
        <th>Total</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $total = 0;
      while ($row = $result->fetch_assoc()):
        $total += $row['total_harga'];
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
        <td><?= $row['nama_pelanggan'] ?: 'Umum' ?></td>
        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
        <td><?= ucfirst($row['metode_pembayaran']) ?></td>
        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
        <td><span class="badge bg-success"><?= htmlspecialchars($row['status']) ?></span></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  
<div class="text-end mb-3">
  <a href="../actions/laporan_pdf.php?mulai=<?= $tanggal_mulai ?>&selesai=<?= $tanggal_selesai ?>" class="btn btn-danger">
    Download PDF
  </a>
</div>

  <div class="text-end mt-3">
    <h5>Total Penjualan: <span class="text-primary fw-bold">Rp <?= number_format($total, 0, ',', '.') ?></span></h5>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
