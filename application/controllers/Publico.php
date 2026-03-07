<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publico extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargamos los modelos necesarios
        $this->load->model("Doctores_model");
        $this->load->model("Citas_model");
    }   

    public function index() {
        $doctores = $this->Doctores_model->getDoctores();
        $data = ["doctor" => $doctores];
        $this->load->view('publico/reservar_cita', $data);
    }

    // =========================================================
    // 1. FUNCIÓN PÚBLICA PARA BUSCAR HORAS (Sin pedir Login)
    // =========================================================
    public function traerhorarios() {
        $fecha = $this->input->post("fecha");
        $medico = $this->input->post("medico");
        
        $CitasIdxFecha = $this->Citas_model->getCitasIdxFecha($fecha,$medico);
        $HorariosDoc   = $this->Citas_model->getHorariosDoc($medico);
        $horario__ = "";
        
        $timestamp = strtotime($fecha);
        $day = date('N', $timestamp);
        
        if ($HorariosDoc->num_rows() > 0) {
            foreach ($HorariosDoc->result() as $row) {
                switch ($day){
                    case 1: $horario__ = $row->Horas_lunes;break;
                    case 2: $horario__ = $row->Horas_martes;break;
                    case 3: $horario__ = $row->Horas_miercoles;break;
                    case 4: $horario__ = $row->Horas_jueves;break;
                    case 5: $horario__ = $row->Horas_viernes;break;
                    case 6: $horario__ = $row->Horas_sabado;break;
                    case 7: $horario__ = $row->Horas_domingo;break;
                }
            }
        }
        // LE AGREGAMOS $fecha AQUÍ
        echo $this->Set_All_Horarios__($horario__, $CitasIdxFecha, $fecha);
    }

    // Funciones matemáticas auxiliares de tu sistema original
   private function Set_All_Horarios__($horario__, $CitasIdxFecha, $fecha_elegida = ""){
        $horario__ = str_replace(" ", "", $horario__);
        if($horario__!=""){
            $horarios_mostrar = array();
            $horas__ = explode(";", $horario__);
            $duracion__ = $horas__[0];
            array_splice($horas__,0,1);
            
            for($i=0; $i < sizeof($horas__);$i++){
                $hor__as = explode("-", $horas__[$i]);
                if(sizeof($hor__as)>1){
                    $hor__min_1 = explode(":", $hor__as[0]);
                    $hor__min_2 = explode(":", $hor__as[1]);
                    $hor__1 = ($hor__min_1[0]*1);   $min__1 = 0;
                    if(sizeof($hor__min_1)>1){  $min__1 = ($hor__min_1[1]*1);}
                    $hor__2 = ($hor__min_2[0]*1);   
                    $min__2 = 0;

                    if(sizeof($hor__min_2)>1){  $min__2 = ($hor__min_2[1]*1);}
                    $hor________min_1 = $this->ceros($hor__1*1,2).":".$this->ceros($min__1*1,2).":00";
                    $hor________min_1 = strtotime($hor________min_1);
                    array_push($horarios_mostrar,array("hora" =>$hor________min_1));
                    $hor________min_1 = strtotime('.'.$duracion__.' minute',$hor________min_1);
                    array_push($horarios_mostrar,array("hora" =>$hor________min_1));
                    $hor________min_2 = $this->ceros($hor__2*1,2).":".$this->ceros($min__2*1,2).":00";
                    $hor________min_2 = strtotime($hor________min_2);
                    for($iaxx=0; $iaxx < 50;$iaxx++){
                        if($hor________min_1>=$hor________min_2){
                            $iaxx = 50;
                        }else{
                            $hor________min_1 = strtotime('.'.$duracion__.' minute',$hor________min_1);
                            array_push($horarios_mostrar,array("hora" =>$hor________min_1));
                        }
                    }
                    $horarios_mostrar_new = array();
                    for($iaa=0; $iaa < sizeof($horarios_mostrar);$iaa++){
                        $horarios_mostrar_new[$iaa]['hora'] = date('H:i',$horarios_mostrar[$iaa]['hora']);
                    }
                }
            }

            if ($CitasIdxFecha->num_rows() > 0){
                foreach ($CitasIdxFecha->result() as $row){
                    $horarios_mostrar_new = $this->eliminar__($horarios_mostrar_new,$row->hora);
                }
            }

            // =========================================================
            // EL FILTRO INTELIGENTE PARA LA WEB (OCULTA HORAS PASADAS)
            // =========================================================
            date_default_timezone_set('America/Lima');
            $fecha_hoy = date('Y-m-d');
            $hora_actual = date('H:i'); 

            if ($fecha_elegida === $fecha_hoy) {
                $horarios_filtrados = array();
                for($i=0; $i < sizeof($horarios_mostrar_new); $i++){
                    if ($horarios_mostrar_new[$i]['hora'] > $hora_actual) {
                        array_push($horarios_filtrados, $horarios_mostrar_new[$i]);
                    }
                }
                $horarios_mostrar_new = $horarios_filtrados;
            }
            // =========================================================

            // CORRECCIÓN DE BUG: Validar la lista limpia, no la sucia
            if(sizeof($horarios_mostrar_new) > 0){
                echo json_encode(["horarios_mostrar" => $horarios_mostrar_new, "sms" => "Horarios Disponibles", "acction" => 1]);
            }else{
                echo json_encode(["horarios_mostrar" => array(), "sms" => "No quedan turnos para hoy", "acction" => 2]);
            }
        }else{
            echo json_encode(["horarios_mostrar" => array(), "sms" => "Dia No Disponible", "acction" => 2]);
        }
    }

    private function eliminar__($arrr,$hora__){
        for($iaa=0; $iaa < sizeof($arrr);$iaa++){
            if($arrr[$iaa]['hora'] == $hora__){
                array_splice($arrr,$iaa,1);
                $this->eliminar__($arrr,$hora__);
            }
        }
        return $arrr;
    } 

    private function ceros($valor, $longitud){
        return str_pad($valor, $longitud, '0', STR_PAD_LEFT);
    } 

    // =========================================================
    // 2. FUNCIÓN PÚBLICA PARA CREAR LA CITA
    // =========================================================
    public function crearcita() {
        $nombre = $this->input->post("nombre");
        $telefono = $this->input->post("telefono");
        $fecha = $this->input->post("fecha");
        $hora = $this->input->post("hora");
        $id_medico = $this->input->post("medico");
        $servicio = $this->input->post("observaciones"); // Aquí viaja el servicio

        $datos = [
            "dni" => $this->input->post("dni"),
            "nombre" => $nombre,
            "telefono" => $telefono,
            "medico" => $id_medico,
            "fecha" => $fecha,
            "hora" => $hora,
            "estado" => $this->input->post("estado"),
            "observaciones" => $servicio,
            "triage" => "No" // No hay triage desde la web
        ];
        
        // 1. Guardamos la cita en MySQL
        $this->Citas_model->crearCita($datos);

        // ==========================================
        // 2. BUSCAR Y LIMPIAR EL NOMBRE DEL DOCTOR
        // ==========================================
        $nombre_doctor = "su especialista"; 
        
        $query_doc = $this->db->select('nombre, apellido')
                              ->where('codigo_doctor', $id_medico)
                              ->get('doctores'); 
                              
        if($query_doc->num_rows() > 0) {
            $doc = $query_doc->row();
            
            // Extraemos SOLO el primer nombre y primer apellido
            $primer_nombre = explode(' ', trim($doc->nombre))[0];
            $primer_apellido = isset($doc->apellido) ? explode(' ', trim($doc->apellido))[0] : "";
            
            // Convertimos a Formato Título (Juan Malambo)
            $primer_nombre = ucwords(strtolower($primer_nombre));
            $primer_apellido = ucwords(strtolower($primer_apellido));
            
            $nombre_doctor = trim($primer_nombre . " " . $primer_apellido);
        }

        // ==========================================
        // 3. DARLE ELEGANCIA A LA FECHA, HORA Y PACIENTE
        // ==========================================
        $fecha_bonita = date("d/m/Y", strtotime($fecha)); // Queda: 03/03/2026
        $hora_bonita = date("h:i A", strtotime($hora));   // Queda: 02:15 PM
        
        // Limpiamos el nombre del paciente (Extraemos solo las dos primeras palabras)
        $partes_nombre = explode(' ', trim($nombre));
        $nombre_paciente_limpio = $partes_nombre[0]; // Primer nombre
        
        // Si escribió al menos una segunda palabra (apellido), la agregamos
        if (isset($partes_nombre[1])) {
            $nombre_paciente_limpio .= " " . $partes_nombre[1]; 
        }
        
        // Pasamos a Formato Título (Ej: CESAR ANTHONY -> Cesar Anthony)
        $nombre_paciente_limpio = ucwords(strtolower($nombre_paciente_limpio));
        // ==========================================
        // 4. ENVIAR WHATSAPP (Mensaje Premium)
        // ==========================================
        try {
            $this->load->helper('whatsapp');
            
            $mensaje = "*Clínica Mi Salud*\n\n";
            $mensaje .= "Hola *$nombre_paciente_limpio*, su cita ha sido reservada con éxito desde la Web.\n\n";
            $mensaje .= "👩‍⚕️ *Especialista:* Dr(a). $nombre_doctor\n";
            $mensaje .= "📅 *Fecha:* $fecha_bonita\n";
            $mensaje .= "⏰ *Hora:* $hora_bonita\n\n";
            $mensaje .= "📍 ¡La esperamos!";
    
            enviar_whatsapp_cita($telefono, $mensaje);
        } catch (Exception $e) {
            log_message('error', 'Error enviando whatsapp: ' . $e->getMessage());
        }
    }
    }