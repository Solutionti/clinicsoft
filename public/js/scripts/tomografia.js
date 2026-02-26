// --- VARIABLES GLOBALES ESPECÍFICAS PARA TOMOGRAFÍA ---
var table_tomografia;         // Tabla Izquierda (Catálogo)
var table_tomografia_items;   // Tabla Derecha (Seleccionados)
var ids_tomografias = [];     // Array para controlar duplicados

$(document).ready(function() {
    
    // --- INICIALIZAR TABLAS DE TOMOGRAFÍA ---
    
    // A. Tabla Izquierda (Catálogo)
    table_tomografia = $('#table-tomografia').DataTable({
        "destroy": true,
        "lengthMenu": [5, 10, 20],
        "language": { "search": "Buscar:", "zeroRecords": "No encontrado", "info": "" },
        "dom": 'ftip',
        "pageLength": 5
    });

    // B. Tabla Derecha (Seleccionados)
    table_tomografia_items = $('#table-tomografia-items').DataTable({
        "destroy": true,
        "paging": false,
        "searching": false,
        "info": false,
        "language": { "zeroRecords": "Ninguna tomografía seleccionada" },
        "rowCallback": function(row, data) {
            $(row).addClass('py-1 my-0');
            $(row).find('td').addClass('py-1');
        }
    });

    // --- EVENTO CLIC EN FILA (Agregar) ---
    $('#table-tomografia tbody').on('click', 'tr', function () {
        var data = table_tomografia.row(this).data();
        if(data) {
            agregarTomografia(data[0], data[1]);
        }
    });

    // --- EVENTO CLIC EN TABLA DERECHA (Deseleccionar) ---
    $('#table-tomografia-items tbody').on('click', 'tr', function () {
        var data = table_tomografia_items.row(this).data();
        if(data) {
            var codigo = $(data[0]).text().trim();
            
            // Eliminar de array
            ids_tomografias = ids_tomografias.filter(item => item !== codigo);
            
            // Eliminar fila de tabla derecha
            table_tomografia_items.row(this).remove().draw();
            
            // Mostrar fila en tabla izquierda
            table_tomografia.rows().every(function() {
                var rowData = this.data();
                if(rowData[0].toString() === codigo) {
                    $(this.node()).show();
                }
            });
        }
    });
});

// --- FUNCIÓN: AGREGAR TOMOGRAFÍA ---
function agregarTomografia(codigo, nombre) {
    codigo = codigo.toString();

    if (ids_tomografias.includes(codigo)) {
        return;
    }

    ids_tomografias.push(codigo);

    var celdaCodigo = `<span class="text-xs font-weight-bold">${codigo}</span>`;
    var celdaNombre = `
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-xs text-uppercase">${nombre}</span> 
        </div>`;

    table_tomografia_items.row.add([celdaCodigo, celdaNombre]).draw(false);

    // Ocultar de la izquierda
    table_tomografia.rows().every(function() {
        var rowData = this.data();
        if(rowData[0].toString() === codigo) {
            $(this.node()).hide();
        }
    });
}

// --- FUNCIÓN: GUARDAR TOMOGRAFÍA ---
function guardarTomografiaHistoria() {
    // 1. CAPTURAR DATOS DEL FORMULARIO
    var documento = $("#documento_historia").val();
    var triage = $("#consecutivo_historia").val();
    var medico = $("#medico_solicitante").val();
    var nombre = $("#nombre_paciente").val();
    var edad = $("#edad_paciente").val();
    
    // 2. OBTENER EXÁMENES SELECCIONADOS
    var examenes = [];
    
    // INTENTO 1: Usar la variable global si tiene datos
    if (ids_tomografias && ids_tomografias.length > 0) {
        examenes = ids_tomografias;
    } 
    // INTENTO 2: Si no, buscar en la tabla visualmente (backup)
    else {
        // Buscar en la tabla #table-tomografia-items
        $('#table-tomografia-items tbody tr').each(function() {
            var celdaCodigo = $(this).find('td:first');
            // Intentar obtener texto limpio
            var codigo = celdaCodigo.text().trim();
            
            if (codigo && codigo !== "Ninguna tomografía seleccionada" && codigo !== "No se encontraron resultados") {
                if (!examenes.includes(codigo)) {
                    examenes.push(codigo);
                }
            }
        });
        
        // Si aún está vacío, intentar con la variable global de historiaclinica.js si existe
        if (examenes.length === 0 && typeof elementos_tomo !== 'undefined' && elementos_tomo.length > 0) {
             for (let i = 0; i < elementos_tomo.length; i++) {
                if (elementos_tomo[i] && elementos_tomo[i][0]) {
                    examenes.push(elementos_tomo[i][0]);
                }
            }
        }
    }
    
    // 3. VALIDACIÓN FINAL
    if (examenes.length === 0) {
        $("body").overhang({ type: "warn", message: "Debe seleccionar al menos una tomografía." });
        return;
    }

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
            servicio: "TOMOGRAFIA", // Identificador para el controlador
            examenes: examenes      // Array de IDs
        },
        success: function(response) {
            $("body").overhang({
                type: "success",
                message: "Orden de tomografía guardada correctamente."
            });
        },
        error: function() {
            $("body").overhang({
                type: "error",
                message: "Error al guardar la orden de tomografía."
            });
        }
    });
}

function limpiarSeleccionTomografia() {
    if ($.fn.DataTable.isDataTable('#table-tomografia-items')) {
        $('#table-tomografia-items').DataTable().clear().draw();
    }
    ids_tomografias = [];
    
    if ($.fn.DataTable.isDataTable('#table-tomografia')) {
        $('#table-tomografia').DataTable().rows().every(function() {
            $(this.node()).show();
        });
    }
}