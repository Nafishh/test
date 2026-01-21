<?php
require_once("../config/koneksi.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  // Cek apakah username sudah ada
  $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $check->bind_param("s", $username);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    header("Location: ../pages/register.php?error=Username sudah digunakan!");
    exit;
  }

  
  // Simpan data user baru
   $stmt = $conn->prepare("INSERT INTO users (username, password_hash, nama_lengkap, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
   $stmt->bind_param("ssss", $username, $password, $nama, $role);


  if ($stmt->execute()) {
    header("Location: ../pages/login.php?success=Registrasi berhasil! Silakan login.");
    exit;
  } else {
    header("Location: ../pages/register.php?error=Terjadi kesalahan saat registrasi!");
    exit;
  }
}
?>
