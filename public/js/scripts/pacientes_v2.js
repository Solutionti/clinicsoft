var urlpacientes = baseurl + "administracion/pacientes/cargar_pacientes";

$("#table-pacientes").DataTable({
	bProcessing: true,
	serverSide: true,
	ajax: {
		url: urlpacientes,
		type: "post",
		error: function () {
		},
	},
	lengthMenu: [10, 50, 100, 200],
	language: {
		processing: "Procesando",
		search: "Buscar:",
		lengthMenu: "Ver _MENU_  Pacientes",
		info: "Mirando _START_ a _END_ de _TOTAL_ Pacientes",
		zeroRecords: "No encontraron resultados",
		paginate: {
		  first: "Primera",
		  last: "Ultima",
		  next: "Siguiente",
		  previous: "Anterior",
		},
	},
});

var responsable = $("input:radio[name=responsable]").change(function () {
	if ($(this).val() === "si") {
		$(".responsable").attr("hidden", false);
	}
});

$("#crearpaciente").on("click", function () {
	var url1 = baseurl + "crearpacientes",
		dni = $("#dni").val(),
		nombre = $("#nombre").val(),
		apellido = $("#apellido").val(),
		hc = $("#hc").val(),
		celular = $("#celular").val(),
		sexo = $("#sexo").val(),
		fecha_nacimiento = $("#fecha_nacimiento").val(),
		edad = $("#edad").val(),
		direccion = $("#direccion").val(),
		departamento = $("#departamento").val(),
		provincia = $("#provincia").val(),
		distrito = $("#distrito").val(),
		ocupacion = $("#ocupacion").val(),
		grado_academico = $("#grado_academico").val(),
		estado_civil = $("#estado_civil").val(),
		responsable = $("input:radio[name=responsable]").val(),
		documento = $("#documento").val(),
		fresponsable = $("#fresponsable").val();

	if (dni === "") {

		$("#dni").addClass("is-invalid");

	} else if (nombre === "") {

		$("#dni").removeClass("is-invalid");
		$("#dni").addClass("is-valid");
		$("#nombre").addClass("is-invalid");

	} else if (celular === "") {

		$("#nombre").removeClass("is-invalid");
		$("#nombre").addClass("is-valid");
		$("#celular").addClass("is-invalid");

	} else if (sexo === "") {

		$("#celular").removeClass("is-invalid");
		$("#celular").addClass("is-valid");
		$("#sexo").addClass("is-invalid");

	} else if (fecha_nacimiento === "") {

		$("#sexo").removeClass("is-invalid");
		$("#sexo").addClass("is-valid");
		$("#fecha_nacimiento").addClass("is-invalid");

	} else if (direccion === "") {

		$("#fecha_nacimiento").removeClass("is-invalid");
		$("#fecha_nacimiento").addClass("is-valid");
		$("#direccion").addClass("is-invalid");

	} else if (departamento === "") {

		$("#direccion").removeClass("is-invalid");
		$("#direccion").addClass("is-valid");
		$("#departamento").addClass("is-invalid");

	} else if (provincia === "") {

		$("#departamento").removeClass("is-invalid");
		$("#departamento").addClass("is-valid");
		$("#provincia").addClass("is-invalid");

	} else if (distrito === "") {

		$("#provincia").removeClass("is-invalid");
		$("#provincia").addClass("is-valid");
		$("#distrito").addClass("is-invalid");
	}

	else {
		$.ajax({
			url: url1,
			method: "POST",
			data: {
			  dni: dni,
				nombre: nombre,
				apellido: apellido,
				hc: hc,
				celular: celular,
				sexo: sexo,
				fecha_nacimiento: fecha_nacimiento,
				edad: edad,
				direccion: direccion,
				departamento: departamento,
				provincia: provincia,
				distrito: distrito,
				ocupacion: ocupacion,
				grado_academico: grado_academico,
				estado_civil: estado_civil,
				responsable: responsable,
				documento: documento,
				fresponsable: fresponsable,
			},

			success: function (json) {
            json =  JSON.parse(json);
            if(json.success == 1){//Paciente Actualizado Correctamente,
               $("body").overhang({
               type: "success",
               message: json.message
               });

               setTimeout(reloadPage, 3000);

            }
			else {
               $("body").overhang({
               type: "warn",
               message: json.message
               });
            }
		  },
			error: function () {
			  $("body").overhang({
			    type: "error",
				message: "Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
				});
			},
		});
	}
});

$("#fecha_nacimiento").on("blur", function () {
	let fechas = new Date().getTime();
	let cumpleanios = new Date($(this).val()).getTime();
	const anios = (fechas - cumpleanios) / 3.154e10;
	anos = Math.floor(anios);
	$("#edad").val(anos);
	$("#edad").attr("readonly", true);
});

/*************** API RENIEC  ***********/

$("#dni").keyup(function (e) {//keyup se Ejecuta al levantar la soltar la tecla presionada
  e.defaultPrevented;//defaultPrevented anula funciones por default
  if (e.which == 13) {//valido que la tecla soltada sea 13==Tecla Enter
	Buscar_Paciente();
  }
});

$("#dni").on("onblur", function (e) {//keyup se Ejecuta al levantar la soltar la tecla presionada
  Buscar_Paciente();
});

function Buscar_Paciente(dni) {
	url2 =
		"https://my.apidev.pro/api/dni/" +
		$("#dni").val() +
		"?api_token=" +
		"e02a95cc4b8e0d521d447c0786af76cdb424b1a2ba23aed9f7cd9e9bc4712e86";
	$.ajax({
		"url": "https://api.factiliza.com/pe/v1/dni/info/" + $("#dni").val() ,
		"method": "GET",
		"timeout": 0,
		"headers": {
		  "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNzk2MyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.XHG0JHDs8Daik_XHbb6cr90diRfL65qu0IaFJL9GrvY"
		},

		success: function (data) {
			response = data.data;
			$("#nombre").val(response.nombres);
			$("#apellido").val(response.apellido_paterno + " " + response.apellido_materno);
			$("#direccion").val(response.direccion);
			$("#departamento").val(response.ubigeo[0]).prop("selected", true);
			$("#provincia").val(response.ubigeo[1]).prop("selected", true);
			$("#distrito").val(response.ubigeo[2]).prop("selected", true);
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



/*************** API RENIEC  ***********/

$("#documento").keyup(function (e) {//keyup se Ejecuta al levantar la soltar la tecla presionada
	e.defaultPrevented;//defaultPrevented anula funciones por default
	if (e.which == 13) {//valido que la tecla soltada sea 13==Tecla Enter
	  Buscar_Tutor();
	}
  });

  $("#documento").on("blur", function (e) {//keyup se Ejecuta al levantar la soltar la tecla presionada
	  Buscar_Tutor();
  });

function Buscar_Tutor() {
	url2 =
		"https://apiperu.dev/api/dni/" +
		$("#documento").val() +
		"?api_token=" +
		"e02a95cc4b8e0d521d447c0786af76cdb424b6a2ba25aea9f7cd9e9bc4712e86";

	$.ajax({
		"url": "https://api.factiliza.com/pe/v1/dni/info/" + $("#dni").val() ,
		"method": "GET",
		"timeout": 0,
		"headers": {
		  "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNzk2MyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.XHG0JHDs8Daik_XHbb6cr90diRfL65qu0IaFJL9GrvY"
		},

		success: function (data) {
			response = data.data;
			$("#fresponsable").val(response.nombres +" "+response.apellido_paterno +" "+response.apellido_materno);
		},

		error: function () {
			$("body").overhang({
				type: "error",
				message:"Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
			});
		},
	});
}

$("#dni").on("keyup", function() {
	let dni =  $("#dni").val();

	$("#hc").val(dni);
});

// url4 = baseurl + "contarconsecutivo";
// $.ajax({
// 	url: url4,
// 	method: "GET",
// 	success: function (data) {
// 		data = JSON.parse(data);
// 		suma = parseInt(data.numero.numero) + 1;
// 		$("#hc").val(suma);
// 	},
// 	error: function () {
// 		$("body").overhang({
// 			type: "error",
// 			message:"Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
// 		});
// 	},
// });

$("#btn-actualizar").on("click", function () {
	var url4 = baseurl + "administracion/actualizarpacientes",
		id = $("#id2").val(),
		celular = $("#celular2").val(),
		direccion = $("#direccion2").val(),
		departamento = $("#departamento2").val(),
		provincia = $("#provincia2").val(),
		distrito = $("#distrito2").val(),
		ocupacion = $("#ocupacion2").val(),
		grado_academico = $("#grado_academico2").val(),
		estado_civil = $("#estado_civil2").val(),
		documento = $("#documento2").val(),
		fresponsable = $("#fresponsable2").val();
	$.ajax({
		url: url4,
		method: "POST",
		data: {
			id: id,
			celular: celular,
			direccion: direccion,
			departamento: departamento,
			provincia: provincia,
			distrito: distrito,
			ocupacion: ocupacion,
			grado_academico: grado_academico,
			estado_civil: estado_civil,
			documento: documento,
			fresponsable: fresponsable,
		},
		success: function () {
			json =  JSON.parse(json);

         if(json.success == 1){//Paciente Actualizado Correctamente,

            $("body").overhang({
               type: "success",
               message: json.message
            });
			setTimeout(reloadPage, 3000);

         }
		  else{//Paciente Actualizado Correctamente,
            $("body").overhang({
               type: "warn",
               message: json.message
            });
         }
		},
		error: function () {
		  $("body").overhang({
		    type: "error",
			message:"Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
			});
		},
	});
});

function buscarPacienteBaseDatos() {
  let url = baseurl + "administracion/getpacientetabla",
      apellido = $("#apellido_buscar").val().replace(/%/g, ''),
      dni = $("#dni_buscar").val();

  $.ajax({
    url: url,
	method: "POST",
	data: {
	  dni: dni,
	  apellido: apellido	
	},
	success: function(response) {
		console.log(response);
	  if(response == 'error'){
		$("body").overhang({
			type: "error",
			message:"El paciente no se encuentra registrado en la base de datos",
		  });
	  }
	  else {
       paciente = JSON.parse(response);
	   paciente.forEach(pacientes => {
		let cadena = "<tr onclick='pasarDatosPaciente(\"" + pacientes.documento + "\")'><td><div class='form-check'><input class='form-check-input' type='radio' name='flexRadioDefault' id='flexRadioDefault1' id='checkpacientes'></div></td><td><div class='d-flex px-2 py-1'><div><img src='https://radiarte.com/application/files/6917/0923/2244/depositphotos_134255588-stock-illustration-empty-photo-of-male-profile.jpg' class='avatar avatar-sm me-3'></div><div class='d-flex flex-column justify-content-center'><p class='mb-0 text-xs text-uppercase'>"+pacientes.nombre + ' ' +pacientes.apellido+"</p><p class='text-xs text-secondary mb-0 text-uppercase'>"+pacientes.documento+"</p></div></div></td><td>"+pacientes.telefono+"</td><td>"+pacientes.fecha_nacimiento+"</td><td>"+pacientes.sexo+"</td><td>"+pacientes.estado_civil+"</td></tr>";
	     document.getElementById("table_buscar").innerHTML = cadena;
	   });
	  }
	},
	error: function() {
	  $("body").overhang({
		type: "error",
		message:"Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
      });
	}
  });
}

function pasarDatosPaciente(paciente) {
  let url = baseurl + "administracion/pacienteid",
      dni = $("#dni"),
      nombre = $("#nombre"),
      apellido = $("#apellido"),
      celular = $("#celular"),
      hc = $("#hc"),
      sexo = $("#sexo"),
      fecha_nacimiento = $("#fecha_nacimiento"),
      direccion = $("#direccion"),
      departamento = $("#departamento"),
      provincia = $("#provincia"),
	  distrito = $("#distrito"),
      ocupacion = $("#ocupacion"),
      academico = $("#grado_academico"),
      estado_civil = $("#estado_civil");

  $.ajax({
    url: url,
	method: "POST",
	data: {
	  dni: paciente
	},
	success: function(response) {

	  $("#btnhistoria").attr("hidden", false);
	  $("#btnactualizar").attr("hidden", false);
	  response =  JSON.parse(response);
	  abrirHistoriaClinica(response.documento);
	  dni.val(response.documento);
	  nombre.val(response.nombre);
      apellido.val(response.apellido);
      hc.val(response.hc);
      celular.val(response.telefono);
      sexo.val(response.sexo).prop("selected", true);
      fecha_nacimiento.val(response.fecha_nacimiento);
      direccion.val(response.direccion);
      departamento.val(response.departamento).prop("selected", true);
      provincia.val(response.provincia).prop("selected", true);
      distrito.val(response.distrito).prop("selected", true);
      ocupacion.val(response.ocupacion);
      academico.val(response.grado_academico).prop("selected", true);
      estado_civil.val(response.estado_civil).prop("selected", true);
   
	},
	error() {

	}
  })
}
const input1 = document.getElementById('apellido_buscar');
input1.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        buscarPacienteBaseDatos();
    }
});
const input2 = document.getElementById('dni_buscar');
input2.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        buscarPacienteBaseDatos();
    }
});

$("#btnactualizar").on("click", function() {

    var url = baseurl + "administracion/actualizarpacientes",
    dni = $("#dni").val(),
    celular = $("#celular").val(),
    sexo = $("#sexo").val(),
    edad = $("#edad").val(),
    direccion = $("#direccion").val(),
    fecha_nacimiento = $("#fecha_nacimiento").val(),
    departamento = $("#departamento").val(),
    provincia = $("#provincia").val(),
    distrito = $("#distrito").val(),
    ocupacion = $("#ocupacion").val(),
    grado_academico = $("#grado_academico").val(),
    estado_civil = $("#estado_civil").val();
    // documento = $("#documento2").val(),
    // fresponsable = $("#fresponsable2").val();

    $.ajax({
      url: url,
      method: "POST",
      data: { 
        dni: dni, 
        celular: celular, 
        direccion: direccion,
        fecha_nacimiento: fecha_nacimiento,
        departamento: departamento,
        edad: edad,
        sexo: sexo,
        provincia: provincia,
        distrito: distrito,
        ocupacion: ocupacion,
        grado_academico: grado_academico,
        estado_civil: estado_civil,
        // documento: documento,
        // fresponsable: fresponsable
      },
      success: function(json) {// json parametro de respuesta para validar la respuesta
        json =  JSON.parse(json);

        if(json.success == 1){//Paciente Actualizado Correctamente,
          $("body").overhang({
            type: "success",
            message: json.message
          });
		//   setTimeout(reloadPage, 3000);
        }else{//Ya existe un paciente con este NRO DOC - WARNING
          $("body").overhang({
            type: "warn",
            message: json.message
          });
        }
      }
    });
});

function abrirHistoriaClinica(paciente) {
	let url =  baseurl + "administracion/historia/" + paciente;

	window.open(url, '_blank');
}

const reloadPage = () => {
  location.reload();
};