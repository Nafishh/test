<?php
require_once("../config/koneksi.php");
require_once(__DIR__ . "/../vendor/autoload.php"); // pastikan composer sudah jalan

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil tanggal dari query string
$tanggal_mulai = $_GET['mulai'] ?? date('Y-m-01');
$tanggal_selesai = $_GET['selesai'] ?? date('Y-m-d');

// Ambil data penjualan
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

// Buat isi HTML untuk PDF
$html = '
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #000; }
h2 { text-align: center; margin-bottom: 10px; }
p { text-align: center; margin: 0 0 20px 0; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #888; padding: 6px; text-align: center; }
th { background-color: #f2f2f2; }
tfoot td { font-weight: bold; background-color: #eaeaea; }
</style>

<h2>Laporan Penjualan</h2>
<p>Periode: ' . htmlspecialchars($tanggal_mulai) . ' s/d ' . htmlspecialchars($tanggal_selesai) . '</p>

<table>
  <thead>
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
  <tbody>';

$no = 1;
$total_semua = 0;
while ($row = $result->fetch_assoc()) {
    $total_semua += $row['total_harga'];
    $html .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . date('d-m-Y H:i', strtotime($row['tanggal'])) . '</td>
                <td>' . htmlspecialchars($row['nama_pelanggan'] ?: 'Umum') . '</td>
                <td>' . htmlspecialchars($row['nama_lengkap']) . '</td>
                <td>' . ucfirst($row['metode_pembayaran']) . '</td>
                <td>Rp ' . number_format($row['total_harga'], 0, ',', '.') . '</td>
                <td>' . htmlspecialchars($row['status']) . '</td>
              </tr>';
}

$html .= '</tbody>
<tfoot>
  <tr>
    <td colspan="5" style="text-align:right;">TOTAL PENJUALAN</td>
    <td colspan="2">Rp ' . number_format($total_semua, 0, ',', '.') . '</td>
  </tr>
</tfoot>
</table>';

// Inisialisasi Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output ke browser (langsung download)
$filename = "Laporan_Penjualan_" . date('Ymd_His') . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
exit;
?>
