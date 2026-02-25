// --- VARIABLES GLOBALES ESPECÍFICAS PARA HISTORIA ---
var table_lab_historia;       // Tabla Izquierda (Catálogo)
var table_agregados_historia; // Tabla Derecha (Seleccionados)
var ids_seleccionados = [];   // Array para controlar duplicados

// --- 1. DEFINICIÓN DE PERFILES (Tu data original) ---
var perfilesLaboratorio = {
    'preoperatorio': ['238', '224', '402', '404', '221', '430', '150', '259', '247', '448'],
    'perfil_prenatal': ['238', '224', '221', '430', '150', '259', '247', '448'],
    'perfil_recien_nacido': ['238', '224', '221', '96', '64'],
    'perfil_coagulacion': ['402', '404', '403', '406', '405', '374', '209', '178', '47', '363'],
    'perfil_cardiaco': ['134', '96', '230', '356', '362', '426'],
    'perfil_torch': ['413', '117', '379', '252'],
    'perfil_hepatico': ['416', '417', '210', '64', '216', '403', '365'],
    'perfil_lipidico': ['134', '230', '290', '442'],
    'perfil_prostatico': ['371', '370'],
    'perfil_reumatico': ['207', '437', '9', '361', '45', '202', '44'],
    'perfil_diabetes': ['221', '222', '287', '288', '237', '236', '307', '397'],
    'perfil_renal': ['150', '170', '368', '307', '430', '308', '366', '9'],
    'perfil_fertilidad': ['267', '268', '196', '358', '354', '228', '229', '450', '400', '27', '173', '193', '401', '265', '359']
};

$(document).ready(function() {
    
    // --- 2. INICIALIZAR TABLAS CON LOS NUEVOS IDs ---
    
    // A. Tabla Izquierda (Catálogo)
    table_lab_historia = $('#table-laboratorio-historia').DataTable({
        "destroy": true,
        "lengthMenu": [5, 10, 20],
        "language": { "search": "Buscar:", "zeroRecords": "No encontrado", "info": "" },
        "dom": 'ftip', // Simplificado para que ocupe menos espacio
        "pageLength": 5
    });

    // B. Tabla Derecha (Seleccionados) - Sin paginación para ver todo lo que agregamos
    // Ojo: Usamos el ID nuevo que definimos
    table_agregados_historia = $('#table-laboratorio-agregados-historia').DataTable({
        "destroy": true,
        "paging": false,
        "searching": false,
        "info": false,
        "language": { "zeroRecords": "Ningún examen seleccionado" },
        "rowCallback": function(row, data) {
            // Reducir espaciado vertical entre filas
            $(row).addClass('py-1 my-0');
            $(row).find('td').addClass('py-1');
        }
    });

    // --- 3. EVENTO CLIC EN FILA (Agregar Individual) ---
    $('#table-laboratorio-historia tbody').on('click', 'tr', function () {
        var data = table_lab_historia.row(this).data();
        if(data) {
            // data[0] es el Código, data[1] es el Nombre
            agregarItemHistoria(data[0], data[1]);
        }
    });

    // --- 4. EVENTO TOGGLE EN TABLA DERECHA (Deseleccionar) ---
    $('#table-laboratorio-agregados-historia tbody').on('click', 'tr', function () {
        var data = table_agregados_historia.row(this).data();
        if(data) {
            // Extraer el código del primer elemento (span)
            var codigo = $(data[0]).text().trim();
            var nombre = $(data[1]).find('span').text().trim();
            
            // Eliminar de array de seleccionados
            ids_seleccionados = ids_seleccionados.filter(item => item !== codigo);
            
            // Eliminar fila de la tabla derecha
            table_agregados_historia.row(this).remove().draw();
            
            // Mostrar fila en la tabla izquierda (si estaba oculta)
            table_lab_historia.rows().every(function() {
                var rowData = this.data();
                if(rowData[0].toString() === codigo) {
                    $(this.node()).show();
                }
            });
        }
    });

});

// --- 5. FUNCIÓN PRINCIPAL: AGREGAR ÍTEM ---
function agregarItemHistoria(codigo, nombre) {
    // Convertimos a string por seguridad
    codigo = codigo.toString();

    // Validar duplicados
    if (ids_seleccionados.includes(codigo)) {
        return;
    }

    // A. Agregamos al array de control
    ids_seleccionados.push(codigo);

    // B. Input Hidden
    var inputHidden = `<input type="hidden" name="examenes[]" value="${codigo}">`;

    // C. Agregamos la fila sin botón de borrar
    var celdaCodigo = `<span class="text-xs font-weight-bold">${codigo}</span>`;
    var celdaNombre = `
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-xs text-uppercase">${nombre}</span> 
            <div>${inputHidden}</div>
        </div>`;

    // D. Agregamos la fila a la tabla derecha
    table_agregados_historia.row.add([
        celdaCodigo,
        celdaNombre
    ]).draw(false);

    // E. Ocultar la fila en la tabla izquierda
    table_lab_historia.rows().every(function() {
        var rowData = this.data();
        if(rowData[0].toString() === codigo) {
            $(this.node()).hide();
        }
    });
}

// --- 6. FUNCIÓN SELECCIONAR PERFIL (Tu lógica adaptada) ---
function seleccionarPerfil() {
    var perfil = $('#selectPerfil').val();
    
    // 1. Primero limpiamos lo actual (opcional, depende de si quieres sumar perfiles o reemplazar)
    limpiarSeleccion(); 

    if (!perfil || !perfilesLaboratorio[perfil]) return;

    var codigosBuscados = perfilesLaboratorio[perfil];
    
    // Recorremos la tabla origen para encontrar los nombres de esos códigos
    // (DataTables permite buscar en data oculta o en otras páginas)
    table_lab_historia.rows().every(function() {
        var d = this.data();
        var codigoFila = d[0].toString(); // Asumiendo col 0 es código
        var nombreFila = d[1];            // Asumiendo col 1 es nombre

        // Si el código de la fila está en el perfil seleccionado...
        if (codigosBuscados.includes(codigoFila)) {
            agregarItemHistoria(codigoFila, nombreFila);
        }
    });

    // Notificación visual
    $("body").overhang({
        type: "success",
        message: perfil + " cargado correctamente (" + codigosBuscados.length + " exámenes)",
        duration: 2
    });
}

// --- 7. FUNCIÓN LIMPIAR ---
function limpiarSeleccion() {
    // Limpiamos tabla derecha
    table_agregados_historia.clear().draw();
    // Limpiamos array
    ids_seleccionados = [];
    // Reset select
    // $('#selectPerfil').val(''); // Descomentar si quieres resetear el select también
}

// --- 8. FUNCIÓN GUARDAR EN HISTORIA ---
function guardarExamenesHistoria() {
    // 1. CAPTURAR DATOS DEL FORMULARIO
    var documento = $("#documento_historia").val();
    var triage = $("#consecutivo_historia").val();
    var medico = $("#medico_solicitante").val();
    var nombre = $("#nombre_paciente").val();
    var edad = $("#edad_paciente").val();
    
    // 2. RECOLECTAR EXÁMENES SELECCIONADOS
    var examenes = [];
    $("input[name='examenes[]']").each(function() {
        var id_examen = $(this).val();
        if (id_examen) {
            examenes.push(id_examen);
        }
    });
    
    // 3. VALIDACIÓN
    if (examenes.length === 0) {
        $("body").overhang({
            type: "warn",
            message: "Debe seleccionar al menos un examen de laboratorio."
        });
        return;
    }
    
    if (!documento || !triage) {
        $("body").overhang({
            type: "error",
            message: "Faltan datos del paciente o triage."
        });
        return;
    }
    
    // 4. ENVÍO AL CONTROLADOR
    var url = baseurl + "administracion/crearOrdenLaboratorio";
    
    $.ajax({
        url: url,
        method: "POST",
        data: {
            documento: documento,
            nombre: nombre,
            edad: edad,
            medico: medico,
            triage: triage,
            servicio: "LABORATORIO", // Tipo de servicio
            examenes: examenes // Array de exámenes seleccionados
        },
        success: function(response) {
            $("body").overhang({
                type: "success",
                message: "Orden de laboratorio guardada correctamente en la historia."
            });
            // Opcional: limpiar después de guardar
            // limpiarSeleccion();
        },
        error: function() {
            $("body").overhang({
                type: "error",
                message: "Error al guardar la orden de laboratorio."
            });
        }
    });
}