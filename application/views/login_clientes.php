<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Paciente | ClinicSoft</title>
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/fontawesome/css/all.min.css">
    <link id="pagestyle" href="<?php echo base_url(); ?>public/css/argon-dashboard.css?v=2.0.2" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>public/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/fontawesome/css/brands.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/fontawesome/css/solid.css" rel="stylesheet">
</head>
<style>
    .color-rosa { background-color: #B47CB5 !important; }
    .color-cyan { background-color: #B47CB5 !important; }
    .cyan-text { color: #B47CB5 !important; }
    .rosa-text { color: #B47CB5 !important; }
    
    /* ===== CORRECCIÓN DE INPUT GROUPS (BORDES COMPLETOS) ===== */
    
    /* 1. Estilo base del input (derecha redondeada, izquierda recta) */
    .input-group > .form-control {
        border: 1px solid #d2d6da !important; /* Borde gris estándar */
        border-left: none !important;         /* Quitamos borde izquierdo para unir */
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-top-right-radius: 0.5rem !important; /* Aseguramos redondez derecha */
        border-bottom-right-radius: 0.5rem !important;
        padding-left: 10px !important; /* Espacio para que el texto no se pegue */
        padding-right: 10px !important; /* Espacio derecho */
    }

    /* 2. Estilo del recuadro del icono (izquierda redondeada, derecha abierta) */
    .input-group-text {
        background-color: transparent !important;
        border: 1px solid #d2d6da !important;
        border-right: none !important;        /* Quitamos borde derecho para unir */
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-top-left-radius: 0.5rem !important; /* Aseguramos redondez izquierda */
        border-bottom-left-radius: 0.5rem !important;
        min-width: 45px; /* Ancho mínimo para el icono */
        justify-content: center;
    }

    /* 3. Efecto al hacer foco (focus) en ambos elementos a la vez */
    .input-group:focus-within > .form-control,
    .input-group:focus-within > .input-group-text {
        border-color: #B47CB5 !important;     /* El cyan de tu clínica */
        box-shadow: 0 0 0 2px rgba(180, 124, 181, 0.2) !important;
    }
    
    .input-group:focus-within > .input-group-text i {
        color: #B47CB5 !important;
    }
    
    /* Estilo de la lista lateral */
    .feature-list li {
        font-size: 1.1rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        color: #fff;
    }
    .feature-list li i {
        background: rgba(255, 255, 255, 0.2);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #fff;
    }
</style>
<body class="">
  <main class="main-content mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-center">
                  <img src="<?php echo base_url(); ?>public/img/theme/logo.png" width="180px;" class="img-fluid mb-2">
                  <div class="mt-1">
                    <span class="mx-3 font-weight-bold h5"> <span class="text-primary h4 text-bold">Clinic</span><span class="text-dark h4 text-bold">Soft</span><small class="text-danger text-bold ms-1">Peru</small></span>
                  </div>
                  
                  <h4 class="font-weight-bolder text-dark mt-2 mb-1">Portal del Paciente</h4>
                  <p class="mb-0 text-sm">Ingresa tu número de documento y contraseña para ver tus resultados.</p>
                </div>
                <div class="card-body">
                  <div class="messageError mt-1 text-center"></div>
                  
                  <form role="form" class="mt-3">
                    <label class="form-label">Documento de Identidad (DNI)</label>
                    <div class="input-group mb-3">
                      <span class="input-group-text"><i class="fas fa-id-card text-muted"></i></span>
                      <input type="number" class="form-control form-control-lg" id="correo" placeholder="Ej. 12345678">
                    </div>
                    
                    <label class="form-label">Contraseña</label>
                    <div class="input-group mb-3">
                      <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                      <input type="password" class="form-control form-control-lg" id="password" placeholder="Tu contraseña">
                    </div>
                    
                    <div class="text-end mb-3">
                        <a href="javascript:;" class="text-sm font-weight-bold cyan-text">¿Olvidaste tu contraseña?</a>
                    </div>
                    
                    <div class="text-center">
                      <button type="button" id="login_clientes" class="btn btn-lg color-cyan text-white w-100 mt-2 mb-0 shadow-lg">Ingresar a mi cuenta</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1 mt-3">
                  <p class="mb-4 text-sm mx-auto">
                    ¿Es tu primera vez aquí?
                    <a href="javascript:;" class="rosa-text font-weight-bold">Solicita tu acceso en recepción</a>
                  </p>
                </div>
              </div>
              
              <footer class="footer py-3 mt-auto">
                <div class="container">
                  <div class="row">
                    <div class="col-12 mx-auto text-center">
                      <p class="mb-0 text-secondary text-sm">
                        <i class="fas fa-shield-alt me-1"></i> Plataforma Segura | ClinicSoft Peru © <script>document.write(new Date().getFullYear())</script>
                      </p>
                    </div>
                  </div>
                </div>
              </footer>
            </div>
            
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div 
                class="position-relative bg-gradient-dark h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" 
                style="background-image: url('https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'); background-size: cover; background-position: center;"
              >
                <span class="mask bg-gradient-dark opacity-8"></span>
                
                <div class="position-relative z-index-1 text-start ms-4">
                    <h2 class="text-white font-weight-bolder mb-2">Tus resultados de salud, <br><span class="cyan-text">al alcance de tu mano.</span></h2>
                    <p class="text-white text-lg font-weight-normal mb-5 opacity-8">
                      Accede a tu historial médico de forma rápida, segura y sin salir de casa.
                    </p>
                    
                    <ul class="list-unstyled feature-list">
                        <li><i class="fas fa-microscope"></i> Análisis de Laboratorio</li>
                        <li><i class="fas fa-x-ray"></i> Reportes de Ecografías</li>
                        <li><i class="fas fa-file-medical-alt"></i> Resultados de Patología</li>
                        <li><i class="fas fa-pills"></i> Recetas Médicas</li>
                    </ul>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </section>
  </main>

  <script src="<?php echo base_url(); ?>public/js/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/argon.js"></script>
  <script>
      var baseurl = "<?php echo base_url();?>";
  </script>
  <script src="<?php echo base_url(); ?>public/js/scripts/login_clientes.js"></script>
</body>
</html>