<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Citas extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Doctores_model');
        $this->load->model('Citas_model');
    }

    public function index()
    {
        $doctores = $this->Doctores_model->getDoctores();
        $horarios_diarios = array();
        $arr_domingo = array();
        $arr_lunes = array();
        $arr_martes = array();
        $arr_miercoles = array();
        $arr_jueves = array();
        $arr_viernes = array();
        $arr_sabado = array();
        if ($doctores->num_rows() > 0) {
            foreach ($doctores->result() as $row) {
                $arr_domingo = $this->Set_All_Horarios__ALL_BIG__($arr_domingo, $row->Horas_domingo, $row->nombre, $row->codigo_doctor, $row->color);
                $arr_lunes = $this->Set_All_Horarios__ALL_BIG__($arr_lunes, $row->Horas_lunes, $row->nombre, $row->codigo_doctor, $row->color);
                $arr_martes = $this->Set_All_Horarios__ALL_BIG__($arr_martes, $row->Horas_martes, $row->nombre, $row->codigo_doctor, $row->color);
                $arr_miercoles = $this->Set_All_Horarios__ALL_BIG__($arr_miercoles, $row->Horas_miercoles, $row->nombre, $row->codigo_doctor, $row->color);
                $arr_jueves = $this->Set_All_Horarios__ALL_BIG__($arr_jueves, $row->Horas_jueves, $row->nombre, $row->codigo_doctor, $row->color);
                $arr_viernes = $this->Set_All_Horarios__ALL_BIG__($arr_viernes, $row->Horas_viernes, $row->nombre, $row->codigo_doctor, $row->color);
                $arr_sabado = $this->Set_All_Horarios__ALL_BIG__($arr_sabado, $row->Horas_sabado, $row->nombre, $row->codigo_doctor, $row->color);
            }
        }

        array_push($horarios_diarios, $arr_domingo);
        array_push($horarios_diarios, $arr_lunes);
        array_push($horarios_diarios, $arr_martes);
        array_push($horarios_diarios, $arr_miercoles);
        array_push($horarios_diarios, $arr_jueves);
        array_push($horarios_diarios, $arr_viernes);
        array_push($horarios_diarios, $arr_sabado);

        $fecha_actual = date('Y-m-d');
        // $aaa = date("Y-m-d H:i:s");var_dump($aaa);
        $dias = [
            $fecha_actual,
            $fecha_actual = date('Y-m-d', strtotime($fecha_actual . '+ 1 days')),
            $fecha_actual = date('Y-m-d', strtotime($fecha_actual . '+ 1 days')),
            $fecha_actual = date('Y-m-d', strtotime($fecha_actual . '+ 1 days')),
            $fecha_actual = date('Y-m-d', strtotime($fecha_actual . '+ 1 days')),
            $fecha_actual = date('Y-m-d', strtotime($fecha_actual . '+ 1 days')),
            $fecha_actual = date('Y-m-d', strtotime($fecha_actual . '+ 1 days'))
        ];
        $citas = $this->Citas_model->getCitas();
        $data = [
            'doctor' => $doctores,
            'horarios_diarios' => $horarios_diarios,
            'dias' => $dias,
            'cita' => $citas
        ];
        $this->load->view('administrador/citas', $data);
    }

    public function Set_All_Horarios__ALL_BIG__($__doctores__pro, $horario__, $nombre, $codigo_doctor, $__color)
    {
        $horario__ = str_replace(' ', '', $horario__);
        if ($horario__ != '') {
            // $__doctores__pro = array();//13;15:30-19:00;21-23
            $horas__ = explode(';', $horario__);  // [13];[15:30-19:00];[21-23]
            array_splice($horas__, 0, 1);  // [15:30-19:00];[21-23]
            for ($i = 0; $i < sizeof($horas__); $i++) {
                $hor__as = explode('-', $horas__[$i]);  // (15:30)-(19:00)
                if (sizeof($hor__as) > 1) {
                    $hor__min_1 = explode(':', $hor__as[0]);  // ([15]:[30])
                    $hor__1 = ($hor__min_1[0] * 1);  // [15]
                    $min__1 = 0;  // [0]
                    if (sizeof($hor__min_1) > 1) {
                        $min__1 = ($hor__min_1[1] * 1);  // [30]
                    }

                    $hor__min_2 = explode(':', $hor__as[1]);  // ([19]:[00])
                    $hor__2 = ($hor__min_2[0] * 1);  // [19]
                    $min__2 = 0;  // [0]
                    if (sizeof($hor__min_2) > 1) {
                        $min__2 = ($hor__min_2[1] * 1);  // //[00]
                    }

                    $am_pm = '';
                    if (!($hor__1 * 1 > 12)) {  // AM
                        $am_pm = 'am';
                    } else {  // PM
                        $am_pm = 'am';
                    }
                    $hor________min_1 = $this->ceros($hor__1 * 1, 2) . ':' . $this->ceros($min__1 * 1, 2) . ':00';
                    $hor________min_1 = strtotime($hor________min_1);
                    $hor________min_2 = $this->ceros($hor__2 * 1, 2) . ':' . $this->ceros($min__2 * 1, 2) . ':00';
                    $hor________min_2 = strtotime($hor________min_2);

                    // var_dump($__doctores__pro);
                    array_push($__doctores__pro, array('hora_ordenable' => $hor________min_1, 'horario' => '' . date('h:i A', $hor________min_1) . ' - ' . date('h:i A', $hor________min_2), 'namedoc' => $nombre, 'doc_ide' => $codigo_doctor, 'color' => $__color));
                }
            }
        }
        return $__doctores__pro;
    }

    public function crearCita()
    {
        $dni = $this->input->post('dni');
        $nombre = $this->input->post('nombre');
        $telefono = $this->input->post('telefono');
        $medico = $this->input->post('medico');
        $fecha = $this->input->post('fecha');
        $hora = $this->input->post('hora');
        $estado = $this->input->post('estado');
        $triage = $this->input->post('triage');
        $observaciones = $this->input->post('observaciones');

        // VALIDACIÓN BÁSICA PARA EVITAR ERROR DE BD
        if (empty($nombre) || empty($dni)) {
            // Log para depuración
            file_put_contents('debug_crearcita.txt', print_r($_POST, true));

            $this->output->set_content_type('application/json');
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios (Nombre o DNI)']);
            return;
        }

        $datos = [
            'dni' => $dni,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'medico' => $medico,
            'fecha' => $fecha,
            'hora' => $hora,
            'estado' => $estado,
            'observaciones' => $observaciones,
            'triage' => $triage ? $triage : 0  // Asegurar que no sea NULL
        ];

        // 1. Guardamos la cita en MySQL
        $this->Citas_model->crearCita($datos);

        // ==========================================
        // 2. BUSCAR Y LIMPIAR EL NOMBRE DEL DOCTOR
        // ==========================================
        $nombre_doctor = 'su especialista';

        $query_doc = $this
            ->db
            ->select('nombre, apellido')
            ->where('codigo_doctor', $medico)
            ->get('doctores');

        if ($query_doc->num_rows() > 0) {
            $doc = $query_doc->row();

            // Extraemos SOLO el primer nombre y primer apellido
            $primer_nombre = explode(' ', trim($doc->nombre))[0];
            $primer_apellido = isset($doc->apellido) ? explode(' ', trim($doc->apellido))[0] : '';

            // Convertimos a Formato Título (Ej: Juan Malambo)
            $primer_nombre = ucwords(strtolower($primer_nombre));
            $primer_apellido = ucwords(strtolower($primer_apellido));

            $nombre_doctor = trim($primer_nombre . ' ' . $primer_apellido);
        }

        // ==========================================
        // 3. DARLE ELEGANCIA A LA FECHA, HORA Y PACIENTE
        // ==========================================
        $fecha_bonita = date('d/m/Y', strtotime($fecha));  // Queda: 03/03/2026
        $hora_bonita = date('h:i A', strtotime($hora));  // Queda: 03:15 PM

        // Limpiamos el nombre del paciente (Formato RENIEC: Apellido_Paterno Apellido_Materno Nombres)
        // array_filter quita los espacios dobles por si acaso
        $partes_nombre = array_values(array_filter(explode(' ', trim($nombre))));
        $total_partes = count($partes_nombre);

        if ($total_partes >= 3) {
            // Ejemplo: QUIROZ [0] IGNACIO [1] CESAR [2] ANTHONY [3]
            $primer_apellido = $partes_nombre[0];
            $primer_nombre = $partes_nombre[2];  // El primer nombre siempre está en la posición 3 (índice 2)
            $nombre_paciente_limpio = $primer_nombre . ' ' . $primer_apellido;
        } elseif ($total_partes == 2) {
            // Ejemplo raro de 2 palabras: QUIROZ CESAR
            $nombre_paciente_limpio = $partes_nombre[1] . ' ' . $partes_nombre[0];
        } else {
            // Si solo puso una palabra
            $nombre_paciente_limpio = $nombre;
        }

        // Convertimos a Formato Título (Ej: CESAR QUIROZ -> Cesar Quiroz)
        $nombre_paciente_limpio = ucwords(strtolower($nombre_paciente_limpio));

        // ==========================================
        // 4. ENVIAR WHATSAPP (Mensaje Premium)
        // ==========================================
        try {
            $this->load->helper('whatsapp');

            $mensaje = "🌸 *Clínica Mujer Plena*\n\n";
            $mensaje .= "Hola *$nombre_paciente_limpio*, su cita ha sido reservada con éxito.\n\n";
            $mensaje .= "👩‍⚕️ *Especialista:* Dr(a). $nombre_doctor\n";
            $mensaje .= "🩺 *Servicio:* $observaciones\n";
            $mensaje .= "📅 *Fecha:* $fecha_bonita\n";
            $mensaje .= "⏰ *Hora:* $hora_bonita\n\n";
            $mensaje .= "📋 *Nota:* La atención será por orden de llegada.\n\n";
            $mensaje .= "📍 ¡La esperamos en Av. Grau 671, Chiclayo! 🚀\n";
            $mensaje .= "🗺️ Clic aquí para ver el mapa:\n";
            $mensaje .= "https://www.google.com/maps/search/?api=1&query=Centro+Medico+Mujer+Plena+Chiclayo\n";

            enviar_whatsapp_cita($telefono, $mensaje);
        } catch (Exception $e) {
            // Ignorar error de whatsapp para no fallar la respuesta principal
            log_message('error', 'Error enviando whatsapp: ' . $e->getMessage());
        }

        // Respuesta exitosa para AJAX (Intacta para la secretaria)
        echo json_encode(['status' => 'success', 'message' => 'Cita creada correctamente']);
    }

    public function calendario()
    {
        $doctores = $this->Doctores_model->getDoctores();
        $data = ['doctor' => $doctores];
        $this->load->view('administrador/calendario', $data);
    }

    public function calendarioDoctor()
    {
        $cita_doc = $this->Citas_model->getCitasDoc($this->session->userdata('codigo'));
        $data = [
            'cita_doc' => $cita_doc,
        ];
        $this->load->view('administrador/calendariodoctor', $data);
    }

    public function getdataCalendario()
    {
        $citas = $this->Citas_model->getdataCalendario();
        $datos = array(
            'id' => $citas->codigo_cita,
            'title' => $citas->apellido . '' . $citas->nombre . ' ( ' . $citas->paciente . ' )' . '  ' . $citas->comentarios,
            'start' => $citas->fecha . ' ' . $citas->hora,
            'end' => $citas->fecha . ' ' . $citas->hora,
            'color' => 'red',
            'display' => 'auto',
            'type' => 1
        );
        echo json_encode($citas);
    }

    public function getAllDataCalendario()
    {
        $allcitas = $this->Citas_model->getAllDataCalendario();

        echo json_encode($allcitas);
    }

    public function getDatosCitas()
    {
        $medico = $this->input->post('medico');
        $doctores = $this->Citas_model->getDatosCitas($medico);
        echo json_encode($doctores);
    }

    public function getHorariosDoc()
    {
        $fecha = $this->input->post('fecha');
        $medico = $this->input->post('medico');
        $CitasIdxFecha = $this->Citas_model->getCitasIdxFecha($fecha, $medico);
        $HorariosDoc = $this->Citas_model->getHorariosDoc($medico);
        $horario__ = '';  // Horario del dia para este Doctor
        $timestamp = strtotime($fecha);
        $day = date('N', $timestamp);

        if ($HorariosDoc->num_rows() > 0) {
            foreach ($HorariosDoc->result() as $row) {
                switch ($day) {
                    case 1:
                        $horario__ = $row->Horas_lunes;
                        break;
                    case 2:
                        $horario__ = $row->Horas_martes;
                        break;
                    case 3:
                        $horario__ = $row->Horas_miercoles;
                        break;
                    case 4:
                        $horario__ = $row->Horas_jueves;
                        break;
                    case 5:
                        $horario__ = $row->Horas_viernes;
                        break;
                    case 6:
                        $horario__ = $row->Horas_sabado;
                        break;
                    case 7:
                        $horario__ = $row->Horas_domingo;
                        break;
                }
            }
        }
        // LE AGREGAMOS $fecha AL FINAL PARA QUE LA FUNCION SEPA QUÉ DÍA ES
        echo $this->Set_All_Horarios__($horario__, $CitasIdxFecha, $fecha);
    }

    public function Set_All_Horarios__($horario__, $CitasIdxFecha, $fecha_elegida = '')
    {
        $horario__ = str_replace(' ', '', $horario__);
        if ($horario__ != '') {
            $horarios_mostrar = array();
            $horas__ = explode(';', $horario__);
            $duracion__ = $horas__[0];
            array_splice($horas__, 0, 1);
            for ($i = 0; $i < sizeof($horas__); $i++) {
                $hor__as = explode('-', $horas__[$i]);
                if (sizeof($hor__as) > 1) {
                    $hor__min_1 = explode(':', $hor__as[0]);
                    $hor__min_2 = explode(':', $hor__as[1]);
                    $hor__1 = ($hor__min_1[0] * 1);
                    $min__1 = 0;
                    if (sizeof($hor__min_1) > 1) {
                        $min__1 = ($hor__min_1[1] * 1);
                    }
                    $hor__2 = ($hor__min_2[0] * 1);
                    $min__2 = 0;

                    if (sizeof($hor__min_2) > 1) {
                        $min__2 = ($hor__min_2[1] * 1);
                    }
                    $hor________min_1 = $this->ceros($hor__1 * 1, 2) . ':' . $this->ceros($min__1 * 1, 2) . ':00';
                    $hor________min_1 = strtotime($hor________min_1);
                    array_push($horarios_mostrar, array('hora' => $hor________min_1));
                    $hor________min_1 = strtotime('.' . $duracion__ . ' minute', $hor________min_1);
                    array_push($horarios_mostrar, array('hora' => $hor________min_1));
                    $hor________min_2 = $this->ceros($hor__2 * 1, 2) . ':' . $this->ceros($min__2 * 1, 2) . ':00';
                    $hor________min_2 = strtotime($hor________min_2);
                    for ($iaxx = 0; $iaxx < 50; $iaxx++) {
                        if ($hor________min_1 >= $hor________min_2) {
                            $iaxx = 50;
                        } else {
                            $hor________min_1 = strtotime('.' . $duracion__ . ' minute', $hor________min_1);
                            array_push($horarios_mostrar, array('hora' => $hor________min_1));
                        }
                    }
                    $horarios_mostrar_new = array();
                    for ($iaa = 0; $iaa < sizeof($horarios_mostrar); $iaa++) {
                        $horarios_mostrar_new[$iaa]['hora'] = date('H:i', $horarios_mostrar[$iaa]['hora']);
                    }
                }
            }

            if ($CitasIdxFecha->num_rows() > 0) {
                foreach ($CitasIdxFecha->result() as $row) {
                    $horarios_mostrar_new = $this->eliminar__($horarios_mostrar_new, $row->hora);
                }
            }

            // =========================================================
            // MAGIA INGENIERA: FILTRO DE HORA ACTUAL
            // =========================================================
            date_default_timezone_set('America/Lima');
            $fecha_hoy = date('Y-m-d');
            $hora_actual = date('H:i');  // Formato militar (Ej: 13:45)

            // Si la secretaria o el bot están consultando para HOY mismo
            if ($fecha_elegida === $fecha_hoy) {
                $horarios_filtrados = array();
                for ($i = 0; $i < sizeof($horarios_mostrar_new); $i++) {
                    // Solo guardamos los horarios que sean MAYORES a la hora en este segundo
                    if ($horarios_mostrar_new[$i]['hora'] > $hora_actual) {
                        array_push($horarios_filtrados, $horarios_mostrar_new[$i]);
                    }
                }
                // Sobreescribimos la lista vieja con la lista ya filtrada
                $horarios_mostrar_new = $horarios_filtrados;
            }
            // =========================================================

            // IMPORTANTE: Cambié sizeof($horarios_mostrar) por $horarios_mostrar_new
            // para que detecte correctamente si todos los turnos ya pasaron
            if (sizeof($horarios_mostrar_new) > 0) {
                $data = [
                    'horarios_mostrar' => $horarios_mostrar_new,
                    'sms' => 'Horarios Disponibles',
                    'acction' => 1
                ];
                echo json_encode($data);
            } else {
                $data = [
                    'horarios_mostrar' => array(),
                    'sms' => 'No quedan turnos para hoy',
                    'acction' => 2
                ];
                echo json_encode($data);
            }
        } else {
            $data = [
                'horarios_mostrar' => array(),
                'sms' => 'Dia No Disponible',
                'acction' => 2
            ];
            echo json_encode($data);
        }
    }

    public function eliminar__($arrr, $hora__)
    {
        for ($iaa = 0; $iaa < sizeof($arrr); $iaa++) {
            if ($arrr[$iaa]['hora'] == $hora__) {
                array_splice($arrr, $iaa, 1);
                $this->eliminar__($arrr, $hora__);
            }
        }
        return $arrr;
    }

    public function ceros($valor, $longitud)
    {
        $res = str_pad($valor, $longitud, '0', STR_PAD_LEFT);
        return $res;
    }

    public function getCitasId()
    {
        $id = $this->input->post('id');
        $result = $this->Citas_model->getCitasId($id);
        echo json_encode($result);
    }

    public function editarCitas()
    {
        $id = $this->input->post('idee');
        $dni = $this->input->post('dni');
        $nombre = $this->input->post('nombre');
        $telefono = $this->input->post('telefono');
        $medico = $this->input->post('medico');
        $fecha = $this->input->post('fecha');
        $hora = $this->input->post('hora');
        $estado = $this->input->post('estado');
        $observaciones = $this->input->post('observaciones');

        $data = [
            'dni' => $dni,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'medico' => $medico,
            'fecha' => $fecha,
            'hora' => $hora,
            'estado' => $estado,
            'observaciones' => $observaciones,
        ];

        // 1. Guardamos los cambios en MySQL
        $this->Citas_model->editarCitas($data, $id);

        // ==========================================
        // 2. BUSCAR Y LIMPIAR EL NOMBRE DEL DOCTOR
        // ==========================================
        $nombre_doctor = 'su especialista';

        $query_doc = $this
            ->db
            ->select('nombre, apellido')
            ->where('codigo_doctor', $medico)
            ->get('doctores');

        if ($query_doc->num_rows() > 0) {
            $doc = $query_doc->row();
            $primer_nombre = explode(' ', trim($doc->nombre))[0];
            $primer_apellido = isset($doc->apellido) ? explode(' ', trim($doc->apellido))[0] : '';

            $primer_nombre = ucwords(strtolower($primer_nombre));
            $primer_apellido = ucwords(strtolower($primer_apellido));
            $nombre_doctor = trim($primer_nombre . ' ' . $primer_apellido);
        }

        // ==========================================
        // 3. DARLE ELEGANCIA A LA FECHA, HORA Y PACIENTE
        // ==========================================
        $fecha_bonita = date('d/m/Y', strtotime($fecha));
        $hora_bonita = date('h:i A', strtotime($hora));

        // Limpieza de nombre formato RENIEC
        $partes_nombre = array_values(array_filter(explode(' ', trim($nombre))));
        $total_partes = count($partes_nombre);

        if ($total_partes >= 3) {
            $primer_apellido = $partes_nombre[0];
            $primer_nombre = $partes_nombre[2];
            $nombre_paciente_limpio = $primer_nombre . ' ' . $primer_apellido;
        } elseif ($total_partes == 2) {
            $nombre_paciente_limpio = $partes_nombre[1] . ' ' . $partes_nombre[0];
        } else {
            $nombre_paciente_limpio = $nombre;
        }
        $nombre_paciente_limpio = ucwords(strtolower($nombre_paciente_limpio));

        // ==========================================
        // 4. ENVIAR WHATSAPP (Reprogramación o Cancelación)
        // ==========================================
        try {
            $this->load->helper('whatsapp');

            if ($estado == 'Cancelado') {
                // Mensaje si la cita se CANCELA
                $mensaje = "🚫 *Clínica Mujer Plena - Cita Cancelada*\n\n";
                $mensaje .= "Hola *$nombre_paciente_limpio*, le confirmamos que su cita con el Dr(a). $nombre_doctor ha sido *cancelada* en nuestro sistema.\n\n";
                $mensaje .= 'Si desea agendar nuevamente en el futuro, estamos a su entera disposición. ¡Que tenga un excelente día!';
            } else {
                // Mensaje si la cita se REPROGRAMA / ACTUALIZA
                $mensaje = "🔄 *Clínica Mujer Plena - Reprogramación de Cita*\n\n";
                $mensaje .= "Hola *$nombre_paciente_limpio*, le informamos que su cita ha sido *reprogramada*.\n\n";
                $mensaje .= "👩‍⚕️ *Especialista:* Dr(a). $nombre_doctor\n";
                $mensaje .= "🩺 *Servicio:* $observaciones\n";
                $mensaje .= "📅 *Nueva Fecha:* $fecha_bonita\n";
                $mensaje .= "⏰ *Nueva Hora:* $hora_bonita\n\n";
                $mensaje .= "📍 ¡La esperamos en Av. Grau 671, Chiclayo! 🚀\n";
                $mensaje .= "🗺️ *Ver mapa:* https://www.google.com/maps/search/?api=1&query=Centro+Medico+Mujer+Plena+Chiclayo\n\n";
            }

            enviar_whatsapp_cita($telefono, $mensaje);
        } catch (Exception $e) {
            log_message('error', 'Error enviando whatsapp en edición: ' . $e->getMessage());
        }

        // Respuesta JSON intacta para la secretaria
        echo json_encode(['status' => 'success', 'message' => 'Cita actualizada correctamente']);
    }

    public function verificar_nuevas_citas()
    {
        // Recibimos el último ID que tiene el navegador de la secretaria
        $ultimo_id = $this->input->post('ultimo_id');

        // 1. Buscamos cuál es el ID más alto actualmente en la base de datos
        $this->db->select_max('codigo_cita');  // Cambia 'id_cita' por el nombre de tu columna ID
        $query_max = $this->db->get('citas');  // Cambia 'citas' por el nombre de tu tabla
        $max_id_actual = $query_max->row()->codigo_cita;

        if ($max_id_actual == null)
            $max_id_actual = 0;

        // 2. Si el navegador envió un 0, significa que recién abrió la página.
        // Solo le devolvemos el ID actual para que empiece a vigilar desde ahí.
        if ($ultimo_id == 0) {
            echo json_encode(['hay_nuevas' => false, 'max_id' => $max_id_actual]);
            return;
        }

        // 3. Si el navegador ya tenía un ID, contamos cuántas citas nuevas entraron después de ese ID
        $this->db->where('codigo_cita >', $ultimo_id);
        $cantidad_nuevas = $this->db->count_all_results('citas');

        // 4. Respondemos al navegador
        if ($cantidad_nuevas > 0) {
            echo json_encode([
                'hay_nuevas' => true,
                'cantidad' => $cantidad_nuevas,
                'max_id' => $max_id_actual
            ]);
        } else {
            echo json_encode([
                'hay_nuevas' => false,
                'max_id' => $max_id_actual
            ]);
        }
    }
}
