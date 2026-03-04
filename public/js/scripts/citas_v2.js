var dias = [
  "Domingo",
  "Lunes",
  "Martes",
  "Mierc.",
  "Jueves",
  "Viernes",
  "Sabado",
];

$(document).ready(function () {
	$("#table-citas").DataTable({
        "lengthMenu": [5, 50, 100, 200],
        "language":{
          "processing": "Procesando",
          "search": "Buscar:",
          "lengthMenu": "Ver _MENU_ Citas Medicas",
          "info": "Viendo _START_ a _END_ de _TOTAL_ Citas Medicas",
          "zeroRecords": "No encontraron resultados",
          "paginate": {
            "first":      "Primera",
            "last":       "Ultima",
            "next":       "Siguiente",
            "previous":   "Anterior"
          }
        }
       });
});

$("#lupa_DNI").click(function () {
	VAL_Search_DNI();
});

$("#dni").keyup(function (e) {
  e.defaultPrevented;
  if (e.which == 13) {
    VAL_Search_DNI();
  }
});

function Cont_closest(bloque) {

	$(bloque).block({
		message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
		overlayCSS: {
		  backgroundColor: "#fff",
		  opacity: 0.8,
		  cursor: "wait",
		},

		css: {
		  border: 0,
		  padding: 0,
		  backgroundColor: "transparent",
		},

	});

}

function VAL_Search_DNI() {

	var url1 = baseurl + "buscarpaciente",
		dni = $("#dni").val();

	if (dni.length >= 8) {

	$.ajax({
		"url": "https://api.factiliza.com/pe/v1/dni/info/" + $("#dni").val() ,
		"method": "GET",
		"timeout": 0,
		"headers": {
		  "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNzk2MyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.XHG0JHDs8Daik_XHbb6cr90diRfL65qu0IaFJL9GrvY"
		},

      success: function (data) {

        if (data.success) {
			var datos = data.data;
			$("#nombre").val(datos.apellido_paterno +" "+datos.apellido_materno+" "+datos.nombres);
        } else {
			$("body").overhang({
                type: "error",
                message:
                  "Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
              });
        }
      },
      error: function () {
        $("body").overhang({
          type: "error",
          message:
            "Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
        });
      },

    });

	} else {
		warning("Minimo debe ingresar 8 digitos");
	}

}



function Get_Horarios(insert_hora) {

	$("#Cont_Horas").empty();
	var url2 = baseurl + "administracion/traerhorarios",
		fecha = $("#fecha").val(),
		medico = $("#medico").val();

	if (fecha != "" && medico != "") {
		$.ajax({
		  url: url2,
		  method: "POST",
		  data: { medico: medico, fecha: fecha },
			success: function (data) {
				data = JSON.parse(data);
				$("#hora").empty();
				$("#hora").append('<option value="">Seleccionar</option>');
				var stta = true;

				if (data.acction == 1) {
					var arrr = data.horarios_mostrar;
					if(arrr != false){
						for (let i = 0; i < arrr.length; i++) {
							if (
							  arrr[i]["hora"] > insert_hora &&
							  stta == true &&
							  insert_hora != ""
							) {
								stta = false;
								$("#Cont_Horas").append(
									'<button type="button" onclick="btn_clock(this,' +
										"'" +
										insert_hora +
										"'" +
										')" class="btn_clock btn bg-gradient-warning" ><i class="fa fa-clock"></i>  ' +
										insert_hora +
										"</button>"
								);
								$("#hora").append(
									'<option value="' +
										insert_hora +
										'">' +
										insert_hora +
										"</option>"
								);
								/*

								console.log(

									'<option value="' +

										insert_hora +

										'">' +

										insert_hora +

										"</option>"

								);

								*/
							}

							$("#Cont_Horas").append(
								'<button type="button" onclick="btn_clock(this,' +
									"'" +
									arrr[i]["hora"] +
									"'" +
									')" class="btn_clock btn bg-gradient-info" ><i class="fa fa-clock"></i>  ' +
									arrr[i]["hora"] +
									"</button>"
							);

							$("#hora").append(
								'<option value="' +
									arrr[i]["hora"] +
									'">' +
									arrr[i]["hora"] +
									"</option>"
							);
						}
					}else{
					  $("#Cont_Horas").append(
					  '<button type="button" onclick="btn_clock(this,' +
									"'" +
									insert_hora +
									"'" +
									')" class="btn_clock btn bg-gradient-warning" ><i class="fa fa-clock"></i>  ' +
									insert_hora +
									"</button>"
							);

							$("#hora").append(

								'<option value="' +

									insert_hora +

									'">' +

									insert_hora +

									"</option>"

							);

					}

					$("body").overhang({

						type: "success",

						message: "Horarios obtenidos",

					});
			} else if (data.acction == 2) {
                    // 1. (Opcional) Limpia cualquier alerta overhang anterior
                    if (typeof $.overhang === 'function') {
                        $("body").overhang("close"); 
                    }

                    // 2. INYECTAMOS EL RELOJ MANUAL REFINADO Y SUTIL
                    // Usamos .html() para sobreescribir cualquier mensaje previo
                    $("#Cont_Horas").html(`
                        <div class="refined-manual-overide shadow-sm p-3 mb-3" style="background-color: #fcfcfc; border: 1px solid #e3e6f0; border-left: 4px solid #f6c23e; border-radius: 6px;">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-exclamation-triangle text-warning me-2" style="font-size: 0.9rem;"></i>
                                <span class="text-muted fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;">FORZAR HORARIO (FUERA DE TURNO)</span>
                            </div>
                            <p class="text-muted mb-2" style="font-size: 0.8rem; line-height: 1.4;">
                                El doctor no tiene horarios programados para esta fecha. Ingrese la hora acordada manualmente:
                            </p>
                            <input type="time" class="form-control" id="hora_cita_manual" name="hora_cita_manual" style="border-color: #ffeeba; background-color: white;" required>
                        </div>
                    `);

                    // ==========================================
                    // 3. EL PUENTE MÁGICO PARA GUARDAR
                    // ==========================================
                    // Le quitamos el 'required' al select original oculto para que no bloquee
                    $("#hora").removeAttr("required");

                    // Cuando la secretaria escribe la hora en el nuevo campo...
                    $("#hora_cita_manual").on("change", function() {
                        var horaElegida = $(this).val();
                        
                        // ...la inyectamos silenciosamente en tu select original
                        $("#hora").html('<option value="' + horaElegida + '">' + horaElegida + '</option>');
                        $("#hora").val(horaElegida);
                    });
                }
            }, // Aquí sigue el resto de tu código (el 'error: function...' o lo que tengas debajo)

			error: function () {

				$("body").overhang({
					type: "error",
					message: "Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
				});

			},

		});

	} else {

		warning("Completar Medico y Fecha.");

	}

}

function btn_clock(aaa, insert_hora) {

	$("#hora").val(insert_hora);

	$(".btn_clock").each(function () {

		if ($(this).hasClass("bg-gradient-warning")) {

			$(this).removeClass("bg-gradient-warning");

			$(this).addClass("bg-gradient-info");

		}

	});

	$(aaa).removeClass("bg-gradient-info");

	$(aaa).addClass("bg-gradient-warning");

}

$("#medico").change(function () {

	Get_Horarios("");

});

$("#fecha").change(function () {

	Get_Horarios("");

});

$("#lupa_Horario").click(function () {

	Get_Horarios("");

});



$("#AddCITA").keypress(function (e) {

	e.defaultPrevented;

	if (e.which == 13) {

		return false;

	}

});

$("#AddCITA").submit(function (event) {

	event.preventDefault();

	Suubtmit();

});



function Suubtmit() {

	var rruta = "";

	if ($("#statee").val() == "Registrar") {

		rruta = "crearcita";

	} else {

		rruta = "editarcita";

	}

	var url1 = baseurl + "administracion/" + rruta,
		dni = $("#dni").val(),
		idee = $("#idee").val(),
		nombre = $("#nombre").val(),
		telefono = $("#telefono").val(),
		medico = $("#medico").val(),
		fecha = $("#fecha").val(),
		hora = $("#hora").val(),
		estado = $("#estado").val(),
		observaciones = $("#observaciones").val();

    // Debug: Verificar datos antes de enviar
    console.log("Enviando datos a: " + url1);
    console.log({
        idee: idee,
        dni: dni,
        nombre: nombre,
        telefono: telefono,
        medico: medico,
        fecha: fecha,
        hora: hora,
        estado: estado,
        observaciones: observaciones
    });

    if (!nombre || !dni || !medico || !fecha || !hora) {
        $("body").overhang({
            type: "error",
            message: "Por favor complete todos los campos obligatorios."
        });
        return;
    }

	$.ajax({
		url: url1,
		method: "POST",
		dataType: "json", // Esperamos respuesta JSON del servidor
		data: {
			idee: idee,
			dni: dni,
			nombre: nombre,
			telefono: telefono,
			medico: medico,
			fecha: fecha,
			hora: hora,
			estado: estado,
			observaciones: observaciones,
		},
		success: function (response) {
            if(response.status === 'error') {
                $("body").overhang({
                    type: "error",
                    message: response.message
                });
            } else {
                $("body").overhang({
                    type: "success",
                    message: "Listo"
                });
                setTimeout(reloadPage, 3000);
            }
		},
		error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error AJAX:", textStatus, errorThrown);
            console.error("Respuesta:", jqXHR.responseText);
			$("body").overhang({
				type: "warning",
				message: "No se realizo la operacion. Ver consola para detalles."
			});
		},
	});
}



function editarCita(id) {

	var url3 = baseurl + "administracion/getcitasid";

	$.ajax({

		url: url3,

		method: "POST",

		data: {

			id: id,

		},

		success: function (data) {

			$("#AgregarPaciente").modal("show");

			data = JSON.parse(data);

			$("#statee").val("Actualizar");

			$("#idee").val(data.codigo_cita);

			$("#dni").val(data.documento);

			$("#nombre").val(data.nombre);

			$("#telefono").val(data.telefono);

			$("#medico").val(data.doctor).prop("selected", true);

			$("#fecha").val(data.fecha);

			//$("#Cont_Horas").append('<button type="button" onclick="$('+"'#hora'"+').val('+"'"+arrr[i]['hora']+"'"+')" class="btn bg-gradient-warning" ><i class="fa fa-clock"></i>  '+arrr[i]['hora']+'</button>');

			//<button type="button" style="padding: 5px 15px;margin: 5px;" onclick="$('#hora').val('18:15')" class="btn bg-gradient-warning" ><i class="fa fa-clock"></i>&nbsp;&nbsp;18:15</button>

			Get_Horarios(data.hora);

			setTimeout(function () {

				$("#hora").val(data.hora).prop("selected", true);

				$("#estado").val(data.estado).prop("selected", true);

			}, 500);

			$("#observaciones").val(data.comentarios);

		},

		error: function () {

			$("body").overhang({

				type: "error",

				message:

					"Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",

			});

		},

	});

}

$("#editarcitas").on("click", function () {

	var url4 = baseurl + "administracion/editarcita",

		id = $("#id2").val(),

		medico = $("#medico2").val(),

		fecha = $("#fecha2").val(),

		hora = $("#hora2").val(),

		estado = $("#estado2").val(),

		observaciones = $("#observaciones2").val();

	$.ajax({

		url: url4,

		method: "POST",

		data: {

			id: id,

			medico: medico,

			fecha: fecha,

			hora: hora,

			estado: estado,

			observaciones: observaciones,

		},

		success: function () {

			$("body").overhang({

				type: "success",

				message: "Cita actualizada correctamente",

			});

			setTimeout(reloadPage, 3000);

		},

		error: function () {

			$("body").overhang({

				type: "error",

				message:

					"Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",

			});

		},

	});

});



const reloadPage = () => {

	location.reload();

};



var dias = [
	"Domingo",
	"Lunes",
	"Martes",
	"Miercoles",
	"Jueves",
	"Viernes",
	"Sabado",
];



var __colores = [
	"#5bd5f5",
	"#f77a92",
	"#7de78a",
	"#8187dc",
	"#959595",
	"#ffaaf5",
	"#e1c62f",
	"#ff674e",
];





function Reset_Horarios() {

	day_count = 0;

	fecha_hoy = new Date();

	var _ordenado = new Array();

	var __today = fecha_hoy.getDay();

	var a = 0;

	for (var i = __today; i < dias.length; i++) {

		_ordenado.push(dias[i]);

	}

	for (var i = 0; i < __today; i++) {

		_ordenado.push(dias[i]);

	}



	var _ordenado_html = new Array();

	var a = 0;

	

	for (var i = __today; i < dias.length; i++) {

		arr__ = horarios_diarios[i];

		arr__ = ord_arr(arr__);

		

		var _html_dias = "";

		console.log(arr_diass[a]);

		for (var iqq = 0; iqq < arr__.length; iqq++) {

			var namee__ = arr__[iqq]["namedoc"].split(" ");

			_html_dias = _html_dias + 

				"<p onclick='call_reg_cita(" +

					'"' +

					arr_diass[a] +

					'"' +

					"," +

					arr__[iqq]["doc_ide"] +

					")' class='doc_p' title='" +

					arr__[iqq]["namedoc"] +

					" " +

					//arr__[iqq]["Horas_" + (_ordenado[a]).toLowerCase()] +

					"' style='background-color: #"+arr__[iqq]["color"]+";'><i class='fa fa-user-md'></i>  Dr." +

					namee__[0].substr(0, 10).toUpperCase() +"<br><span style='font-size:9px;font-weight:bold;color:#8f8f8f;'>"+arr__[iqq]["horario"]+"<br></p>";

		}

		a++;

		_ordenado_html.push(_html_dias);

	}

	for (var i = 0; i < __today; i++) {

		arr__ = horarios_diarios[i];

		arr__ = ord_arr(arr__);

		var _html_dias = "";

		for (var iqq = 0; iqq < arr__.length; iqq++) {

			var namee__ = arr__[iqq]["namedoc"].split(" ");

			_html_dias = _html_dias + 

				"<p onclick='call_reg_cita(" +

					'"' +

					arr_diass[a] +

					'"' +

					"," +

					arr__[iqq]["doc_ide"] +

					")' class='doc_p' title='" +

					arr__[iqq]["namedoc"] +

					" " +

					//arr__[iqq]["Horas_" + (_ordenado[a]).toLowerCase()] +

					"' style='background-color: #"+arr__[iqq]["color"]+";'><i class='fa fa-user-md'></i>  Dr." +

					namee__[0].substr(0, 10).toUpperCase() +"<br><span style='font-size:9px;font-weight:bold;color:#3e3e3e;'>"+arr__[iqq]["horario"]+"<br></p>";

		}

		a++;

		_ordenado_html.push(_html_dias);

	}



	var a = 0;

	__fecha_hoy = new Date();

	$(".card > .bg-gradient-info").each(function () {

		var __today = __fecha_hoy.getDate();

		$(this).html((_ordenado[a]).slice(0, 6) + " / " + __today);

		a++;

		//$(this).html((_ordenado[a]));a++;

		__fecha_hoy = __fecha_hoy.setDate(__fecha_hoy.getDate() + 1);

		__fecha_hoy = new Date(__fecha_hoy);

	});



	var a = 0;

	$(".cont_day").each(function () {

		$(this).append(_ordenado_html[a]);

		a++;

	});

}



function ord_arr(items){

	items.sort(function (a, b) {

		if (a.hora_ordenable > b.hora_ordenable) {

		return 1;

		}

		if (a.hora_ordenable < b.hora_ordenable) {

		return -1;

		}

		// a must be equal to b

		return 0;

  	});

  	return items;

}



function Reset_HorariosAAAAAAAA() {

	day_count = 0;

	fecha_hoy = new Date();

	var _ordenado = new Array();

	var __today = fecha_hoy.getDay();

	for (var i = __today; i < dias.length; i++) {

		_ordenado.push(dias[i]);

	}

	for (var i = 0; i < __today; i++) {

		_ordenado.push(dias[i]);

	}

	var a = 0;

	__fecha_hoy = new Date();

	$(".card > .bg-gradient-info").each(function () {

		var __today = __fecha_hoy.getDate();

		$(this).html(_ordenado[a].slice(0, 6) + " / " + __today);

		a++;

		//$(this).html(_ordenado[a]);a++;

		__fecha_hoy = __fecha_hoy.setDate(__fecha_hoy.getDate() + 1);

		__fecha_hoy = new Date(__fecha_hoy);

	});

	

	var a = 0;

	//ffffecha_hoy = new Date();

	//ffffecha_hoy = (ffffecha_hoy).setDate(ffffecha_hoy.getDate() - 1);

	$(".cont_day").each(function () {

		$(this).html("");

		for (var i = 0; i < arr_doctors.length; i++) {

			if (arr_doctors[i][_ordenado[a].toLowerCase()] != 0) {

				var namee__ = arr_doctors[i]["nombre"].split(" ");

				//ffffecha_hoy = new Date(ffffecha_hoy);

				//var todayDate = ffffecha_hoy.toISOString().slice(0, 10);

				//console.log(arr_doctors[i]["nombre"] + " " + "Horas_" + _ordenado[a].toLowerCase());

				//console.log(arr_diass[day_count]);

				//console.log(todayDate);

				$(this).append(

					"<p onclick='call_reg_cita(" +

						'"' +

						arr_diass[day_count] +

						'"' +

						"," +

						arr_doctors[i]["codigo_doctor"] +

						")' class='doc_p' title='" +

						arr_doctors[i]["nombre"] +

						" " +

						arr_doctors[i]["Horas_" + _ordenado[a].toLowerCase()] +

						";' style='background-color: " +

						__colores[i] +

						";'><i class='fa fa-user-md'></i>  Dr." +

						namee__[0].substr(0, 10).toUpperCase() +

						"</p>"

				);

			}

		}

		//ffffecha_hoy = ffffecha_hoy.setDate(ffffecha_hoy.getDate() + 1);

		a++;

		day_count++;

	});

}



function call_reg_cita(fecha, medico) {

	$("#AgregarPaciente").modal("show");

	$("#AddCITA").trigger("reset");

	$("#hora").empty();

	$("#fecha").val(fecha);

	$("#medico").val(medico);

	Get_Horarios("");

}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario_medicos');
    if(!calendarEl) return; // Si no existe el div, no hace nada

    // 1. DICCIONARIO DE DÍAS (FullCalendar usa 0 para Domingo, 1 para Lunes, etc.)
    var dias_bd = {
        'Horas_domingo': 0, 
        'Horas_lunes': 1, 
        'Horas_martes': 2,
        'Horas_miercoles': 3, 
        'Horas_jueves': 4, 
        'Horas_viernes': 5, 
        'Horas_sabado': 6
    };

    // Ya no necesitamos la variable "colores", la borramos para limpiar el código
    var eventos_mensuales = [];

    // 1. OBTENEMOS LA FECHA DE HOY EN FORMATO YYYY-MM-DD
    var hoy = new Date();
    var yyyy = hoy.getFullYear();
    var mm = String(hoy.getMonth() + 1).padStart(2, '0');
    var dd = String(hoy.getDate()).padStart(2, '0');
    var fecha_actual = yyyy + '-' + mm + '-' + dd; 

    // 2. CREAR LOS EVENTOS AUTOMÁTICAMENTE LEYENDO TU VARIABLE arr_doctors
    if(typeof arr_doctors !== 'undefined') {
        arr_doctors.forEach(function(doc) {
            
            // ==========================================
            // EXTRACCIÓN DE COLOR INTELIGENTE (Una vez por doctor)
            var colorFondo = "#6c757d"; // Gris por defecto
            
            if (doc.color && doc.color.trim() !== "") {
                var colorDB = doc.color.trim();
                // Si el color es un código pero no tiene el "#", se lo agregamos
                if (!colorDB.startsWith("#") && /^[0-9A-Fa-f]{3,6}$/.test(colorDB)) {
                    colorFondo = "#" + colorDB;
                } else {
                    colorFondo = colorDB;
                }
            }
            // ==========================================

            // Revisamos cada día de la semana para este doctor
            for (var key in dias_bd) {
                // Si el campo tiene datos (no es nulo, no está vacío y no es "0")
                if (doc[key] !== null && doc[key].trim() !== "" && doc[key].trim() !== "0") {

                    eventos_mensuales.push({
                        title: ' ', // Un espacio en blanco para que no salga texto
                        daysOfWeek: [ dias_bd[key] ],
                        color: colorFondo, // ¡MAGIA! Toma el color exacto de la base de datos
                        display: 'list-item', // Lo convierte en un puntito
                        allDay: true
                    });
                }
            }
        });
    }

    // 3. INICIALIZAR EL CALENDARIO
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 'auto',
        
        // BORRAMOS EL validRange QUE LO PONÍA GRIS
        
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            list: 'Agenda'
        },
        events: eventos_mensuales,
        
        // ====== EL TRUCO NINJA PARA OCULTAR EL PASADO ======
        eventClassNames: function(info) {
            var fechaEvento = info.event.start;
            var hoy = new Date();
            hoy.setHours(0,0,0,0); // Reseteamos la hora para comparar solo el día
            
            // Si el bloque del doctor cae en un día antes de hoy...
            if (fechaEvento < hoy) {
                return ['d-none']; // Clase de Bootstrap que lo hace invisible
            }
            return [];
        },
       
        // =========================================================
        // MAGIA: AL HACER CLIC EN UN DÍA
        // =========================================================
        dateClick: function(info) {
           mostrarDoctoresDelDia(info.dateStr); // Llamamos a la función empaquetada
        },
     
    });
    
    calendar.render();

	// =========================================================
    // MAGIA: CARGAR EL DÍA DE HOY AUTOMÁTICAMENTE AL INICIAR
    // =========================================================
    var hoy = new Date();
    var yyyy = hoy.getFullYear();
    var mm = String(hoy.getMonth() + 1).padStart(2, '0');
    var dd = String(hoy.getDate()).padStart(2, '0');
    var fecha_hoy_str = yyyy + '-' + mm + '-' + dd;
    
    // Disparamos la función con la fecha de hoy
    mostrarDoctoresDelDia(fecha_hoy_str);
    // =========================================================
    // FUNCIÓN PARA PINTAR DOCTORES EN EL PANEL DERECHO
    // =========================================================
    function mostrarDoctoresDelDia(fecha_str) {
    var fecha_clic = new Date(fecha_str + 'T00:00:00'); 
    var dia_semana = fecha_clic.getDay(); 

    var opciones_fecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    var fecha_texto = fecha_clic.toLocaleDateString('es-ES', opciones_fecha);
    $("#titulo_panel_dia").html('<i class="fas fa-calendar-day"></i> ' + fecha_texto);

    // =========================================================
    // 1. PRIMERO: Identificar quién trabaja hoy
    // =========================================================
    var lista_ordenada = arr_doctors.map(function(doc) {
        var trabaja = false;
        if (dia_semana === 0 && doc.Horas_domingo && doc.Horas_domingo.trim() !== "0" && doc.Horas_domingo.trim() !== "") trabaja = true;
        if (dia_semana === 1 && doc.Horas_lunes && doc.Horas_lunes.trim() !== "0" && doc.Horas_lunes.trim() !== "") trabaja = true;
        if (dia_semana === 2 && doc.Horas_martes && doc.Horas_martes.trim() !== "0" && doc.Horas_martes.trim() !== "") trabaja = true;
        if (dia_semana === 3 && doc.Horas_miercoles && doc.Horas_miercoles.trim() !== "0" && doc.Horas_miercoles.trim() !== "") trabaja = true;
        if (dia_semana === 4 && doc.Horas_jueves && doc.Horas_jueves.trim() !== "0" && doc.Horas_jueves.trim() !== "") trabaja = true;
        if (dia_semana === 5 && doc.Horas_viernes && doc.Horas_viernes.trim() !== "0" && doc.Horas_viernes.trim() !== "") trabaja = true;
        if (dia_semana === 6 && doc.Horas_sabado && doc.Horas_sabado.trim() !== "0" && doc.Horas_sabado.trim() !== "") trabaja = true;
        
        doc.en_turno_ahora = trabaja; // Propiedad temporal para ordenar
        return doc;
    });

    // =========================================================
    // 2. SEGUNDO: ORDENAR (True va antes que False)
    // =========================================================
    lista_ordenada.sort(function(a, b) {
        return (b.en_turno_ahora === a.en_turno_ahora) ? 0 : b.en_turno_ahora ? 1 : -1;
    });

    // =========================================================
    // 3. TERCERO: DIBUJAR LA LISTA
    // =========================================================
    var html_doctores = '<div class="list-group">';
    var contador_doctores = 0;

    lista_ordenada.forEach(function(doc) {
        contador_doctores++;
        
        // Limpieza de nombres para la tarjeta
        var primerNombre = doc.nombre.split(' ')[0]; 
        var primerApellido = (doc.apellido && doc.apellido.trim() !== "") ? doc.apellido.trim().split(' ')[0] : "";
        
        // Color desde base de datos o gris por defecto
        var colorFondo = (doc.color && doc.color.trim() !== "") ? doc.color.trim() : "#6c757d";
        if (!colorFondo.startsWith("#")) colorFondo = "#" + colorFondo;

        var etiqueta_estado = doc.en_turno_ahora 
            ? '<span class="badge bg-success" style="font-size: 0.65rem; padding: 0.4em 0.6em;">EN TURNO</span>' 
            : '<span class="badge bg-secondary" style="font-size: 0.65rem; padding: 0.4em 0.6em;">FUERA DE HORARIO</span>';

        html_doctores += `
            <a href="javascript:void(0)" onclick="call_reg_cita('${fecha_str}', '${doc.codigo_doctor}')" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="rounded-circle text-white d-flex justify-content-center align-items-center flex-shrink-0" style="background-color: ${colorFondo}; width: 45px; height: 45px; min-width: 45px; font-size: 1.2rem; box-shadow: 0 2px 5px rgba(0,0,0,0.15);">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="ms-3 flex-grow-1" style="min-width: 0;">
                    <h6 class="mb-1 fw-bold text-dark text-truncate" style="font-size: 0.95rem;">Dr. ${primerNombre} ${primerApellido}</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted text-truncate me-2" style="font-size: 0.8rem;">
                            <i class="fas fa-stethoscope"></i> ${doc.perfil}
                        </small>
                        ${etiqueta_estado}
                    </div>
                </div>
            </a>
        `;
    });

    html_doctores += '</div>';

    if (contador_doctores > 0) {
        $("#panel_doctores_dia").html(html_doctores);
    } else {
        $("#panel_doctores_dia").html(`
            <div class="alert alert-light border text-center text-muted">
                <i class="fas fa-bed fs-2 mb-2"></i><br>
                Ningún doctor registrado.
            </div>
        `);
    }
}
	// =========================================================
// SISTEMA DE NOTIFICACIONES EN TIEMPO REAL (AJAX POLLING)
// =========================================================

var ultimo_id_conocido = 0; // 0 significa que recién abrimos el sistema

function vigilarNuevasCitas() {
    $.ajax({
        url: baseurl + "administracion/verificar_nuevas_citas", 
        type: 'POST',
        data: { ultimo_id: ultimo_id_conocido },
        dataType: 'json',
        success: function(respuesta) {
            
            if (ultimo_id_conocido === 0) {
                // Primera vez que carga la página: solo guardamos el ID actual en silencio
                ultimo_id_conocido = respuesta.max_id;
            } else {
                // Ya estábamos vigilando. ¡Revisamos si hay nuevas!
                if (respuesta.hay_nuevas) {
                    
                    // Actualizamos nuestro ID para no repetir la alerta
                    ultimo_id_conocido = respuesta.max_id; 
                    
                    // Texto dinámico (singular o plural)
                    var texto_alerta = respuesta.cantidad === 1 
                        ? "¡Ha ingresado 1 nueva reserva online!" 
                        : "¡Han ingresado " + respuesta.cantidad + " nuevas reservas online!";
                    
                    // Lanzamos la notificación bonita desde arriba
                    $("body").overhang({
                        type: "success",
                        message: texto_alerta,
                        duration: 6,
                        upper: true
                    });

                    // (Opcional) Si quieres que suene una campanilla, descomenta estas dos líneas y pon un mp3
                    // var sonido = new Audio('assets/sonidos/campana.mp3');
                    // sonido.play();
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Depuración: ver qué error real está ocurriendo
            console.error("Error en vigilarNuevasCitas:", textStatus, errorThrown);
            console.error("Respuesta del servidor:", jqXHR.responseText);
        }
    });
}

// 1. Ejecutamos al instante para obtener el ID base sin hacer ruido
vigilarNuevasCitas();

// 2. Programamos al vigilante para que pregunte cada 30 segundos (30000 milisegundos)
// Es el balance perfecto: no consume recursos y la secretaria se entera casi al instante.
setInterval(vigilarNuevasCitas, 30000);
});