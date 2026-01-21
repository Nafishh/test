<?php
session_start();
if (isset($_SESSION['user'])) {
  header("Location: dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Aplikasi Kasir</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-lg p-4" style="width: 350px;">
    <h4 class="text-center mb-4 fw-bold">Login Kasir</h4>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger p-2 text-center"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="../actions/login_process.php" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>

    <!-- Tambahkan bagian ini -->
    <div class="text-center mt-3">
      <small>Belum punya akun?</small><br>
      <a href="register.php" class="btn btn-outline-secondary w-100 mt-2">Daftar Akun</a>
    </div>
    <!-- Sampai sini -->
    
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
