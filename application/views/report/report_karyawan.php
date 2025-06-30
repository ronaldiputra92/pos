<?php
include 'report_header.php';
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 5, 'LAPORAN DATA KARYAWAN', 0, 1, 'C');
$pdf->Cell(30, 8, '', 0, 1);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(7, 6, 'NO', 1, 0, 'C');
$pdf->Cell(37, 6, 'NAMA KARYAWAN', 1, 0, 'C');
$pdf->Cell(37, 6, 'ALAMAT', 1, 0, 'C');
$pdf->Cell(30, 6, 'TELP', 1, 0, 'C');
$pdf->Cell(37, 6, 'EMAIL', 1, 0, 'C');
$pdf->Cell(20, 6, 'STATUS', 1, 0, 'C');
$pdf->Cell(25, 6, 'TGL MASUK', 1, 1, 'C');
$i = 1;
foreach ($data as $d) {
        $pdf->SetFont('Times', '', 9);
        $pdf->Cell(7, 6, $i++, 1, 0);
        $pdf->Cell(37, 6, $d['nama_karyawan'], 1, 0);
        $pdf->Cell(37, 6, $d['alamat'], 1, 0);
        $pdf->Cell(30, 6, $d['telp_karyawan'], 1, 0);
        $pdf->Cell(37, 6, $d['email_karyawan'], 1, 0);
        $pdf->Cell(20, 6, $d['status_karyawan'], 1, 0);
        $pdf->Cell(25, 6, $d['tgl_masuk'], 1, 1);
}

$pdf->SetFont('Times', '', 10);

$pdf->Output('laporan_karyawan.pdf', 'I');
