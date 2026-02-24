<?php



defined('BASEPATH') OR exit('No direct script access allowed');







class Laboratorio extends Admin_Controller {







    public function __construct() {
		parent::__construct();
		$this->load->model("Doctores_model");
		$this->load->model("Atencion_model");
		$this->load->model("Pagos_model");
		$this->load->model("Laboratorio_model");
	}

    public function index() {
        $laboratorios = $this->Laboratorio_model->getPreciosLaboratorio();
        $doctores = $this->Doctores_model->getDoctores();
        $data = ["doctor" => $doctores, "laboratorio" => $laboratorios];
        $this->load->view("administrador/laboratorio", $data);

    }

    public function CountLaboratorioId() {
		$result = $this->Laboratorio_model->CountLaboratorioId();

		$data = [
			"numero" => $result
		];

		echo  json_encode($data);
	}

    public function precioLaboratorio() {
        $laboratorios = $this->Laboratorio_model->getPreciosLaboratorio();
        $data = [
            "laboratorio" => $laboratorios
        ];
        $this->load->view("administrador/precio_laboratorio", $data);
    }

    public function pdfReciboLaboratorio($id) {
    // 1. OBTENER DATOS
    $laboratorios = $this->Laboratorio_model->getLaboratoriPdf($id);
    $laboratorio = $laboratorios->result()[0];
    $servicios = $this->Laboratorio_model->getLaboratorioServicios($id);

    // 2. GENERAR CLAVE SEGURA
    $this->load->model('Pacientes_model');
    $clave_papel = rand(100000, 999999); 
    $clave_encriptada = password_hash($clave_papel, PASSWORD_BCRYPT);
    $this->Pacientes_model->actualizar_password($laboratorio->dni_paciente, $clave_encriptada);

    // 3. CONFIGURACIÓN TICKET (80mm)
    $this->load->library("pdf"); 
    $pdf = new FPDF('P', 'mm', array(80, 250)); 
    $pdf->SetMargins(4, 2, 4); // Margen superior reducido
    $pdf->SetAutoPageBreak(true, 5);
    $pdf->AddPage();

    // --- ENCABEZADO CON LOGO AL LADO ---
    // Colocamos el logo a la izquierda (X=5)
    $pdf->Image('public/img/theme/logo.png', 5, 5, 14, 14, 'png'); 
    
    // Movemos el texto a la derecha del logo
    $pdf->SetXY(20, 6); 
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 4, utf8_decode('CLÍNICA "MI SALUD"'), 0, 1, 'L');
    $pdf->SetX(20);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Maternidad y Especialidades'), 0, 1, 'L');
    $pdf->SetX(20);
    $pdf->MultiCell(0, 3, utf8_decode('Av. Salaverry #1402 - Chiclayo'), 0, 'L');
    
    $pdf->Ln(5);
    $pdf->Cell(0, 0, '---------------------------------------------------', 0, 1, 'C');
    $pdf->Ln(2);

    // --- INFO TICKET ---
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(12, 4, 'Fecha:', 0, 0, 'L');
    $pdf->Cell(0, 4, date("d/m/Y H:i"), 0, 1, 'L');
    $pdf->Cell(12, 4, 'Cajero:', 0, 0, 'L');
    $cajero = substr($this->session->userdata("nombre"), 0, 18);
    $pdf->Cell(0, 4, utf8_decode($cajero), 0, 1, 'L');

    $pdf->Ln(2);

    // --- DATOS PACIENTE ---
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0, 4, 'PACIENTE:', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->MultiCell(0, 4, utf8_decode($laboratorio->apellido . " " . $laboratorio->nombre), 0, 'L');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(8, 4, 'DNI:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(22, 4, $laboratorio->dni_paciente, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(8, 4, 'Cel:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 4, $laboratorio->telefono, 0, 1, 'L');

    $pdf->Ln(1);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0, 4, 'DOCTOR:', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(0, 3, utf8_decode($laboratorio->doctor), 0, 'L');

    // --- CONTRASEÑA ---
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 5, utf8_decode('SU CONTRASEÑA WEB:'), 0, 1, 'C');
    $pdf->SetFont('Courier', 'B', 15); 
    $pdf->Cell(0, 8, $clave_papel, 1, 1, 'C'); 
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 4, '(Para ver resultados online)', 0, 1, 'C');

    $pdf->Ln(2);
    $pdf->Cell(0, 0, '---------------------------------------------------', 0, 1, 'C');

    // --- EXAMENES ---
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0, 4, 'EXAMENES:', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 7.5); 
    foreach($servicios->result() as $servicio) {
        $pdf->MultiCell(0, 4, utf8_decode('- ' . $servicio->nombre), 0, 'L');
    }

    $pdf->Ln(2);
    $pdf->Cell(0, 0, '---------------------------------------------------', 0, 1, 'C');
    $pdf->Ln(2);

    // --- TOTAL ---
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(20, 6, 'TOTAL', 0, 0, 'L');
    $pdf->Cell(0, 6, 'S/. ' . number_format($laboratorio->total, 2), 0, 1, 'R');

    // --- PIE DE PAGINA CON QR AL LADO ---
    $pdf->Ln(4);
    $pdf->Cell(0, 0, '---------------------------------------------------', 0, 1, 'C');
    $pdf->Ln(3);
    
    // Dibujamos el QR a la izquierda (X=5)
    $pdf->Image('public/img/theme/zonac.png', 5, $pdf->GetY(), 16, 16, 'png'); 
    
    // Texto al lado del QR
    $pdf->SetX(23);
    $pdf->SetFont('Arial', 'B', 7.5);
    $pdf->Cell(0, 4, 'CONSULTE SUS RESULTADOS EN:', 0, 1, 'L');
    $pdf->SetX(23);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 4, 'clinicamisalud.pe/zonac', 0, 1, 'L');
    $pdf->SetX(23);
    $pdf->SetFont('Arial', 'I', 6.5);
    $pdf->MultiCell(0, 3, utf8_decode('Escanee el QR o use su DNI y clave en la web.'), 0, 'L');

    $pdf->Ln(6);
    $pdf->SetFont('Arial', 'I', 7);
    $pdf->MultiCell(0, 3, utf8_decode('Este ticket no tiene valor fiscal. Canjéelo por Boleta/Factura si lo requiere.'), 0, 'C');
    
    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0, 4, utf8_decode('¡Gracias por su visita!'), 0, 1, 'C');
    $pdf->Cell(0, 2, '.', 0, 1, 'C'); 

    $pdf->Output();
}

    public function crearServicioLaboratorio() {

        $dni = $this->input->post("dni");
        $doctor = $this->input->post("doctor");
        $forma_pago = $this->input->post("forma_pago");
        $observacion = $this->input->post("observacion");
        $fecha = $this->input->post("fecha");
        $total = $this->input->post("total");
        $laboratorio = $this->input->post("laboratorio");

        $data = [
            "tipo_deposito" => $forma_pago,
            "dni" => $dni,
            "doctor" => $doctor,
            "observacion" => $observacion,
            "fecha" => $fecha,
            "total" => $total
        ];

        $detallelaboratorio = $this->Laboratorio_model->crearServicioLaboratorio($data);

        for($i=0; $i < sizeof($laboratorio); $i++){
           $data2 = [
               "id_laboratorio" => $detallelaboratorio,
               "servicio" => $laboratorio[$i],
               "fecha" => $fecha
           ];
           $this->Laboratorio_model->crearDetalleLaboratorio($data2);
        }
        $this->Atencion_model->CrearLineaTiempoLaboratorio($dni,'Laboratorio',$doctor);
        echo json_encode($detallelaboratorio);
    }







    public function subirDocumentoLaboratorio() {



        $paciente = $this->input->post("paciente");

		$titulo = $this->input->post("titulo");

        $fecha = date("dmY");

		$dir_subida = 'public/laboratorio/';

        $fichero_subido = $dir_subida.basename($paciente."-".$fecha."-".$_FILES['icono']['name']);



		move_uploaded_file($_FILES['icono']['tmp_name'], $fichero_subido);

			$datos = array(

				"paciente" => $paciente,

				"titulo" => $titulo,

				"icono" => $paciente."-".$fecha."-".$_FILES['icono']['name']

			);

		

		$this->Laboratorio_model->subirDocumentoLaboratorio($datos);



		redirect(base_url("administracion/historia/".$paciente));

    }



    public function createPrecioLaboratorio(){



        $servicio = $this->input->post("servicio");



        $precio = $this->input->post("precio");



        $data = [



         "servicio" => $servicio,



         "precio" => $precio



        ];



        $this->Laboratorio_model->createPrecioLaboratorio($data);



    }







    public function getDataPrecioLaboratorio($id) {



        $precios = $this->Laboratorio_model->getDataPrecioLaboratorio($id);







        echo json_encode($precios);



        



    }



    public function actualizarpreciosLaboratorio() {



        $id = $this->input->post("id");



        $especialidad = $this->input->post("especialidad");



        $precio = $this->input->post("precio");



        $estado = $this->input->post("estado");



        



        $data = [



            "id" => $id,



            "especialidad" => $especialidad,



            "precio" => $precio,



            "estado" => $estado,



        ];



        $this->Laboratorio_model->actualizarpreciosLaboratorio($data);



    }



}