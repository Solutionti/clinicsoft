// --- VARIABLES GLOBALES ESPECÍFICAS PARA ECOGRAFÍA ---
var table_ecografia;         // Tabla Izquierda (Catálogo)
var table_ecografia_items;   // Tabla Derecha (Seleccionados)
var ids_ecografias = [];     // Array para controlar duplicados

$(document).ready(function() {
    
    // --- INICIALIZAR TABLAS DE ECOGRAFÍA ---
    
    // A. Tabla Izquierda (Catálogo)
    table_ecografia = $('#table-ecografia').DataTable({
        "destroy": true,
        "lengthMenu": [5, 10, 20],
        "language": { "search": "Buscar:", "zeroRecords": "No encontrado", "info": "" },
        "dom": 'ftip',
        "pageLength": 5
    });

    // B. Tabla Derecha (Seleccionados)
    table_ecografia_items = $('#table-ecografia-items').DataTable({
        "destroy": true,
        "paging": false,
        "searching": false,
        "info": false,
        "language": { "zeroRecords": "Ninguna ecografía seleccionada" },
        "rowCallback": function(row, data) {
            // Reducir espaciado vertical entre filas
            $(row).addClass('py-1 my-0');
            $(row).find('td').addClass('py-1');
        }
    });

    // --- EVENTO CLIC EN FILA (Agregar Individual) ---
    $('#table-ecografia tbody').on('click', 'tr', function () {
        var data = table_ecografia.row(this).data();
        if(data) {
            // data[0] es el Código, data[1] es el Nombre
            agregarEcografia(data[0], data[1]);
        }
    });

    // --- EVENTO TOGGLE EN TABLA DERECHA (Deseleccionar) ---
    $('#table-ecografia-items tbody').on('click', 'tr', function () {
        var data = table_ecografia_items.row(this).data();
        if(data) {
            // Extraer el código del primer elemento (span)
            var codigo = $(data[0]).text().trim();
            var nombre = $(data[1]).find('span').text().trim();
            
            // Eliminar de array de seleccionados
            ids_ecografias = ids_ecografias.filter(item => item !== codigo);
            
            // Eliminar fila de la tabla derecha
            table_ecografia_items.row(this).remove().draw();
            
            // Mostrar fila en la tabla izquierda (si estaba oculta)
            table_ecografia.rows().every(function() {
                var rowData = this.data();
                if(rowData[0].toString() === codigo) {
                    $(this.node()).show();
                }
            });
        }
    });

});

// --- FUNCIÓN PRINCIPAL: AGREGAR ECOGRAFÍA ---
function agregarEcografia(codigo, nombre) {
    // Convertimos a string por seguridad
    codigo = codigo.toString();

    // DEPURACIÓN: Verificar qué valor llega
    console.log("Código recibido:", codigo, "Tipo:", typeof codigo);
    console.log("Nombre recibido:", nombre);

    // Validar duplicados
    if (ids_ecografias.includes(codigo)) {
        return;
    }

    // A. Agregamos al array de control (ESTO ES LO IMPORTANTE)
    ids_ecografias.push(codigo);
    console.log("Array ids_ecografias actualizado:", ids_ecografias);

    // B. Ya no necesitamos input hidden, usamos el array directamente
    // Eliminamos la línea de inputHidden

    // C. Agregamos la fila sin botón de borrar
    var celdaCodigo = `<span class="text-xs font-weight-bold">${codigo}</span>`;
    var celdaNombre = `
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-xs text-uppercase">${nombre}</span> 
        </div>`;

    // D. Agregamos la fila a la tabla derecha
    table_ecografia_items.row.add([
        celdaCodigo,
        celdaNombre
    ]).draw(false);

    // E. Ocultar la fila en la tabla izquierda
    table_ecografia.rows().every(function() {
        var rowData = this.data();
        if(rowData[0].toString() === codigo) {
            $(this.node()).hide();
        }
    });
}

// --- FUNCIÓN LIMPIAR ECOGRAFÍA ---
function limpiarSeleccionEcografia() {
    // Limpiamos tabla derecha
    table_ecografia_items.clear().draw();
    // Limpiamos array
    ids_ecografias = [];
    // Mostramos todas las filas en la tabla izquierda
    table_ecografia.rows().every(function() {
        $(this.node()).show();
    });
}

// --- FUNCIÓN GUARDAR ECOGRAFÍA ---
function guardarEcografiaHistoria() {
    // 1. CAPTURAR DATOS DEL FORMULARIO
    var documento = $("#documento_historia").val();
    var triage = $("#consecutivo_historia").val();
    var medico = $("#medico_solicitante").val();
    var nombre = $("#nombre_paciente").val();
    var edad = $("#edad_paciente").val();
    
    // 2. USAR EL ARRAY DIRECTAMENTE (en lugar de buscar inputs)
    var examenes = ids_ecografias; // Usamos el array que se mantiene actualizado
    
    // DEPURACIÓN: Verificar qué se va a enviar
    console.log("Datos a enviar:");
    console.log("Documento:", documento);
    console.log("Triage:", triage);
    console.log("Array ids_ecografias:", ids_ecografias);
    console.log("Examenes a enviar:", examenes);
    console.log("Cantidad de examenes:", examenes.length);
    
    // 3. VALIDACIÓN
    if (examenes.length === 0) {
        $("body").overhang({
            type: "warn",
            message: "Debe seleccionar al menos una ecografía."
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
    
    // 4. ENVÍO AL CONTROLADOR (usando el mismo endpoint de laboratorio)
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
            servicio: "ECOGRAFIA", // Tipo de servicio diferente
            examenes: examenes // Array de ecografías seleccionadas
        },
        success: function(response) {
            console.log("Respuesta del servidor:", response);
            $("body").overhang({
                type: "success",
                message: "Orden de ecografía guardada correctamente en la historia."
            });
            // Opcional: limpiar después de guardar
            // limpiarSeleccionEcografia();
        },
        error: function(xhr, status, error) {
            console.log("Error en AJAX:", xhr.responseText);
            $("body").overhang({
                type: "error",
                message: "Error al guardar la orden de ecografía."
            });
        }
    });
}