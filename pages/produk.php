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
  <title>Data Produk - KasirApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard.php">KasirApp</a>
    <div class="d-flex align-items-center">
      <a href="produk.php" class="btn btn-light btn-sm me-2">Produk</a>
      <a href="pelanggan.php" class="btn btn-outline-light btn-sm me-2">Pelanggan</a>
      <a href="transaksi.php" class="btn btn-outline-light btn-sm me-2">Transaksi</a>
      <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-semibold">Data Produk</h3>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Produk</button>
  </div>

  <table class="table table-striped table-hover align-middle">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>Kode Produk</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Satuan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $result = $conn->query("SELECT * FROM produk ORDER BY id_produk DESC");
      while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['kode_produk']) ?></td>
        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
        <td><?= $row['stok'] ?></td>
        <td><?= htmlspecialchars($row['satuan']) ?></td>
        <td>
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_produk'] ?>">Edit</button>
          <a href="../actions/produk_process.php?hapus=<?= $row['id_produk'] ?>" onclick="return confirm('Yakin hapus produk ini?')" class="btn btn-danger btn-sm">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="modalEdit<?= $row['id_produk'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="../actions/produk_process.php" method="POST">
              <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
                <div class="mb-3">
                  <label>Kode Produk</label>
                  <input type="text" name="kode_produk" value="<?= $row['kode_produk'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Nama Produk</label>
                  <input type="text" name="nama_produk" value="<?= $row['nama_produk'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Harga</label>
                  <input type="number" name="harga" value="<?= $row['harga'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Stok</label>
                  <input type="number" name="stok" value="<?= $row['stok'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Satuan</label>
                  <input type="text" name="satuan" value="<?= $row['satuan'] ?>" class="form-control">
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" name="edit" class="btn btn-warning">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../actions/produk_process.php" method="POST">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">Tambah Produk</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Kode Produk</label>
            <input type="text" name="kode_produk" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Satuan</label>
            <input type="text" name="satuan" value="pcs" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
