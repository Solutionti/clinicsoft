<?php
// Obtenemos los datos de la atención
$atenciones = $atencion->result()[0];

// 1. CONFIGURACIÓN DE TICKET (80mm ancho)
$this->load->library("pdf"); 
// Definimos el tamaño para papel térmico (80mm x 200mm aprox)
$pdf = new FPDF('P', 'mm', array(80, 200)); 

$pdf->SetMargins(4, 2, 4); // Márgenes ajustados para ticketera
$pdf->SetAutoPageBreak(true, 5);
$pdf->AddPage();

// --- ENCABEZADO: LOGO AL LADO DEL NOMBRE ---
// Colocamos el logo a la izquierda (Posición original)
$pdf->Image('public/img/theme/logo.png', 27, 5, 25, 18, 'png'); 

// Texto de la clínica a la derecha del logo (Bajamos solo el texto)
$pdf->SetFont('Arial', '', 7); // Establecemos la fuente ANTES de escribir
$pdf->SetXY(20, 25); 
$pdf->SetX(20);
$pdf->MultiCell(0, 3, utf8_decode('Av. Salaverry #1402 - Chiclayo'), 0, 'L');

$pdf->Ln(5);
$pdf->Cell(0, 0, '---------------------------------------------------', 0, 1, 'C');
$pdf->Ln(2);

// --- INFORMACIÓN DE LA ATENCIÓN ---
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 4, 'Fecha:', 0, 0, 'L');
$pdf->Cell(25, 4, date("d/m/Y H:i"), 0, 0, 'L');
$pdf->Cell(10, 4, 'Cajero:', 0, 0, 'L');
$cajero = substr($this->session->userdata("nombre"), 0, 15);
$pdf->Cell(0, 4, utf8_decode($cajero), 0, 1, 'L');

$pdf->Ln(1);

// --- DATOS DEL PACIENTE ---
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 4, 'PACIENTE:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 4, utf8_decode($atenciones->apellido . " " . $atenciones->nombre), 0, 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(8, 4, 'DNI:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(22, 4, $atenciones->documento, 0, 0, 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(12, 4, 'No HC:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 4, $atenciones->hc, 0, 1, 'L');

// --- ORDEN DE ATENCIÓN (Si existe, se resalta) ---
if((($atenciones->orden__)*1) > 0){
    $pdf->Ln(2);
    $pdf->SetFillColor(240, 240, 240); // Gris muy claro
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 8, utf8_decode('ORDEN DE ATENCIÓN:'), 0, 0, 'L', true);
    $pdf->SetFont('Arial', 'B', 16); // Número de orden grande
    $pdf->Cell(0, 8, $atenciones->orden__, 0, 1, 'C', true);
    $pdf->Ln(2);
}

// --- SERVICIOS Y DOCTOR ---
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, 'SERVICIO:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, utf8_decode($atenciones->descripcion), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, 'DOCTOR:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 5, utf8_decode($atenciones->doctor), 0, 'L');

$pdf->Ln(2);
$pdf->Cell(0, 0, '---------------------------------------------------', 0, 1, 'C');
$pdf->Ln(2);

// --- TOTAL ---
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, 8, 'TOTAL', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(0, 8, 'S/. ' . number_format($atenciones->costo, 2), 0, 1, 'R');

$pdf->Ln(2);
$pdf->Cell(0, 0, '---------------------------------------------------', 0, 1, 'C');

// --- PIE DE PÁGINA CON QR / WEB ---
$pdf->Ln(4);
// Colocamos la imagen de la web/QR al lado de la URL
$pdf->Image('public/img/theme/web-misalud.png', 5, $pdf->GetY(), 16, 16, 'png'); 

$pdf->SetX(23);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 4,  utf8_decode('VISÍTENOS EN:'), 0, 1, 'L');
$pdf->SetX(23);
$pdf->SetFont('Arial', 'U', 8);
$pdf->Cell(0, 4, 'clinicamisalud.pe', 0, 1, 'L');
$pdf->SetX(23);
$pdf->SetFont('Arial', 'I', 6.5);
$pdf->MultiCell(0, 3, utf8_decode('Revise sus citas y servicios en nuestra plataforma web.'), 0, 'L');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'I', 7);
$pdf->MultiCell(0, 3, utf8_decode('* Puede canjear este ticket por boleta o factura en caja.'), 0, 'C');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 4, utf8_decode('¡Gracias por su preferencia!'), 0, 1, 'C');
$pdf->Cell(0, 2, '.', 0, 1, 'C'); // Punto de margen para corte

$pdf->Output();


?>



