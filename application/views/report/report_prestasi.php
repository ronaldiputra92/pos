<?php
include 'report_header.php';
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 5, 'LAPORAN DATA INDEX PRESTASI KARYAWAN', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(0, 7, 'Periode :' . $awal . ' s/d ' . $akhir, 0, 1, 'C');

$sql_prestasi = "SELECT a.id_karyawan, c.kode_karyawan, c.nama_karyawan, SUM(a.subtotal) AS total FROM detil_penjualan a, penjualan b, karyawan c WHERE a.id_karyawan = c.id_karyawan AND a.id_jual = b.id_jual AND SUBSTRING(b.tgl, 1, 10) BETWEEN '$awal' AND '$akhir' GROUP BY a.id_karyawan ORDER BY total DESC";

$sql_detail = "SELECT a.id_karyawan, COUNT(a.id_karyawan) AS banyaknya FROM detil_penjualan a, penjualan b
WHERE a.id_jual = b.id_jual AND SUBSTRING(b.tgl, 1, 10) BETWEEN  '$awal' AND '$akhir' AND a.id_karyawan IS NOT NULL GROUP BY a.id_karyawan";

$prestasi = $this->db->query($sql_prestasi)->result_array();
$detail = $this->db->query($sql_detail)->result_array();

$pdf->Cell(30, 8, '', 0, 1);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(25, 6, 'PERINGKAT ', 1, 0, 'C');
$pdf->Cell(35, 6, 'KODE KARYAWAN ', 1, 0, 'C');
$pdf->Cell(50, 6, 'NAMA KARYAWAN ', 1, 0, 'C');
$pdf->Cell(40, 6, 'PEROLEHAN KOMISI', 1, 0, 'C');
$pdf->Cell(40, 6, 'BANYAKNYA SERVIS', 1, 1, 'C');
$i = 1;
foreach ($prestasi as $prestasi) {
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(25, 6, $i++, 1, 0);
    $pdf->Cell(35, 6, $prestasi['kode_karyawan'], 1, 0);
    $pdf->Cell(50, 6, $prestasi['nama_karyawan'], 1, 0);
    $pdf->Cell(40, 6, 'Rp. ' . number_format($prestasi['total'], '0', '.', '.'), 1, 0);
    foreach ($detail as $d) {
        if ($prestasi['id_karyawan'] == $d['id_karyawan']) {

            $pdf->Cell(40, 6, $d['banyaknya'], 1, 1);
        }
    }
}




$pdf->Output('laporan_prestasi.pdf', 'I');
