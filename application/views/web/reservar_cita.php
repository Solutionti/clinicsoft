<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Citas | Clinica Mi Salud</title>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        :root {
            --primary-color: #5e72e4;
            --primary-hover: #4860df;
            --secondary-color: #2dce89;
            --text-color: #344767;
            --bg-color: #f8f9fe;
            --card-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
        }

        body { 
            background-color: var(--bg-color); 
            font-family: 'Poppins', sans-serif; 
            color: var(--text-color);
            background-image: linear-gradient(310deg, #5e72e4 0%, #825ee4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-wrapper {
            width: 100%;
            padding: 2rem 0;
        }

        .wizard-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        /* Header */
        .wizard-header {
            background: transparent;
            padding: 2rem 1rem 1rem;
            text-align: center;
        }
        
        .wizard-header h3 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        /* Pasos */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 2rem 3rem;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            height: 2px;
            background: #e9ecef;
            z-index: 0;
        }

        .step {
            text-align: center;
            position: relative;
            z-index: 1;
            background: transparent;
            width: 33.33%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .step-icon {
            width: 50px; 
            height: 50px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #e9ecef;
            color: #adb5bd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 0.5rem;
        }

        .step.active .step-icon { 
            background: var(--primary-color); 
            color: white; 
            border-color: var(--primary-color);
            box-shadow: 0 0 0 5px rgba(94, 114, 228, 0.2);
            transform: scale(1.1);
        }

        .step.completed .step-icon { 
            background: var(--secondary-color); 
            color: white; 
            border-color: var(--secondary-color);
        }

        .step span {
            font-size: 0.85rem;
            font-weight: 600;
            color: #adb5bd;
            transition: color 0.3s;
            background: rgba(255,255,255,0.8);
            padding: 0 5px;
            border-radius: 4px;
        }

        .step.active span { color: var(--primary-color); }
        .step.completed span { color: var(--secondary-color); }
        
        /* Contenido */
        .step-content { 
            display: none; 
            padding: 2rem 3rem 3rem; 
            animation: slideUp 0.5s ease; 
        }
        .step-content.active { display: block; }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            background-color: #fff;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(94, 114, 228, 0.1);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        /* Botones */
        .btn {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(87deg, var(--primary-color) 0, var(--primary-hover) 100%);
            border: none;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }

        .btn-success {
            background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%);
            border: none;
        }

        /* Horarios */
        .horas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            max-height: 350px;
            overflow-y: auto;
            padding: 5px;
        }

        .btn-hora {
            background: #fff;
            border: 1px solid #e9ecef;
            color: var(--text-color);
            padding: 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            text-align: center;
        }

        .btn-hora:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: #f6f9fc;
        }

        .btn-hora.seleccionada {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 6px rgba(94, 114, 228, 0.3);
        }

        /* Calendar Customization */
        .flatpickr-calendar {
            box-shadow: none !important;
            border: 1px solid #e9ecef !important;
            background: #fff !important;
            border-radius: 1rem !important;
            margin: 0 auto !important;
            width: 100% !important;
            max-width: 350px;
        }
        
        .flatpickr-day.selected {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        /* Alerts */
        .alert-info {
            background-color: rgba(17, 205, 239, 0.1);
            border-color: rgba(17, 205, 239, 0.5);
            color: #11cdef;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                
                <div class="wizard-container">
                    <div class="wizard-header">
                        <div class="text-center mb-4">
                            <h3 class="mb-1">Reserva tu Cita</h3>
                            <p class="text-muted">Clínica Mi Salud - Servicio en Línea</p>
                        </div>
                        
                        <div class="step-indicator">
                            <div class="step active" id="indicator-1">
                                <div class="step-icon"><i class="fas fa-user-md"></i></div>
                                <span>Especialista</span>
                            </div>
                            <div class="step" id="indicator-2">
                                <div class="step-icon"><i class="far fa-calendar-alt"></i></div>
                                <span>Fecha y Hora</span>
                            </div>
                            <div class="step" id="indicator-3">
                                <div class="step-icon"><i class="far fa-user"></i></div>
                                <span>Tus Datos</span>
                            </div>
                        </div>
                    </div>

                    <div class="wizard-body">
                        <!-- PASO 1: SELECCIÓN DE MÉDICO -->
                        <div class="step-content active" id="step-1">
                            <h5 class="text-center mb-4 font-weight-bold">1. ¿Con quién deseas atenderte?</h5>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="mb-4">
                                        <label for="web_medico" class="form-label">Selecciona un Especialista</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-white"><i class="fas fa-stethoscope text-primary"></i></span>
                                            <select class="form-select" id="web_medico">
                                                <option value="">-- Selecciona un Especialista --</option>
                                                <?php foreach($doctor->result() as $doc) { 
                                                    // 1. Tijeras mágicas: Cortamos por los espacios y sacamos solo el primer elemento [0]
                                                    $primer_nombre = explode(' ', trim($doc->nombre))[0];
                                                    $primer_apellido = explode(' ', trim($doc->apellido))[0];
                                                ?>
                                                    <option value="<?php echo $doc->codigo_doctor; ?>">
                                                        <?php echo "Dr. " . $primer_nombre . " " . $primer_apellido . " - " . $doc->perfil; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-primary btn-lg px-5 shadow-lg" id="btn-next-1">
                                            Siguiente <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PASO 2: FECHA Y HORA -->
                        <div class="step-content" id="step-2">
                            <h5 class="text-center mb-4 font-weight-bold">2. Elige tu horario preferido</h5>
                            <div class="row">
                                <div class="col-md-6 mb-4 d-flex justify-content-center">
                                    <div class="w-100 text-center">
                                        <label class="form-label mb-3"><i class="far fa-calendar-check me-1 text-primary"></i> Calendario</label>
                                        <input type="text" id="web_calendario_inline" class="d-none">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label mb-3"><i class="far fa-clock me-1 text-primary"></i> Turnos Disponibles</label>
                                    <div class="card bg-light border-0">
                                        <div class="card-body p-2">
                                            <div class="horas-grid" id="web_contenedor_horas">
                                                <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
                                                    <i class="fas fa-hand-pointer fs-2 mb-2 opacity-50"></i>
                                                    <small>Selecciona un día en el calendario</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="web_hora_elegida">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                                <button type="button" class="btn btn-secondary bg-white text-dark border btn-prev" data-target="1">
                                    <i class="fas fa-arrow-left me-2"></i> Volver
                                </button>
                                <button class="btn btn-primary px-4" id="btn-next-2">
                                    Siguiente <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PASO 3: DATOS DEL PACIENTE -->
                        <div class="step-content" id="step-3">
                            <h5 class="text-center mb-4 font-weight-bold">3. Finaliza tu reserva</h5>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <form id="formFinalReserva">
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-body bg-light rounded-3">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon icon-shape bg-white shadow-sm rounded-circle text-primary p-3 me-3">
                                                        <i class="fas fa-info-circle"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-dark">Resumen de Cita</h6>
                                                        <small class="text-muted">Verifica los detalles antes de confirmar</small>
                                                    </div>
                                                </div>
                                                <ul class="list-unstyled mb-0 ms-2">
                                                    <li class="mb-2"><i class="fas fa-user-md text-primary me-2"></i> <strong id="resumen_doc"></strong></li>
                                                    <li><i class="far fa-calendar-alt text-primary me-2"></i> <span id="resumen_fecha"></span> a las <strong id="resumen_hora"></strong></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Número de DNI</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="web_dni" required maxlength="11" placeholder="Ingresa tu documento">
                                                <button class="btn btn-outline-primary" type="button" id="btn_reniec">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nombres y Apellidos</label>
                                            <input type="text" class="form-control" id="web_nombre" required placeholder="Tu nombre completo">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Teléfono / Celular</label>
                                            <input type="tel" class="form-control" id="web_telefono" required placeholder="Para contactarte">
                                        </div>

                                        <div class="d-flex justify-content-between pt-3 border-top">
                                            <button type="button" class="btn btn-secondary bg-white text-dark border btn-prev" data-target="2">
                                                <i class="fas fa-arrow-left me-2"></i> Volver
                                            </button>
                                            <button type="submit" class="btn btn-success px-5 shadow" id="btn_confirmar">
                                                <i class="fas fa-check-circle me-2"></i> CONFIRMAR CITA
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="text-center mt-4 text-white opacity-75">
                    <small>&copy; <?php echo date('Y'); ?> Clínica Mi Salud. Todos los derechos reservados.</small>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Variables globales
    var baseurl = "<?php echo base_url(); ?>"; 
    var arr_doctors = <?php echo json_encode($doctor->result()); ?>; // ¡Esta es la clave!// Asegúrate de que esto funcione en tu PHP
    var fecha_seleccionada = "";
    
    $(document).ready(function() {

        // ====== INICIALIZAR CALENDARIO (ESTILO EASY APPOINTMENTS) ======
        var calendario = flatpickr("#web_calendario_inline", {
            inline: true, // Esto hace que siempre se vea abierto
            locale: "es",
            minDate: "today",
            disable: [
                function(date) {
                    // Opcional: Deshabilitar domingos si no atienden
                    return (date.getDay() === 0);
                }
            ],
            onChange: function(selectedDates, dateStr, instance) {
                fecha_seleccionada = dateStr;
                buscarHorasDisponibles();
            }
        });

        // ====== LÓGICA DEL WIZARD (Cambiar de pasos) ======
        
        // Botón Siguiente (De Paso 1 a Paso 2)
       // Botón Siguiente (De Paso 1 a Paso 2)
        $("#btn-next-1").click(function() {
            var medico_id = $("#web_medico").val();
            
            if(medico_id === "") {
                Swal.fire("Atención", "Por favor selecciona un especialista primero.", "warning");
                return;
            }

            // 1. EXTRAER LOS DÍAS DE TRABAJO DEL DOCTOR ELEGIDO
            var doc_data = arr_doctors.find(d => d.codigo_doctor == medico_id);
            var dias_activos = [];
            
            if (doc_data) {
                if (doc_data.Horas_domingo !== null && doc_data.Horas_domingo.trim() !== "0" && doc_data.Horas_domingo.trim() !== "") dias_activos.push(0);
                if (doc_data.Horas_lunes !== null && doc_data.Horas_lunes.trim() !== "0" && doc_data.Horas_lunes.trim() !== "") dias_activos.push(1);
                if (doc_data.Horas_martes !== null && doc_data.Horas_martes.trim() !== "0" && doc_data.Horas_martes.trim() !== "") dias_activos.push(2);
                if (doc_data.Horas_miercoles !== null && doc_data.Horas_miercoles.trim() !== "0" && doc_data.Horas_miercoles.trim() !== "") dias_activos.push(3);
                if (doc_data.Horas_jueves !== null && doc_data.Horas_jueves.trim() !== "0" && doc_data.Horas_jueves.trim() !== "") dias_activos.push(4);
                if (doc_data.Horas_viernes !== null && doc_data.Horas_viernes.trim() !== "0" && doc_data.Horas_viernes.trim() !== "") dias_activos.push(5);
                if (doc_data.Horas_sabado !== null && doc_data.Horas_sabado.trim() !== "0" && doc_data.Horas_sabado.trim() !== "") dias_activos.push(6);
            }

            // 2. APLICAMOS LA RESTRICCIÓN AL CALENDARIO FLATPICKR
            calendario.set("enable", [
                function(date) {
                    return dias_activos.includes(date.getDay());
                }
            ]);

            // ========================================================
            // 3. MAGIA NUEVA: BUSCAR EL DÍA MÁS CERCANO AUTOMÁTICAMENTE
            // ========================================================
            if (dias_activos.length > 0) {
                var hoy = new Date();
                hoy.setHours(0,0,0,0); // Limpiamos la hora para comparar bien
                var proxima_fecha = null;

                // Buscamos en los próximos 14 días cuál es el primero en el que trabaja
                for (var i = 0; i < 14; i++) {
                    var dia_prueba = new Date(hoy);
                    dia_prueba.setDate(hoy.getDate() + i);
                    
                    // Si el día de prueba coincide con un día que trabaja el doctor
                    if (dias_activos.includes(dia_prueba.getDay())) {
                        proxima_fecha = dia_prueba;
                        break; // Detenemos la búsqueda, ¡ya lo encontramos!
                    }
                }

                if (proxima_fecha != null) {
                    // Formatear a YYYY-MM-DD
                    var yyyy = proxima_fecha.getFullYear();
                    var mm = String(proxima_fecha.getMonth() + 1).padStart(2, '0');
                    var dd = String(proxima_fecha.getDate()).padStart(2, '0');
                    fecha_seleccionada = yyyy + "-" + mm + "-" + dd;

                    // Pintamos ese día seleccionado en el calendario visualmente
                    calendario.setDate(fecha_seleccionada);
                    
                    // Disparamos la búsqueda de horas para ese día solito
                    buscarHorasDisponibles();
                } else {
                    calendario.clear();
                    $("#web_contenedor_horas").html('<div class="alert alert-warning w-100 text-center"><i class="fas fa-calendar-times fs-3 mb-2"></i><br>Este doctor no tiene turnos configurados próximos.</div>');
                }
            } else {
                calendario.clear();
                $("#web_contenedor_horas").html('<div class="alert alert-warning w-100 text-center"><i class="fas fa-exclamation-triangle fs-3 mb-2"></i><br>Este doctor no tiene días de atención asignados en el sistema.</div>');
            }

            $("#web_hora_elegida").val("");
            
            // Pasamos a la siguiente pantalla
            cambiarPaso(1, 2);
        });

        // Botón Siguiente (De Paso 2 a Paso 3)
        $("#btn-next-2").click(function() {
            if(fecha_seleccionada === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Debes seleccionar un día en el calendario.',
                    confirmButtonColor: '#5e72e4'
                });
                return;
            }
            if($("#web_hora_elegida").val() === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Debes seleccionar una hora de la lista.',
                    confirmButtonColor: '#5e72e4'
                });
                return;
            }
            
            // Llenar el resumen del Paso 3
            $("#resumen_doc").text( $("#web_medico option:selected").text() );
            $("#resumen_fecha").text( fecha_seleccionada );
            $("#resumen_hora").text( $("#web_hora_elegida").val() );

            cambiarPaso(2, 3);
        });

        // Botones Volver
        $(".btn-prev").click(function() {
            var target = $(this).data("target");
            var current = target + 1;
            cambiarPaso(current, target);
        });

        // Función visual para cambiar pasos
        function cambiarPaso(actual, nuevo) {
            $("#step-" + actual).removeClass("active");
            $("#step-" + nuevo).addClass("active");
            
            // Actualizar indicadores de arriba
            if(nuevo > actual) {
                $("#indicator-" + actual).addClass("completed").removeClass("active");
                $("#indicator-" + nuevo).addClass("active");
            } else {
                $("#indicator-" + actual).removeClass("active");
                $("#indicator-" + nuevo).addClass("active").removeClass("completed");
            }
        }

        // ====== FUNCIÓN PARA BUSCAR HORAS (Tu lógica PHP) ======
        // FUNCIÓN PARA BUSCAR HORAS
        function buscarHorasDisponibles() {
            var medico_id = $("#web_medico").val();
            
            $("#web_contenedor_horas").html('<div class="text-center w-100 py-4"><div class="spinner-border text-primary mb-2"></div><p class="mb-0 text-sm">Consultando disponibilidad...</p></div>');
            $("#web_hora_elegida").val("");

            $.ajax({
                // CAMBIAMOS "administracion" POR "web"
                url: baseurl + "Publico/traerhorarios", 
                method: "POST",
                data: { medico: medico_id, fecha: fecha_seleccionada },
                success: function(data) {
                    try {
                        var respuesta = JSON.parse(data);
                        
                        if (respuesta.acction == 1 && respuesta.horarios_mostrar != false) {
                            var horas = respuesta.horarios_mostrar;
                            var html_botones = "";
                            
                            for (let i = 0; i < horas.length; i++) {
                                html_botones += `<div class="btn-hora" data-hora="${horas[i].hora}"><i class="far fa-clock me-1"></i> ${horas[i].hora}</div>`;
                            }
                            $("#web_contenedor_horas").html(html_botones);
                        } else {
                            $("#web_contenedor_horas").html('<div class="alert alert-warning w-100 text-center m-0 border-0"><i class="fas fa-calendar-times fs-4 mb-2"></i><br><small>No hay turnos disponibles para esta fecha.</small></div>');
                        }
                    } catch(e) {
                        console.error(e);
                        $("#web_contenedor_horas").html('<div class="alert alert-danger w-100 text-center m-0">Error procesando datos.</div>');
                    }
                },
                error: function() {
                    $("#web_contenedor_horas").html('<div class="alert alert-danger w-100 text-center m-0">Error de conexión.</div>');
                }
            });
        }

        // ====== SELECCIONAR HORA ======
        $(document).on("click", ".btn-hora", function() {
            $(".btn-hora").removeClass("seleccionada");
            $(this).addClass("seleccionada");
            $("#web_hora_elegida").val($(this).data("hora"));
        });

        // ====== GUARDAR CITA FINAL ======
       // GUARDAR CITA FINAL
        $("#formFinalReserva").submit(function(e) {
            e.preventDefault();
            
            $("#btn_confirmar").prop("disabled", true).html('<i class="fas fa-spinner fa-spin me-2"></i> Procesando...');

            $.ajax({
                // CAMBIAMOS "administracion" POR "web"
                url: baseurl + "Publico/crearcita",
                method: "POST",
                data: {
                    dni: $("#web_dni").val(),
                    nombre: $("#web_nombre").val(),
                    telefono: $("#web_telefono").val(),
                    medico: $("#web_medico").val(),
                    fecha: fecha_seleccionada,
                    hora: $("#web_hora_elegida").val(),
                    estado: "Pendiente",
                    observaciones: "Reserva vía web (Paciente)",
                    triage: "No"
                },
                success: function() {
                    Swal.fire({
                        title: "¡Reserva Exitosa!",
                        text: "Te esperamos en la Clínica Mi Salud.",
                        icon: "success",
                        confirmButtonColor: "#2dce89",
                        confirmButtonText: "Entendido"
                    }).then(() => {
                        window.location.reload(); // Recarga y limpia todo
                    });
                },
                error: function() {
                    Swal.fire("Error", "No pudimos conectar con el servidor.", "error");
                    $("#btn_confirmar").prop("disabled", false).html('<i class="fas fa-check-circle me-2"></i> Confirmar Cita');
                }
            });
        });

        // (Opcional) Aquí puedes enlazar el botón de RENIEC
        $("#btn_reniec").click(function() {
            // Llama a tu función de Reniec si la tienes para autocompletar el input #web_nombre
        });
        // ========================================================
        // MAGIA: BÚSQUEDA DE DNI CON TU API (RENIEC / FACTILIZA)
        // ========================================================
        
        // Al hacer clic en el botón de la lupa
        $("#btn_reniec").click(function() {
            buscarDNIWeb();
        });

        // Al presionar "Enter" estando dentro del cuadrito del DNI
        $("#web_dni").keypress(function(e) {
            if(e.which == 13) {
                e.preventDefault(); // Evita que el formulario se envíe por accidente
                buscarDNIWeb();
            }
        });

        function buscarDNIWeb() {
            var dni = $("#web_dni").val();
            
            if (dni.length >= 8) {
                // Bloqueamos el botón y ponemos un ícono de cargando
                var btnOriginal = $("#btn_reniec").html();
                $("#btn_reniec").prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Buscando');
                $("#web_nombre").val("Consultando base de datos...");

                $.ajax({
                    "url": "https://api.factiliza.com/pe/v1/dni/info/" + dni,
                    "method": "GET",
                    "timeout": 0,
                    "headers": {
                      "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNzk2MyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.XHG0JHDs8Daik_XHbb6cr90diRfL65qu0IaFJL9GrvY"
                    },
                    success: function (data) {
                        // Restauramos el botón
                        $("#btn_reniec").prop("disabled", false).html(btnOriginal);
                        
                        if (data.success) {
                            var datos = data.data;
                            // Armamos el nombre completo y lo pegamos en el input
                            $("#web_nombre").val(datos.nombres + " " + datos.apellido_paterno + " " + datos.apellido_materno);
                        } else {
                            $("#web_nombre").val("");
                            Swal.fire("Atención", "No pudimos encontrar los datos de este DNI.", "warning");
                        }
                    },
                    error: function () {
                        // Restauramos el botón si hay error de red
                        $("#btn_reniec").prop("disabled", false).html(btnOriginal);
                        $("#web_nombre").val("");
                        Swal.fire("Error de Conexión", "Tenemos un problema al conectar con la base de datos. Por favor, escribe tu nombre manualmente.", "error");
                    }
                });
            } else {
                Swal.fire("Atención", "Mínimo debe ingresar 8 dígitos para buscar el DNI.", "info");
            }
        }

    });
</script>

</body>
</html>