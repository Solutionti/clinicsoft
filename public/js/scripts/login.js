/*
$(document).ready(function () {
    $("#login").on("click", function () {
      var correo = $("#correo").val(),
          password = $("#password").val();

      if(correo == "") {
        $(".correo").addClass("is-invalid");
      }
      else if (password == "") {
        $(".password").addClass("is-invalid");
      }
      else {
        $(".password").addClass("is-valid");
        $(".correo").addClass("is-valid");
          $.ajax({
            url: url1,
            method: "POST",
            data: { correo: correo, password: password },
            success: function (data) {
              if(data === "error"){
                $(".messageError").html('<div class="alert text-white alert-success">El usuario y contraseña ingresado son invalidos.</div>');
                $(".password").addClass("is-invalid");
                $(".correo").addClass("is-invalid");
               }
               else {
                 window.location = baseurl + "administracion/perfil";
               }
             }
           })
        }
    })
});
*/
document.addEventListener("DOMContentLoaded", function () {
  var togglePassword = document.querySelector(".input-group-append.input-group-text");
  var passwordField = document.getElementById("password");
  var icon = document.getElementById("changePassIcon");

  if (togglePassword) {
      togglePassword.addEventListener("click", function (event) {
          event.preventDefault();
          if (passwordField.type === "password") {
              passwordField.type = "text";
              icon.classList.remove("fa-eye");
              icon.classList.add("fa-eye-slash");
          } else {
              passwordField.type = "password";
              icon.classList.remove("fa-eye-slash");
              icon.classList.add("fa-eye");
          }
      });
  }
});


$("#FormLOG").keypress(function (e) {
	e.defaultPrevented;
	if (e.which == 13) {
	  Suubtmit();
		//return false;
	}
});
$("#FormLOG").submit(function (event) {
	event.preventDefault();
  Suubtmit();
});
function Suubtmit() {
  var correo = $("#correo").val(),
  password = $("#password").val();
  $.ajax({
    url: url1,
    method: "POST",
    data: { correo: correo, password: password },
    success: function (data) {
      if(data === "error"){
        $(".messageError").html('<div class="alert text-white alert-success">El usuario y contraseña ingresado son invalidos.</div>');
        $(".password").addClass("is-invalid");
        $(".correo").addClass("is-invalid");
       }
       else {
         window.location = baseurl + "administracion/perfil";
       }
     }
   });
}


