<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Administracion / Inicio</title>
  <?php require_once("componentes/head.php"); ?>
</head>
<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-default position-absolute w-100"></div>
  <?php require("componentes/menu.php"); ?>
  <main class="main-content position-relative border-radius-lg">
  <div class="container-fluid py-1">
    <div class="row ">
      <div class="card">
        <div class="container-fluid mt-3">
          <div class="row">
            <div class="col-md-12">
              <div class="d-flex flex-row-reverse">
                <a
                  class="btn btn-success text-white btn-xs"
                  target="_blank"
                >
                  <i class="fas fa-file-medical text-white"></i> HC
                </a>
                <a
                  class="btn btn-danger text-white btn-xs mx-2"
                >
                  <i class="fas fa-pen text-white"></i> Actualizar
                </a>
                <button
                  class="btn btn-primary text-white btn-xs mx-1"
                >
                  <i class="fas fa-database text-white"></i> Crear
                </button>
              </div>
            </div>
          </div>
          <form >
          <div class="row">
              <div class="col-md-3">
                <label>Tipo de Documento</label>
                <select
                  class="form-control form-control-sm"
                >
                  <option value="DNI">DNI</option>
                </select>
              </div>
              <div class="col-md-5">
                <label>Buscar por apellidos </label>
                <input
                  type="text"
                  class="form-control form-control-sm"
                >
              </div>
              <div class="col-md-4">
                <label>Buscar por DNI *</label>
                <div class="input-group">
                  <input
                    type="number"
                    class="form-control form-control-sm"
                  >
                  <!-- <button
                    class="btn btn-primary btn-sm"
                    type="button"
                  >
                    <i class="fas fa-fingerprint"></i>
                  </button> -->
                </div>
              </div>
            </div>
          </form>
          <div class="row mt-3">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr class="text-xs text-white bg-default">
                      <th ></th>
                      <th pSortableColumn="docuemento" class="text-xs text-white bg-default" style="width:27%">PACIENTE</th>
                      <th class="text-xs text-white bg-default">TELEFONO</th>
                      <th class="text-xs text-white bg-default">FECHA NACIMIENTO</th>
                      <th class="text-xs text-white bg-default">SEXO</th>
                      <th class="text-xs text-white bg-default">ESTADO CIVIL</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="row mt-2">
            <h6>Datos de los Pacientes</h6>
          </div>
          <form [formGroup]="crearPacienteForm">
          <div class="row">
            <div class="col-md-2">
              <label>DNI *</label>
              <div class="input-group">
              <input
                type="number"
                class="form-control form-control-sm"
              >
              <!-- <button class="btn btn-primary btn-xs" type="button">
                <i class="fas fa-fingerprint"></i>
              </button> -->
              <div
               class="error"
               *ngIf="dniControl.invalid && (dniControl.touched || dniControl.dirty)"
              >
              </div>
            </div>
            </div>
            <div class="col-md-4">
              <label>Apellidos *</label>
              <input
                type="text"
                class="form-control form-control-sm"
                
              >
              <div
                class="error"
                *ngIf="apellidosControl.invalid && (apellidosControl.touched || apellidosControl.dirty)"
              >
              </div>
            </div>
            <div class="col-md-4">
              <label>Nombres *</label>
              <input
                type="text"
                class="form-control form-control-sm"
                
              >
              <div
                class="error"
                *ngIf="nombresControl.invalid && (nombresControl.touched || nombresControl.dirty)"
              >
              </div>
            </div>
            <div class="col-md-2">
              <label>HC *</label>
              <input
                type="number"
                class="form-control form-control-sm"
                
              >
              <div
                class="error"
                *ngIf="hcControl.invalid && (hcControl.touched || hcControl.dirty)"
              >
              </div>
            </div>
          </div>
          <div class="row mt-1">
            <div class="col-md-3">
              <label>Celular </label>
              <input
                type="number"
                class="form-control form-control-sm"
                
              >
            </div>
            <div class="col-md-2">
              <label>Sexo *</label>
              <select
                class="form-control form-control-sm text-uppercase"
                
              >
                <option value="">Seleccione una opcion</option>
                <option
                  *ngFor="let sexo of getSexs"
                  [value]="sexo.codigo"
                >
                  {{ sexo.descripcion }}
                </option>
              </select>
              <div
                class="error"
                *ngIf="sexoControl.invalid && (sexoControl.touched || sexoControl.dirty)"
              >
              </div>
            </div>
            <div class="col-md-2">
              <label>Fecha de Nacimiento *</label>
              <input
                type="date"
                class="form-control form-control-sm"
                
                (blur)="calcularEdad()"
              >
              <div
                class="error"
                *ngIf="nacimientoControl.invalid && (nacimientoControl.touched || nacimientoControl.dirty)"
              >
              </div>
            </div>
            <div class="col-md-1">
              <label>Edad</label>
              <input
                type="number"
                class="form-control form-control-sm"
                


              >
            </div>
            <div class="col-md-4">
              <label>Direcciòn *</label>
              <input
                type="text"
                class="form-control form-control-sm"
                
              >
              <div
                class="error"
                *ngIf="direccionControl.invalid && (direccionControl.touched || direccionControl.dirty)"
              >
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-4">
              <label>Departamento *</label>
              <select
                class="form-control form-control-sm text-uppercase"
                
                (change)="getProvince()"
              >
                <option value="">SELECCIONE UNA OPCION</option>
                <option
                  *ngFor="let departamento of getDepartament"
                  [value]="departamento.id"
                  >
                  {{ departamento.name }}

                </option>
              </select>
              <div
                class="error"
                *ngIf="departamentoControl.invalid && (departamentoControl.touched || departamentoControl.dirty)"
              >
              </div>
            </div>
            <div class="col-md-4">
              <label>Provincia *</label>
              <select
                class="form-control form-control-sm text-uppercase"
                
                (change)="getDistrict()"
              >
                <option value="">Seleccione una opcion</option>
                <option
                  *ngFor="let provincia of getProvinces"
                  [value]="provincia.id"
                >
                  {{ provincia.name }}
              </option>
              </select>
              <div
                class="error"
                *ngIf="provinciaControl.invalid && (provinciaControl.touched || provinciaControl.dirty)"
              >
              </div>
            </div>
            <div class="col-md-4">
              <label>Distrito *</label>
              <select
                class="form-control form-control-sm text-uppercase"
                
              >
                <option value="">Seleccione una opcion</option>
                <option
                >
                  {{ distrito.name }}
                </option>
              </select>
              <div
                class="error"
                *ngIf="distritoControl.invalid && (distritoControl.touched || distritoControl.dirty)"
              >
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-4">
              <label>Ocupaciòn</label>
              <input
                type="text"
                class="form-control form-control-sm"
                
              >
            </div>
            <div class="col-md-4">
              <label>Grado Academico *</label>
              <select
                class="form-control form-control-sm text-uppercase"
                
              >
                <option value="">Seleccione una opcion</option>
                <option
                  *ngFor="let academico of getAcademic"
                  [value]="academico.codigo"
                  >
                  {{ academico.descripcion }}

                </option>
              </select>
              <div
                class="error"
                *ngIf="academicoControl.invalid && (academicoControl.touched || academicoControl.dirty)"
              >
              </div>
            </div>
            <div class="col-md-4">
              <label>Estado Civil *</label>
              <select
                class="form-control form-control-sm text-uppercase"
                
              >
                <option value="">Seleccione una opcion</option>
                <option
                  *ngFor="let estado of getCivilStatu"
                  [value]="estado.codigo"
                >
                  {{ estado.descripcion }}
              </option>
              </select>
              <div
                class="error"
                *ngIf="civilControl.invalid && (civilControl.touched || civilControl.dirty)"
              >
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-12">
              <h6>¿Es menor de edad ?</h6>
              <div class="form-check form-switch">
                <input
                  class="form-check-input"
                  type="checkbox"
                  role="switch"
                  id="flexSwitchCheckChecked"
                  
                >
                <label class="form-check-label" for="flexSwitchCheckChecked">Si</label>
              </div>
            </div>
            <div class="row mt-1" [hidden]="containerResponsable">
              <div class="col-md-3">
                <label>Documento</label>
                <input
                  type="text"
                  class="form-control form-control-sm"
                  
                >
              </div>
              <div class="col-md-4">
                <label>Nombre Responsable</label>
                <input
                  type="text"
                  class="form-control form-control-sm"
                  
                >
              </div>
              <div class="col-md-2">
                <label>Telefono</label>
                <input
                  type="text"
                  class="form-control form-control-sm"
                  
                >
              </div>
              <div class="col-md-3">
                <label>Parentesco</label>
                <select
                  class="form-control form-control-sm"
                  
                >
                <option value="">Seleccione una opcion</option>
              </select>
              </div>
            </div>
          </div>
          <br>
        </form>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("componentes/personalizar.php"); ?>
 <!-- VENTANAS MODALES -->

<div class="modal fade" id="AgregarPaciente" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-default">
        <h5 class="modal-title text-uppercase text-white" id="exampleModalLabel">Crear paciente</h5>
        <button type="button" class=" close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group input-group-sm has-validation">
                    <label>DNI</label>
                    <input type="number" class="form-control" id="dni" value="">
                    <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="form-group input-group-sm has-validation">

                    <label>Apellido</label>

                    <input type="text" class="form-control" id="apellido" value="">

                    <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="form-group input-group-sm has-validation">

                    <label>Nombre</label>

                    <input type="text" class="form-control" id="nombre" value="">

                    <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

                </div>

            </div>

            <div class="col-md-2">

                <div class="form-group input-group-sm">

                    <label>No HC</label>

                    <input type="text" class="form-control" id="hc" readonly>

                    

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-3">

              <div class="form-group input-group-sm has-validation">

                 <label>Celular</label>

                 <input type="number" class="form-control" id="celular" value="">

                 <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

              </div>

            </div>

            <div class="col-md-3">

              <div class="form-group input-group-sm has-validation" >

                <label>Sexo</label>

                  <select class="form-control" id="sexo">

                    <option value="">Seleccione el sexo</option>

                    <option value="M">Masculino</option>

                    <option value="F">Femenino</option>

                    <option value="O">Otro</option>

                  </select>

                  <div class="invalid-feedback">

                      Campo obligatorio.

                  </div>

              </div>

            </div>

            <div class="col-md-3">

              <div class="form-group input-group-sm has-validation">

                 <label>Fecha de nacimiento</label>

                 <input type="date" class="form-control" id="fecha_nacimiento" value="">

                 <div class="invalid-feedback">

                      Campo obligatorio.

                  </div>

              </div>

            </div>

            <div class="col-md-3">

              <div class="form-group input-group-sm">

                 <label>Edad</label>

                 <input type="number" class="form-control" id="edad">

                 <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

              </div>

            </div>

         </div>

         <div class="row">

           <div class="col-md-4">

              <div class="form-group input-group-sm">

                 <label>Dirección</label>

                 <input type="text" class="form-control" id="direccion">

                 <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

              </div>

            </div>

            <div class="col-md-3">

              <div class="form-group input-group-sm">

                <label>Departamento</label>

                  <select class="form-control"  id="departamento">

                  <option value="">Seleccione el departamento</option>

                   <?php foreach($departamento->result() as $departamentos) { ?>

                  <option value="<?php echo $departamentos->id; ?>"><?php echo $departamentos->name; ?></option>

                  <?php } ?>

                  </select>

                  <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

              </div>

            </div>

            <div class="col-md-3">

              <div class="form-group input-group-sm">

                <label>Provincia</label>

                  <select class="form-control" id="provincia">

                    <option value="">Seleccione la provincia</option>

                    <?php foreach($provincia->result() as $provincias) { ?>

                    <option value="<?php echo $provincias->id; ?>"><?php echo $provincias->name; ?></option>

                    <?php } ?>

                  </select>

                  <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

              </div>

            </div>

            <div class="col-md-2">

              <div class="form-group input-group-sm">

                <label>Distrito</label>

                  <select class="form-control" id="distrito">

                  <option value="">Seleccione el distrito</option>

                  <?php foreach($distrito->result() as $distritos) { ?>

                    <option value="<?php echo $distritos->id; ?>"><?php echo $distritos->name;  ?></option>

                  <?php } ?>

                  </select>

                  <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

              </div>

            </div>

         </div>

         <div class="row">

           <div class="col-md-4">

               <div class="form-group input-group-sm">

                   <label>Ocupación</label>

                   <input type="text" class="form-control" id="ocupacion" value="">

                   <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

               </div>

           </div>

           <div class="col-md-4">

               <div class="form-group input-group-sm">

                   <label>Grado Academico</label>

                   <select class="form-control" id="grado_academico">

                      <option value="">Seleccione el grado academico</option>

                      <option value="Kinder">Kinder</option>

                      <option value="Preescolar">Pre Escolar</option>

                      <option value="analfabeto">Analfabeto</option>

                      <option value="Primaria">Primaria</option>

                      <option value="Secundaria">Secundaria</option>

                      <option value="Superior">Superior</option>

                  </select>

                  <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

               </div>

           </div>

           <div class="col-md-4">

               <div class="form-group input-group-sm">

                   <label>Estado civil</label>

                   <select class="form-control" id="estado_civil">

                        <option value="">Seleccione estado civil</option>

                        <option value="Soltero">Soltero(a)</option>

                        <option value="Casado">Casado(a)</option>

                        <option value="Viudo">Viudo(a)</option>

                        <option value="Conviviente">Conviviente(a)</option>

                        <option value="Divorciado">Divorciado(a)</option>

                        <option value="Otro">Otro(a)</option>

                   </select>

                   <div class="invalid-feedback">

                      Campo obligatorio.

                    </div>

               </div>

           </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <h4>¿Es menor de edad?</h4>

                 <div class="form-check">

                  <input class="form-check-input" type="radio" name="responsable" id="exampleRadios1" value="si">

                   <label class="form-check-label" for="exampleRadios1">

                      Si

                  </label>

                 </div>

            </div>

        </div>

        <div class="responsable" hidden="true">

          <div class="row">

            <div class="col-md-6">

              <div class="form-group input-group-sm">

                 <label>Documento</label>

                 <input type="text" class="form-control" id="documento">

                </div>

              </div>

              <div class="col-md-6">

                <div class="form-group input-group-sm">

                  <label>Familiar Responsable</label>

                  <input type="text" class="form-control" id="fresponsable">

                </div>

              </div>

            </div>

          </div>

        </div>



      <div class="modal-footer">

        <button type="button" class="btn btn-primary" id="crearpaciente">Guardar</button>

      </div>

    </div>

  </div>

</div>



  <?php require_once("componentes/scripts.php"); ?>

  <script src="<?php echo base_url(); ?>public/js/scripts/pacientes_v2.js"></script>

  <script>

    function actualizarPaciente(id) {

        var url5 = baseurl  + "administracion/pacienteidaaaa",

            dni = $("#dni2"),

            nombre = $("#nombre2"),

            apellido = $("#apellido2"),

            celular = $("#celular2"),

            hc = $("#hc2"),

            sexo = $("#sexo2"),

            fecha_nacimiento = $("#fecha_nacimiento2"),

            direccion = $("#direccion2"),

            departamento = $("#departamento2"),

            provincia = $("#provincia2"),

            distrito = $("#distrito2"),

            ocupacion = $("#ocupacion2"),

            academico = $("#grado_academico2"),

            estado_civil = $("#estado_civil2"),

            documento = $("#documento2"),

            fresponsable = $("#fresponsable2");

            $.ajax({

              url: url5,

              method: "POST",

              data: { id: id },

              success: function(data) {

                  data =  JSON.parse(data);

                  console.log(data);

                  $("#actualizarPaciente").modal("show");

                  $("#id2").val(data.codigo_paciente);

                  dni.val(data.documento);

                  nombre.val(data.nombre);

                  apellido.val(data.apellido);

                  hc.val(data.hc);

                  celular.val(data.telefono);

                  sexo.val(data.sexo).prop("selected", true);

                  fecha_nacimiento.val(data.fecha_nacimiento);

                  direccion.val(data.direccion);

                  departamento.val(data.departamento).prop("selected", true);

                  provincia.val(data.provincia).prop("selected", true);

                  distrito.val(data.distrito).prop("selected", true);

                  ocupacion.val(data.ocupacion);

                  academico.val(data.grado_academico).prop("selected", true);

                  estado_civil.val(data.estado_civil).prop("selected", true);

                  documento.val(data.familiar_documento);

                  fresponsable.val(data.familiar_nombre);



              }

          });

      }

  </script>



  <script>

    var departamento = <?php echo json_encode($departamento->result()); ?>;

    var provincia = <?php echo json_encode($provincia->result()); ?>;

    var distrito = <?php echo json_encode($distrito->result()); ?>;



      $("#departamento").change(function(){

        var id_departamento = ($('#departamento').find(":selected").val()).slice(0,2);

        $("#provincia").html("");

        $("#distrito").html("");

        

        $("#provincia").append('<option value="" >Seleccione la Provincia</option>');

        $("#distrito").append('<option value="" >Seleccione el Distrito</option>');

        for (var i = 0; i < provincia.length; i++) {

          if((provincia[i]['id']).slice(0,2) == id_departamento){

            $("#provincia").append('<option value="'+provincia[i]['id']+'" >'+provincia[i]['name']+'</option>');

          }

        }

        for (var i = 0; i < distrito.length; i++) {

          if((distrito[i]['id']).slice(0,2) == id_departamento){

            $("#distrito").append('<option value="'+distrito[i]['id']+'" >'+distrito[i]['name']+'</option>');

          }

        }



        $('#provincia  option[value=""]').attr('selected','selected');

        $('#distrito  option[value=""]').attr('selected','selected');

      });



      $("#provincia").change(function(){

        var id_provincia = ($('#provincia').find(":selected").val()).slice(0,2);

        $('#departamento  option[value="'+id_provincia+'"]').attr('selected','selected');



        $("#distrito").html("");

        $("#distrito").append('<option value="" selected>Seleccione el Distrito</option>');

        for (var i = 0; i < distrito.length; i++) {

          if((distrito[i]['id']).slice(0,2) == id_provincia){

            $("#distrito").append('<option value="'+distrito[i]['id']+'" >'+distrito[i]['name']+'</option>');

          }

        }

        $('#distrito  option[value=""]').attr('selected','selected');



      });



      $("#distrito").change(function(){

        var id_distrito = ($('#distrito').find(":selected").val()).slice(0,2);

        $('#departamento  option[value="'+id_distrito+'"]').attr('selected','selected');



        $("#provincia").html("");

        $("#provincia").append('<option value="">Seleccione la Provincia</option>');

        for (var i = 0; i < provincia.length; i++) {

          if((provincia[i]['id']).slice(0,2) == id_distrito){

            $("#provincia").append('<option value="'+provincia[i]['id']+'" >'+provincia[i]['name']+'</option>');

          }

        }

        

        var id_distrito = ($('#distrito').find(":selected").val()).slice(0,4);

        for (var i = 0; i < provincia.length; i++) {

          if((provincia[i]['id']).slice(0,4) == id_distrito){

            $('#provincia  option[value="'+id_distrito+'"]').attr('selected','selected');

            i = provincia.length;

          }

        }

      });



  </script>

</body>

</html>