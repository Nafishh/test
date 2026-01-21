<?php
session_start();
require_once("../config/koneksi.php");

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();


    if (password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id' => $user['id_user'],
            'nama' => $user['nama_lengkap'],
            'role' => $user['role']
        ];
        header("Location: ../pages/dashboard.php");
        exit;
    } else {
        header("Location: ../pages/login.php?error=Password salah");
        exit;
    }
} else {
    header("Location: ../pages/login.php?error=Username tidak ditemukan");
    exit;
}
?>
