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
  <title>Transaksi - KasirApp</title>
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
        <li class="nav-item"><a class="nav-link" href="pelanggan.php">Pelanggan</a></li>
        <li class="nav-item"><a class="nav-link active" href="transaksi.php">Transaksi</a></li>
        <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
      </ul>
      <div class="d-flex align-items-center">
        <span class="text-white me-3">Halo, <?= htmlspecialchars($user['nama']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
        <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      </div>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h3 class="fw-semibold mb-4">Transaksi Penjualan</h3>

  <form action="../actions/transaksi_process.php" method="POST">
    <div class="row g-3">
      <div class="col-md-4">
        <label>Pelanggan</label>
        <select name="id_pelanggan" class="form-select">
          <option value="">Umum</option>
          <?php
          $pel = $conn->query("SELECT * FROM pelanggan ORDER BY nama_pelanggan");
          while ($p = $pel->fetch_assoc()):
          ?>
            <option value="<?= $p['id_pelanggan'] ?>"><?= htmlspecialchars($p['nama_pelanggan']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label>Metode Pembayaran</label>
        <select name="metode_pembayaran" class="form-select" required>
          <option value="tunai">Tunai</option>
          <option value="kartu">Kartu</option>
          <option value="transfer">Transfer</option>
          <option value="qris">QRIS</option>
        </select>
      </div>
      <div class="col-md-4">
        <label>Tanggal</label>
        <input type="text" class="form-control" value="<?= date('d-m-Y H:i') ?>" readonly>
      </div>
    </div>

    <hr class="my-4">

    <h5 class="mb-3">Tambah Produk</h5>
    <div class="row g-2 align-items-end">
      <div class="col-md-5">
        <label>Produk</label>
        <select name="id_produk" id="id_produk" class="form-select" required>
          <option value="">-- Pilih Produk --</option>
          <?php
          $prod = $conn->query("SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk");
          while ($r = $prod->fetch_assoc()):
          ?>
            <option value="<?= $r['id_produk'] ?>" data-harga="<?= $r['harga'] ?>">
              <?= htmlspecialchars($r['nama_produk']) ?> (Stok: <?= $r['stok'] ?>)
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label>Jumlah</label>
        <input type="number" name="jumlah" id="jumlah" min="1" value="1" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label>Harga</label>
        <input type="text" id="harga" class="form-control" readonly>
      </div>
      <div class="col-md-2 d-grid">
        <button type="button" class="btn btn-success" id="btnTambah">Tambah</button>
      </div>
    </div>

    <table class="table table-bordered table-striped mt-4" id="tabelKeranjang">
      <thead class="table-secondary">
        <tr>
          <th>Produk</th>
          <th>Jumlah</th>
          <th>Harga</th>
          <th>Subtotal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <div class="text-end">
      <h4>Total: <span id="totalHarga">Rp 0</span></h4>
      <input type="hidden" name="total_harga" id="inputTotal">
      <button type="submit" name="checkout" class="btn btn-primary mt-3 px-5">Simpan Transaksi</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let keranjang = [];
const tabelBody = document.querySelector("#tabelKeranjang tbody");
const totalHarga = document.getElementById("totalHarga");
const inputTotal = document.getElementById("inputTotal");

document.getElementById("btnTambah").addEventListener("click", () => {
  const select = document.getElementById("id_produk");
  const id = select.value;
  const nama = select.options[select.selectedIndex].text;
  const harga = parseFloat(select.options[select.selectedIndex].dataset.harga);
  const jumlah = parseInt(document.getElementById("jumlah").value);

  if (!id) return alert("Pilih produk dulu");
  const subtotal = harga * jumlah;

  keranjang.push({ id, nama, harga, jumlah, subtotal });
  renderTabel();
});

function renderTabel() {
  tabelBody.innerHTML = "";
  let total = 0;

  keranjang.forEach((item, index) => {
    total += item.subtotal;
    tabelBody.innerHTML += `
      <tr>
        <td>${item.nama}</td>
        <td>${item.jumlah}</td>
        <td>Rp ${item.harga.toLocaleString()}</td>
        <td>Rp ${item.subtotal.toLocaleString()}</td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusItem(${index})">X</button></td>
      </tr>`;
  });

  totalHarga.innerText = "Rp " + total.toLocaleString();
  inputTotal.value = total;
}

function hapusItem(i) {
  keranjang.splice(i, 1);
  renderTabel();
}

// sebelum submit, kirim semua item keranjang
document.querySelector("form").addEventListener("submit", (e) => {
  if (keranjang.length === 0) {
    e.preventDefault();
    alert("Keranjang masih kosong!");
    return;
  }

  const hiddenInput = document.createElement("input");
  hiddenInput.type = "hidden";
  hiddenInput.name = "keranjang";
  hiddenInput.value = JSON.stringify(keranjang);
  e.target.appendChild(hiddenInput);
});
</script>
</body>
</html>
