<?php

session_start();

$message = $_SESSION['message'] ?? '';

if (isset($_SESSION['message'])) {
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi - KasirApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="text-center mb-4">Registrasi Pengguna Baru</h4>

          <!-- Tampilkan message bila ada -->
          <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
          <?php endif; ?>

          <form method="POST" action="../actions/register_process.php">
            <div class="mb-3">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select name="role" class="form-select" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="kasir">Kasir</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
          </form>
          <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
