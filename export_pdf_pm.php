<?php
require('fpdf186/fpdf.php');
require('phpqrcode/qrlib.php');

$file_name = isset($_GET['file_name']) ? $_GET['file_name'] : 'data_pm';
$file_name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $file_name);

$conn = new mysqli('localhost', 'root', '', 'data_pc');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$qrDir = 'qrcodes/';
if (!file_exists($qrDir)) {
    mkdir($qrDir, 0777, true);
}

$sql = "SELECT pm.*, pc.nama_pc, pc.lokasi_pc FROM pm_tel pm JOIN pc_tel pc ON pm.pc_id = pc.id";
$result = $conn->query($sql);

class PDF extends FPDF
{
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        $str_width=$this->GetStringWidth($txt);
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                $horiz_scale=$ratio*100.0;
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                $char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1,1)*$this->k;
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            $align='';
        }

        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

        if ($fit)
            $this->_out('BT /F1 '.($scale ? '100 ' : '0 ').'Tz ET');
    }

    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Data Preventive Maintenance (PM)', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 9);

$widths = [
    'id' => 15,
    'nama_pc' => 30,
    'kondisi_sebelum' => 40,
    'tanggal_sebelum' => 35,
    'kondisi_setelah' => 40,
    'tanggal_setelah' => 35,
    'qr_code' => 25
];

$pdf->SetFillColor(220, 220, 220);
$pdf->CellFitSpace($widths['id'], 10, 'ID', 1, 0, 'C', true);
$pdf->CellFitSpace($widths['nama_pc'], 10, 'Nama PC', 1, 0, 'C', true);
$pdf->CellFitSpace($widths['kondisi_sebelum'], 10, 'Kondisi Sebelum', 1, 0, 'C', true);
$pdf->CellFitSpace($widths['tanggal_sebelum'], 10, 'Tanggal Sebelum', 1, 0, 'C', true);
$pdf->CellFitSpace($widths['kondisi_setelah'], 10, 'Kondisi Setelah', 1, 0, 'C', true);
$pdf->CellFitSpace($widths['tanggal_setelah'], 10, 'Tanggal Setelah', 1, 0, 'C', true);
$pdf->CellFitSpace($widths['qr_code'], 10, 'QR Code', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 8);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $start_y = $pdf->GetY();
        $pdf->CellFitSpace($widths['id'], 10, $row['id'], 1);
        $pdf->CellFitSpace($widths['nama_pc'], 10, $row['nama_pc'], 1);
        $pdf->CellFitSpace($widths['kondisi_sebelum'], 10, $row['kondisi_sebelum'], 1);
        $pdf->CellFitSpace($widths['tanggal_sebelum'], 10, $row['tanggal_sebelum'], 1);
        $pdf->CellFitSpace($widths['kondisi_setelah'], 10, $row['kondisi_setelah'], 1);
        $pdf->CellFitSpace($widths['tanggal_setelah'], 10, $row['tanggal_setelah'], 1);
        
        $qrData = $row['nama_pc'] . '|' . $row['kondisi_setelah'] . '|' . $row['lokasi_pc'] . '|' . $row['tanggal_setelah'];
        $filePath = $qrDir . 'qr_' . $row['id'] . '.png';
        QRcode::png($qrData, $filePath, QR_ECLEVEL_L, 3);
        
        $pdf->Cell($widths['qr_code'], 10, $pdf->Image($filePath, $pdf->GetX()+3, $pdf->GetY()+1, 8, 8), 1, 0, 'C');
        $pdf->Ln();
        
        unlink($filePath);
        
        if ($pdf->GetY() > 180) {
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(220, 220, 220);
            $pdf->CellFitSpace($widths['id'], 10, 'ID', 1, 0, 'C', true);
            $pdf->CellFitSpace($widths['nama_pc'], 10, 'Nama PC', 1, 0, 'C', true);
            $pdf->CellFitSpace($widths['kondisi_sebelum'], 10, 'Kondisi Sebelum', 1, 0, 'C', true);
            $pdf->CellFitSpace($widths['tanggal_sebelum'], 10, 'Tanggal Sebelum', 1, 0, 'C', true);
            $pdf->CellFitSpace($widths['kondisi_setelah'], 10, 'Kondisi Setelah', 1, 0, 'C', true);
            $pdf->CellFitSpace($widths['tanggal_setelah'], 10, 'Tanggal Setelah', 1, 0, 'C', true);
            $pdf->CellFitSpace($widths['qr_code'], 10, 'QR Code', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 8);
        }
    }
} else {
    $pdf->Cell(array_sum($widths), 10, 'Tidak ada data PM ditemukan.', 1, 1, 'C');
}

$pdf->Output('D', $file_name.'.pdf');

$conn->close();
?>