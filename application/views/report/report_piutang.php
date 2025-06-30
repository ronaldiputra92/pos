<?php
include 'report_header.php';
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 5, 'LAPORAN DATA PIUTANG', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(0, 7, 'Periode :' . $awal . ' s/d ' . $akhir, 0, 1, 'C');

if ($customer == "Semua") {
    $sql = "SELECT b.id_piutang, a.kode_jual, c.nama_cs, SUBSTRING(b.tgl_piutang, 1, 10) AS tanggal, b.jml_piutang, b.bayar, b.sisa, b.status, b.jatuh_tempo FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir' ORDER BY SUBSTRING(b.tgl_piutang, 1, 10) ASC";
    $data = $this->db->query($sql)->result_array();
} else {
    $sql = "SELECT b.id_piutang, a.kode_jual, c.nama_cs, SUBSTRING(b.tgl_piutang, 1, 10) AS tanggal, b.jml_piutang, b.bayar, b.sisa, b.status, b.jatuh_tempo FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir' AND c.id_cs = '$customer' ORDER BY SUBSTRING(b.tgl_piutang, 1, 10) ASC";
    $data = $this->db->query($sql)->result_array();
}


$pdf->Cell(30, 8, '', 0, 1);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(20, 6, 'KODE JUAL', 1, 0, 'C');
$pdf->Cell(35, 6, 'CUSTOMER', 1, 0, 'C');
$pdf->Cell(25, 6, 'TANGGAL', 1, 0, 'C');
$pdf->Cell(20, 6, 'TEMPO', 1, 0, 'C');
$pdf->Cell(25, 6, 'JUMLAH', 1, 0, 'C');
$pdf->Cell(25, 6, 'BAYAR', 1, 0, 'C');
$pdf->Cell(25, 6, 'SISA', 1, 0, 'C');
$pdf->Cell(20, 6, 'STATUS', 1, 1, 'C');
foreach ($data as $d) {
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(20, 6, $d['kode_jual'], 1, 0);
    $pdf->Cell(35, 6, $d['nama_cs'], 1, 0);
    $pdf->Cell(25, 6, $d['tanggal'], 1, 0);
    $pdf->Cell(20, 6, $d['jatuh_tempo'], 1, 0);
    $pdf->Cell(25, 6, 'Rp. ' . number_format($d['jml_piutang'], '0', '.', '.'), 1, 0);
    $pdf->Cell(25, 6, 'Rp. ' . number_format($d['bayar'], '0', '.', '.'), 1, 0);
    $pdf->Cell(25, 6, 'Rp. ' . number_format($d['sisa'], '0', '.', '.'), 1, 0);
    $pdf->Cell(20, 6, $d['status'], 1, 1);
}

if ($customer == "Semua") {

    $query_total = "SELECT SUM(b.jml_piutang) AS total FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir'";
    $query_lunas = "SELECT SUM(b.jml_piutang) AS total FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir' AND b.status = 'Lunas'";
    $query_sisa = "SELECT SUM(b.sisa) AS sisa FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir'";
    $query_bayar = "SELECT SUM(b.bayar) AS bayar FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir'";
} else {

    $query_total = "SELECT SUM(b.jml_piutang) AS total FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir' AND c.id_cs = '$customer'";
    $query_lunas = "SELECT SUM(b.jml_piutang) AS total FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir' AND b.status = 'Lunas' AND c.id_cs = '$customer'";
    $query_sisa = "SELECT SUM(b.sisa) AS sisa FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir' AND c.id_cs = '$customer'";
    $query_bayar = "SELECT SUM(b.bayar) AS bayar FROM penjualan a, piutang b, customer c WHERE b.id_jual  = a.id_jual AND a.id_cs = c.id_cs AND SUBSTRING(b.tgl_piutang, 1, 10) BETWEEN '$awal' AND '$akhir' AND c.id_cs = '$customer'";
}

$total = $this->model->General($query_total)->row_array();
$lunas = $this->model->General($query_lunas)->row_array();
$sisa = $this->model->General($query_sisa)->row_array();
$bayar = $this->model->General($query_bayar)->row_array();

$pdf->Cell(118, 2, '', 0, 1, 'R');
$pdf->Cell(118, 6, '', 0, 0, 'R');
$pdf->SetFont('Times', 'B', 10);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(40, 6, 'Total Piutang', 1, 0, 'L');
$pdf->Cell(37, 6, 'Rp. ' . number_format($total['total'], '0', '.', '.') . ',-', 1, 1, 'L');
$pdf->Cell(118, 6, '', 0, 0, 'R');
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(40, 6, 'Piutang Dibayarkan', 1, 0, 'L');
$pdf->Cell(37, 6, 'Rp. ' . number_format($bayar['bayar'], '0', '.', '.') . ',-', 1, 1, 'L');
$pdf->Cell(118, 6, '', 0, 0, 'R');
$pdf->Cell(40, 6, 'Total Lunas', 1, 0, 'L');
$pdf->Cell(37, 6, 'Rp. ' . number_format($lunas['total'], '0', '.', '.') . ',-', 1, 1, 'L');
$pdf->Cell(118, 6, '', 0, 0, 'R');
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(40, 6, 'Sisa Piutang', 1, 0, 'L');
$pdf->Cell(37, 6, 'Rp. ' . number_format($sisa['sisa'], '0', '.', '.') . ',-', 1, 0, 'L');

$pdf->Output('laporan_piutang.pdf', 'I');
