<?php
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 18);
$profil = $this->db->get('profil_perusahaan')->row_array();
$pdf->Image('./assets/img/profil/' . $profil['logo_toko'], 70, 5, 27, 24);
$pdf->Cell(25);
$pdf->SetFont('Times', 'B', 17);
$pdf->Cell(0, 5, $profil['nama_toko'], 0, 1, 'C');
$pdf->Cell(25);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(0, 5, 'Website :' . $profil['website_toko'] . '/ E-Mail : ' . $profil['email_toko'], 0, 1, 'C');
$pdf->Cell(25);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(0, 5, $profil['alamat_toko'] . ' Telp. / Fax : ' . $profil['telp_toko'] . ' / ' . $profil['fax_toko'], 0, 1, 'C');

$pdf->SetLineWidth(1);
$pdf->Line(10, 36, 285, 36);
$pdf->SetLineWidth(0);
$pdf->Line(10, 37, 285, 37);
$pdf->Cell(30, 17, '', 0, 1);

$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 5, 'LAPORAN DATA KAS BANK', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(0, 7, 'Periode :' . $awal . ' s/d ' . $akhir, 0, 1, 'C');
$sql = "SELECT SUBSTRING(a.tanggal, 1, 10) AS tgl, a.jenis, a.nominal, a.keterangan, b.nama_lengkap FROM bank a, user b WHERE a.id_user = b.id_user AND SUBSTRING(a.tanggal, 1, 10) BETWEEN '$awal' AND '$akhir'";
$data = $this->db->query($sql)->result_array();

$pdf->Cell(30, 8, '', 0, 1);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(40, 6, 'TANGGAL', 1, 0, 'C');
$pdf->Cell(35, 6, 'USER', 1, 0, 'C');
$pdf->Cell(35, 6, 'JENIS', 1, 0, 'C');
$pdf->Cell(35, 6, 'NOMINAL (Rp)', 1, 0, 'C');
$pdf->Cell(135, 6, 'KETERANGAN', 1, 1, 'C');
foreach ($data as $d) {
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(40, 6, $d['tgl'], 1, 0);
    $pdf->Cell(35, 6, $d['nama_lengkap'], 1, 0);
    $pdf->Cell(35, 6, $d['jenis'], 1, 0);
    $pdf->Cell(35, 6, 'Rp. ' . number_format($d['nominal'], '0', '.', '.'), 1, 0);
    $pdf->Cell(135, 6, $d['keterangan'], 1, 1);
}
$sqlmasuk = "SELECT SUM(nominal) AS nominal FROM bank WHERE jenis = 'Pemasukan'  AND SUBSTRING(tanggal, 1, 10) BETWEEN '$awal' AND '$akhir'";
$sqlkeluar = "SELECT SUM(nominal) AS nominal FROM bank WHERE jenis = 'Pengeluaran'  AND SUBSTRING(tanggal, 1, 10) BETWEEN '$awal' AND '$akhir'";
$sql_mutasi = "SELECT SUM(nominal) AS nominal FROM bank WHERE jenis = 'Mutasi Ke Kas'  AND SUBSTRING(tanggal, 1, 10) BETWEEN '$awal' AND '$akhir'";

$pemasukan = implode($this->model->General($sqlmasuk)->row_array());
$pengeluaran = implode($this->model->General($sqlkeluar)->row_array());
$mutasi = implode($this->model->General($sql_mutasi)->row_array());
$sisa = (int)$pemasukan - (int)$pengeluaran - (int)$mutasi;

$pdf->Cell(203, 2, '', 0, 1, 'R');
$pdf->Cell(203, 6, '', 0, 0, 'R');
$pdf->SetFont('Times', 'B', 10);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(40, 6, 'Total Pemasukan', 1, 0, 'L');
$pdf->Cell(37, 6, 'Rp. ' . number_format((int)$pemasukan, '0', '.', '.') . ',-', 1, 1, 'L');
$pdf->Cell(203, 6, '', 0, 0, 'R');
$pdf->Cell(40, 6, 'Total Pengeluaran', 1, 0, 'L');
$pdf->Cell(37, 6, 'Rp. ' . number_format((int)$pengeluaran, '0', '.', '.') . ',-', 1, 1, 'L');
$pdf->Cell(203, 6, '', 0, 0, 'R');
$pdf->Cell(40, 6, 'Total Mutasi', 1, 0, 'L');
$pdf->Cell(37, 6, 'Rp. ' . number_format((int)$mutasi, '0', '.', '.') . ',-', 1, 1, 'L');
$pdf->Cell(203, 6, '', 0, 0, 'R');
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(40, 6, 'Sisa Saldo', 1, 0, 'L');
$pdf->Cell(37, 6, 'Rp. ' . number_format((int)$sisa, '0', '.', '.') . ',-', 1, 0, 'L');

$pdf->Output('laporan_kas_bank.pdf', 'I');
