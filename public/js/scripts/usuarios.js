$("#crearusuario").on("click", function (){
    var url1 = baseurl + "administacion/crearusuario",
        nombre = $("#nombre").val(),
        apellido = $("#apellido").val(),
        tp_usuario = $("#tp_usuario").val(),
        telefono = $("#telefono").val(),
        direccion = $("#direccion").val(),
        correo = $("#correo").val(),
        password = $("#password").val(),
        usuario = $("#usuario").val();
         //VALIDACION 
         if (nombre === "") {
            $("#nombre").addClass("is-invalid");
          }
          else if (apellido === "") {
            $("#apellido").addClass("is-invalid");
          }
          else if (tp_usuario === "") {
            $("#tp_usuario").addClass("is-invalid");
          }
          else if (telefono === "") {
            $("#telefono").addClass("is-invalid");
          }
          else if (password === "") {
            $("#password").addClass("is-invalid");
          }
          else if (usuario === "") {
          $("#usuario").addClass("is-invalid");

        } else {
        $.ajax({
            url : url1,
            method: "POST", 
            data: {
                nombre: nombre,
                usuario: usuario,
                apellido: apellido,
                tp_usuario: tp_usuario,
                telefono: telefono,
                direccion: direccion,
                correo: correo,
                password: password 
            },
            success: function () {
                $("body").overhang({
                    type: "success",
                    message: "Usuario registrado correctamente"
                });
                setTimeout(reloadPage, 3000);
            },
            error: function () {
                $("body").overhang({
                  type: "error",
                  message: "Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
                }); 
              }
       });
    }
});

function verUsuarios(id) {
    var url2 = baseurl + "administracion/verusuarios";

    $.ajax({
        url: url2,
        method: "POST",
        data: { id: id },
        success: function (data) {
            data = JSON.parse(data);
            console.log(data);
            $("#actualizarUsuario").modal("show");
            $("#id").val(data.codigo_usuario);
            $("#apellido2").val(data.apellido);
            $("#nombre2").val(data.nombre);
            $("#telefono2").val(data.telefono);
            $("#correo2").val(data.email);
            $("#usuario2").val(data.usuario);
            $("#tp_usuario2").val(data.rol_usuario).prop("selected", true);
        },
        error: function () {
            $("body").overhang({
              type: "error",
              message: "Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
            }); 
          }
    })
}

$("#actualizarUsuariom").on("click", function (){
    var url3 = baseurl + "administracion/actualizarusuario",
        id = $("#id").val(),
        tp_usuario = $("#tp_usuario2").val(),
        telefono = $("#telefono2").val(),
        correo = $("#correo2").val(),
        usuario = $("#usuario2").val();

        $.ajax({
            url: url3,
            method: "POST",
            data: {
                id:id,
                tp_usuario: tp_usuario,
                telefono: telefono,
                correo: correo,
                usuario: usuario
            },
            success: function() {
                $("body").overhang({
                    type: "success",
                    message: "Usuario se ha actualizado correctamente"
                });
                setTimeout(reloadPage, 3000);
            },
            error: function () {
                $("body").overhang({
                  type: "error",
                  message: "Alerta ! Tenemos un problema al conectar con la base de datos verifica tu red.",
                }); 
              }
        })
})

const reloadPage = () => {
    location.reload();
  }


    // Optimización del modal Agregar Usuario con jQuery
    $(document).ready(function() {
        $('#Agregarusuario').on('show.bs.modal', function (event) {
             // 1. Encontrar el botón activo del tab
             // Buscamos por la clase active O por el atributo aria-selected="true"
             var activeTab = $('#myTab .nav-link.active');
             
             // Si no encuentra por clase active, intenta por aria-selected
             if (activeTab.length === 0) {
                 activeTab = $('#myTab .nav-link[aria-selected="true"]');
             }

             var activeTabId = activeTab.attr('id');
             var selectRol = $('#tp_usuario');
             var divSelect = selectRol.closest('.form-group'); // El div que contiene el select
             
             // Resetear (mostrar por defecto si no coincide nada)
             selectRol.val("");
             divSelect.show();
             
             // Debug
             console.log("Tab Activo ID:", activeTabId);

             // Mapeo de IDs de pestañas a valores del select
             // Asegúrate de que estos IDs coincidan exactamente con los del HTML
             var mapaRoles = {
                 'home-tab': 'Administrador',
                 'profile-tab': 'Enfermera',
                 'contact-tab': 'Doctor',
                 'contact1-tab': 'Laboratorista',
                 'contact-tab2': 'Patologo' // Corregido: contact-tab2
             };
     
             if (activeTabId && mapaRoles[activeTabId]) {
                 var rolDestino = mapaRoles[activeTabId];
                 console.log("Asignando rol:", rolDestino);
                 
                 // Asignar valor
                 selectRol.val(rolDestino);
                 
                 // Verificar si se asignó correctamente (por si el value no coincide)
                 if (selectRol.val() === rolDestino) {
                     // Ocultar el campo para que "no lo pida"
                     divSelect.hide();
                 } else {
                     console.warn("El valor '" + rolDestino + "' no existe en el select #tp_usuario");
                 }
             }
        });
    });
  
