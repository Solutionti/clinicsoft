<?php



$totales = $total->result()[0];

$pdf=new FPDF();

$pdf->addpage();

$pdf->Image('public/img/theme/logo.png' , 10 ,9, 25 , 20,'png');

$pdf->SetFont('Times','',13);

$pdf->Ln(1);

$pdf->Cell(65,6,'', '', 0,'L', false );

$pdf->Cell(60,6,'Centro Medico Especializado', '', 0,'L', false );

$pdf->Ln(5);

$pdf->Cell(72,6,'', '', 0,'L', false );

$pdf->Cell(1,6,'SALUD MADRE & MUJER', '', 0,'L', false );

$pdf->Ln(5);

$pdf->Cell(60,6,'', '', 0,'L', false );

$pdf->Cell(7,6,'Av. Grau #478', '', 0,'L', false );

$pdf->Ln(2);

$pdf->Cell(42,5,'', '', 0,'L', false );

$pdf->Cell(60,5,'_____________________________________________', '', 0,'L', false );

$pdf->SetFont('Times','b',13);

$pdf->Ln(10);

$pdf->Cell(70,6,'', '', 0,'L', false );

$pdf->Cell(60,6,'INFORME DE GASTOS', '', 0,'L', false );

$pdf->SetFont('Times','B',9);

$pdf->Ln(14);

$pdf->Cell(60,6,$totales->f_recepcion, 1, 0,'C', false );

$pdf->Cell(134,6,'RESPONSABLE', 1, 0,'L', false );

$pdf->SetFont('Times','',9);

$pdf->Ln(6);

$pdf->Cell(60,6,'2022/01/23', 1, 0,'C', false );

$pdf->Cell(134,6,$this->session->userdata("nombre")." ".$this->session->userdata("apellido"), 1, 0,'L', false );

$pdf->SetFont('Arial', '', 11);

$pdf->Ln(9);

$pdf->Cell(1,6,'', '', 0,'L', false );

$pdf->Cell(70,6,'REPORTE DE GASTOS', '', 0,'L', false );

$pdf->SetFont('Times','B',9);

$pdf->Ln(8);

$pdf->Cell(50,6,'Razon Social', 1, 0,'L', false );

$pdf->Cell(35,6,'Descripcion', 1, 0,'L', false );

$pdf->Cell(20,6,'Fe.Recepcion', 1, 0,'L', false );

$pdf->Cell(35,6,'Colaborador', 1, 0,'L', false );

$pdf->Cell(45,6,'Comprobante', 1, 0,'L', false );

foreach($gasto->result() as $gastos) {

    $pdf->SetFont('Times','',9);

    $pdf->Ln(6);

    $pdf->Cell(50,6,substr($gastos->razon_social,0,25), 1, 0,'L', false );

    $pdf->Cell(35,6,substr($gastos->descripcion,0,35), 1, 0,'L', false );

    $pdf->Cell(20,6,utf8_decode($gastos->f_recepcion), 1, 0,'L', false );

    $pdf->Cell(35,6,utf8_decode(substr($gastos->nombre." ".$gastos->apellido,0,15)), 1, 0,'L', false );

    $pdf->Cell(45,6,strtoupper($gastos->comprobante)."        ".$gastos->monto_v1, 1, 0,'L', false );

    $pdf->SetFont('Times','B',9);

}

$pdf->Ln(11);

$pdf->Cell(140,6,'', 0,'', false );

$pdf->Cell(45,6,'             TOTAL      S/ '.$totales->monto, 1, 0,'', false );

$pdf->SetFont('Times','b',9);

$pdf->Output();



?>

