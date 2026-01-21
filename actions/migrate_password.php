<?php
require_once("../config/koneksi.php"); // sesuaikan path koneksi kamu

// Ambil semua data user dari tabel users
$q = $conn->query("SELECT id_user, password_hash FROM users");

while ($row = $q->fetch_assoc()) {
    $id = $row['id_user'];
    $plain = $row['password_hash'];

    // Cek: kalau password belum di-hash
    // hash PHP (bcrypt) biasanya diawali dengan $2y$
    if (strpos($plain, '$2y$') !== 0) {
        // Buat hash baru dari password plaintext
        $hash = password_hash($plain, PASSWORD_DEFAULT);

        // Simpan hasil hash ke database, ganti password lama
        $update = $conn->prepare("UPDATE users SET password_hash = ? WHERE id_user = ?");
        $update->bind_param("si", $hash, $id);
        $update->execute();

        echo "User ID $id berhasil di-hash<br>";
    } else {
        echo "User ID $id sudah di-hash, dilewati<br>";
    }
}

echo "<br><b>Proses selesai!</b>";
?>
