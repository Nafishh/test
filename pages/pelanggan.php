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
  <title>Data Pelanggan - KasirApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard.php">KasirApp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="produk.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link active" href="pelanggan.php">Pelanggan</a></li>
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

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-semibold">Data Pelanggan</h3>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Pelanggan</button>
  </div>

  <table class="table table-striped table-hover align-middle">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>Nama Pelanggan</th>
        <th>Alamat</th>
        <th>Telepon</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $result = $conn->query("SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
      while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td><?= htmlspecialchars($row['telepon']) ?></td>
        <td>
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_pelanggan'] ?>">Edit</button>
          <a href="../actions/pelanggan_process.php?hapus=<?= $row['id_pelanggan'] ?>" onclick="return confirm('Yakin hapus pelanggan ini?')" class="btn btn-danger btn-sm">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="modalEdit<?= $row['id_pelanggan'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="../actions/pelanggan_process.php" method="POST">
              <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">Edit Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id_pelanggan" value="<?= $row['id_pelanggan'] ?>">
                <div class="mb-3">
                  <label>Nama Pelanggan</label>
                  <input type="text" name="nama_pelanggan" value="<?= $row['nama_pelanggan'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Alamat</label>
                  <textarea name="alamat" class="form-control"><?= $row['alamat'] ?></textarea>
                </div>
                <div class="mb-3">
                  <label>Telepon</label>
                  <input type="text" name="telepon" value="<?= $row['telepon'] ?>" class="form-control">
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
      <form action="../actions/pelanggan_process.php" method="POST">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">Tambah Pelanggan</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label>Telepon</label>
            <input type="text" name="telepon" class="form-control">
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
