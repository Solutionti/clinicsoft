// --- VARIABLES GLOBALES ESPECÍFICAS PARA RESONANCIA ---
var table_resonancia;         // Tabla Izquierda (Catálogo)
var table_resonancia_items;   // Tabla Derecha (Seleccionados)
var ids_resonancias = [];     // Array para controlar duplicados

$(document).ready(function() {
    
    // --- INICIALIZAR TABLAS DE RESONANCIA ---
    
    // A. Tabla Izquierda (Catálogo)
    table_resonancia = $('#table-resonancia').DataTable({
        "destroy": true,
        "lengthMenu": [5, 10, 20],
        "language": { "search": "Buscar:", "zeroRecords": "No encontrado", "info": "" },
        "dom": 'ftip',
        "pageLength": 5
    });

    // B. Tabla Derecha (Seleccionados)
    table_resonancia_items = $('#table-resonancia-items').DataTable({
        "destroy": true,
        "paging": false,
        "searching": false,
        "info": false,
        "language": { "zeroRecords": "Ninguna resonancia seleccionada" },
        "rowCallback": function(row, data) {
            $(row).addClass('py-1 my-0');
            $(row).find('td').addClass('py-1');
        }
    });

    // --- EVENTO CLIC EN FILA (Agregar) ---
    $('#table-resonancia tbody').on('click', 'tr', function () {
        var data = table_resonancia.row(this).data();
        if(data) {
            agregarResonancia(data[0], data[1]);
        }
    });

    // --- EVENTO CLIC EN TABLA DERECHA (Deseleccionar) ---
    $('#table-resonancia-items tbody').on('click', 'tr', function () {
        var data = table_resonancia_items.row(this).data();
        if(data) {
            var codigo = $(data[0]).text().trim();
            
            // Eliminar de array
            ids_resonancias = ids_resonancias.filter(item => item !== codigo);
            
            // Eliminar fila de tabla derecha
            table_resonancia_items.row(this).remove().draw();
            
            // Mostrar fila en tabla izquierda
            table_resonancia.rows().every(function() {
                var rowData = this.data();
                if(rowData[0].toString() === codigo) {
                    $(this.node()).show();
                }
            });
        }
    });
});

// --- FUNCIÓN: AGREGAR RESONANCIA ---
function agregarResonancia(codigo, nombre) {
    codigo = codigo.toString();

    if (ids_resonancias.includes(codigo)) {
        return;
    }

    ids_resonancias.push(codigo);

    var celdaCodigo = `<span class="text-xs font-weight-bold">${codigo}</span>`;
    var celdaNombre = `
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-xs text-uppercase">${nombre}</span> 
        </div>`;

    table_resonancia_items.row.add([celdaCodigo, celdaNombre]).draw(false);

    // Ocultar de la izquierda
    table_resonancia.rows().every(function() {
        var rowData = this.data();
        if(rowData[0].toString() === codigo) {
            $(this.node()).hide();
        }
    });
}

// --- FUNCIÓN: GUARDAR RESONANCIA ---
function guardarResonanciaHistoria() {
    // 1. CAPTURAR DATOS DEL FORMULARIO
    var documento = $("#documento_historia").val();
    var triage = $("#consecutivo_historia").val();
    var medico = $("#medico_solicitante").val();
    var nombre = $("#nombre_paciente").val();
    var edad = $("#edad_paciente").val();
    
    // 2. OBTENER EXÁMENES SELECCIONADOS
    var examenes = [];
    
    // INTENTO 1: Usar la variable global si tiene datos
    if (ids_resonancias && ids_resonancias.length > 0) {
        examenes = ids_resonancias;
    } 
    // INTENTO 2: Si no, buscar en la tabla visualmente (backup)
    else {
        // Buscar en la tabla #table-resonancia-items
        $('#table-resonancia-items tbody tr').each(function() {
            var celdaCodigo = $(this).find('td:first');
            // Intentar obtener texto limpio
            var codigo = celdaCodigo.text().trim();
            
            if (codigo && codigo !== "Ninguna resonancia seleccionada" && codigo !== "No se encontraron resultados") {
                if (!examenes.includes(codigo)) {
                    examenes.push(codigo);
                }
            }
        });
        
        // Si aún está vacío, intentar con la variable global de historiaclinica.js si existe
        if (examenes.length === 0 && typeof elementos_reso !== 'undefined' && elementos_reso.length > 0) {
             for (let i = 0; i < elementos_reso.length; i++) {
                if (elementos_reso[i] && elementos_reso[i][0]) {
                    examenes.push(elementos_reso[i][0]);
                }
            }
        }
    }
    
    // 3. VALIDACIÓN FINAL
    if (examenes.length === 0) {
        $("body").overhang({ type: "warn", message: "Debe seleccionar al menos una resonancia." });
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
            servicio: "RESONANCIA", // Identificador para el controlador
            examenes: examenes      // Array de IDs
        },
        success: function(response) {
            $("body").overhang({
                type: "success",
                message: "Orden de resonancia guardada correctamente."
            });
        },
        error: function() {
            $("body").overhang({
                type: "error",
                message: "Error al guardar la orden de resonancia."
            });
        }
    });
}

function limpiarSeleccionResonancia() {
    if ($.fn.DataTable.isDataTable('#table-resonancia-items')) {
        $('#table-resonancia-items').DataTable().clear().draw();
    }
    ids_resonancias = [];
    
    if ($.fn.DataTable.isDataTable('#table-resonancia')) {
        $('#table-resonancia').DataTable().rows().every(function() {
            $(this.node()).show();
        });
    }
}
