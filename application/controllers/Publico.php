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
        echo $this->Set_All_Horarios__($horario__,$CitasIdxFecha);
    }

    // Funciones matemáticas auxiliares de tu sistema original
    private function Set_All_Horarios__($horario__,$CitasIdxFecha){
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
            if(sizeof($horarios_mostrar)>0){
                echo json_encode(["horarios_mostrar" => $horarios_mostrar_new, "sms" => "Horarios Disponibles", "acction" => 1]);
            }else{
                echo json_encode(["horarios_mostrar" => $horarios_mostrar_new, "sms" => "Dia No Disponible", "acction" => 2]);
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

        $datos = [
            "dni" => $this->input->post("dni"),
            "nombre" => $nombre,
            "telefono" => $telefono,
            "medico" => $this->input->post("medico"),
            "fecha" => $fecha,
            "hora" => $hora,
            "estado" => $this->input->post("estado"),
            "observaciones" => $this->input->post("observaciones"),
            "triage" => "No" // No hay triage desde la web
        ];
        $this->Citas_model->crearCita($datos);

        // 2. ENVIAR WHATSAPP (Nueva lógica)
        try {
            $this->load->helper('whatsapp');
            
            $mensaje = "🌸 *Clínica Mujer Plena*\n\n";
            $mensaje .= "Hola *$nombre*, su cita ha sido reservada con éxito desde la Web.\n";
            $mensaje .= "📅 *Fecha:* $fecha\n";
            $mensaje .= "⏰ *Hora:* $hora\n\n";
            $mensaje .= "¡La esperamos en Chiclayo! 🚀";
    
            enviar_whatsapp_cita($telefono, $mensaje);
        } catch (Exception $e) {
            // Ignorar error de whatsapp para no fallar la respuesta principal
            log_message('error', 'Error enviando whatsapp: ' . $e->getMessage());
        }
    }
}