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
$pdf->Cell(0, 5, 'LAPORAN DATA STOK OPNAME', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(0, 7, 'Periode : ' . $awal . ' s/d ' . $akhir, 0, 1, 'C');
$sql = "SELECT a.id_stok_opname, b.barcode, b.nama_barang, a.stok, a.stok_nyata, a.selisih, a.keterangan, 
 a.nilai FROM stok_opname a, barang b WHERE a.id_barang = b.id_barang AND SUBSTRING(a.tanggal, 1, 10) BETWEEN '$awal' AND '$akhir'";
$data = $this->model->General($sql)->result_array();
$pdf->Cell(30, 8, '', 0, 1);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(7, 6, 'NO', 1, 0, 'C');
$pdf->Cell(25, 6, 'BARCODE', 1, 0, 'C');
$pdf->Cell(68, 6, 'NAMA ITEM', 1, 0, 'C');
$pdf->Cell(15, 6, 'STOK', 1, 0, 'C');
$pdf->Cell(26, 6, 'STOK NYATA', 1, 0, 'C');
$pdf->Cell(15, 6, 'SELISIH', 1, 0, 'C');
$pdf->Cell(25, 6, 'NILAI (Rp)', 1, 0, 'C');
$pdf->Cell(95, 6, 'KETERANGAN', 1, 1, 'C');
$i = 1;
foreach ($data as $d) {
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(7, 6, $i++, 1, 0);
    $pdf->Cell(25, 6, $d['barcode'], 1, 0);
    $pdf->Cell(68, 6, $d['nama_barang'], 1, 0);
    $pdf->Cell(15, 6, $d['stok'], 1, 0);
    $pdf->Cell(26, 6, $d['stok_nyata'], 1, 0);
    $pdf->Cell(15, 6, $d['selisih'], 1, 0);
    $pdf->Cell(25, 6, 'Rp. ' . number_format($d['nilai'], '0', '.', '.'), 1, 0);
    $pdf->Cell(95, 6, $d['keterangan'], 1, 1);
}

$pdf->SetFont('Times', '', 10);

$pdf->Output('laporan_stokOpname.pdf', 'I');
