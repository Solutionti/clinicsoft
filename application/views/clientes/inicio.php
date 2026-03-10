<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Portal | ClinicSoft</title>
    <link rel="icon" href="<?php echo base_url(); ?>public/img/theme/logo2.ico" type="image/ico" /> 
    <link id="pagestyle" href="<?php echo base_url(); ?>public/css/argon-dashboard.css?v=2.0.2" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/fontawesome/css/all.min.css">
    
    <style>
        /* Ajuste para que los botones de acceso rápido se vean bien en móviles */
        @media (max-width: 768px) {
            .nav-wrapper .nav-pills {
                flex-direction: column !important;
            }
            .nav-wrapper .nav-item {
                margin-bottom: 5px;
            }
            .nav-wrapper .nav-link {
                justify-content: flex-start !important;
                padding-left: 20px !important;
            }
        }
        
        /* Botones de navegación personalizados */
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: #219B9F !important;
            color: white !important;
        }
        
        /* Resaltar campos de solo lectura */
        .form-control[readonly] {
            background-color: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="g-sidenav-show bg-gray-100">
  <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-position: center; background-repeat: no-repeat; background-size: cover;">
    <span class="mask bg-gradient-info opacity-8"></span>
  </div>

  <div class="main-content position-relative">
    
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3 mt-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-8 text-white" href="javascript:;">Portal Paciente</a></li>
            <li class="breadcrumb-item text-sm text-white active font-weight-bold" aria-current="page">Mi Perfil</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0" style="font-size: 1.5rem;">Bienvenido(a)</h6>
        </nav>
        
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 justify-content-end" id="navbar">
          <ul class="navbar-nav">
            <li class="nav-item d-flex align-items-center">
              <a href="<?php echo base_url(); ?>cerrarsesionclientes" class="nav-link text-white font-weight-bold px-3 py-2 bg-danger rounded-pill shadow-sm">
                <i class="fas fa-sign-out-alt me-sm-1"></i>
                <span class="d-sm-inline d-none">Cerrar Sesión</span>
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li> 
          </ul>
        </div>
      </div>
    </nav>
    <div class="card shadow-lg mx-4 mt-4">
      <div class="card-body p-3">
        <div class="row gx-4 align-items-center">
            
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="<?php echo base_url(); ?>public/img/theme/team-41.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                <?php echo $this->session->userdata("apellido")." ".$this->session->userdata("nombre"); ?>
              </h5>
              <p class="mb-0 font-weight-bold text-sm text-primary">
                Paciente de ClinicSoft
              </p>
            </div>
          </div>
          
          <div class="col-lg-7 col-md-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-4 mt-lg-0">
            <div class="nav-wrapper position-relative end-0">
              <ul class="nav nav-pills nav-fill p-1 bg-gray-100 rounded-lg" role="tablist">
                
                <li class="nav-item" title="Historial Clínico">
                  <a target="_blank" class="nav-link mb-0 px-0 py-2 d-flex align-items-center justify-content-center disabled text-secondary" href="#">
                    <i class="fas fa-notes-medical fs-5"></i>
                    <span class="ms-2">Historia</span>
                  </a>
                </li>
                
                <li class="nav-item" title="Recetas Médicas">
                  <a target="_blank" class="nav-link mb-0 px-0 py-2 d-flex align-items-center justify-content-center disabled text-secondary" href="#">
                    <i class="fas fa-pills fs-5"></i>
                    <span class="ms-2">Recetas</span>
                  </a>
                </li>
                
                <li class="nav-item">
                  <a target="_blank" class="nav-link mb-0 px-0 py-2 d-flex align-items-center justify-content-center bg-white shadow-sm" href="<?php echo base_url(); ?>clientes/laboratorio">
                    <i class="fas fa-vial text-info fs-5"></i>
                    <span class="ms-2 font-weight-bold">Laboratorio</span>
                  </a>
                </li>
                
                <li class="nav-item">
                  <a target="_blank" class="nav-link mb-0 px-0 py-2 d-flex align-items-center justify-content-center bg-white shadow-sm mx-lg-2" href="<?php echo base_url(); ?>clientes/patologia">
                    <i class="fas fa-microscope text-warning fs-5"></i>
                    <span class="ms-2 font-weight-bold">Patología</span>
                  </a>
                </li>
                
                <li class="nav-item">
                  <a target="_blank" class="nav-link mb-0 px-0 py-2 d-flex align-items-center justify-content-center bg-white shadow-sm" href="<?php echo base_url(); ?>clientes/ecografias">
                    <i class="fas fa-x-ray text-primary fs-5"></i>
                    <span class="ms-2 font-weight-bold">Ecografías</span>
                  </a>
                </li>
                
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="container-fluid py-4">
      <div class="row">
          
        <div class="col-md-4 col-xl-3 mb-4">
          <div class="card card-profile h-100">
            <img src="<?php echo base_url(); ?>public/img/theme/bg-profile.jpg" alt="Fondo" class="card-img-top" style="height: 120px; object-fit: cover;">
            
            <div class="row justify-content-center">
              <div class="col-4 col-lg-4 order-lg-2">
                <div class="mt-n4 mt-lg-n5 mb-4 mb-lg-0 text-center">
                  <a href="javascript:;">
                    <img src="<?php echo base_url(); ?>public/img/theme/team-41.jpg" class="rounded-circle img-fluid border border-4 border-white shadow">
                  </a>
                </div>
              </div>
            </div>
            
            <div class="card-body pt-0 text-center mt-3">
              <h5 class="mb-0">
                <?php echo $this->session->userdata("nombre"); ?> <span class="font-weight-light"><?php echo $this->session->userdata("apellido"); ?></span>
              </h5>
              
              <div class="d-flex justify-content-center align-items-center mt-2 mb-4">
                  <i class="fas fa-circle text-success text-xs me-2"></i> <span class="text-sm font-weight-bold text-success">Sesión Activa</span>
              </div>
              
              <div class="row border-top border-bottom py-3 mb-4 bg-gray-50">
                <div class="col-4">
                  <div class="d-grid text-center">
                    <span class="text-xl font-weight-bolder text-info">0</span>
                    <span class="text-xs opacity-8 text-uppercase">Laboratorio</span>
                  </div>
                </div>
                <div class="col-4 border-start border-end">
                  <div class="d-grid text-center">
                    <span class="text-xl font-weight-bolder text-warning">0</span>
                    <span class="text-xs opacity-8 text-uppercase">Patología</span>
                  </div>
                </div>
                <div class="col-4">
                  <div class="d-grid text-center">
                    <span class="text-xl font-weight-bolder text-primary">0</span>
                    <span class="text-xs opacity-8 text-uppercase">Ecografías</span>
                  </div>
                </div>
              </div>
              
              <div class="text-start px-3">
                <div class="h6 font-weight-300 text-sm mb-3">
                  <i class="fas fa-calendar-alt text-muted me-2"></i> Fecha actual: <span class="font-weight-bold text-dark"><?php echo date("d-m-Y"); ?></span>
                </div>
                <div class="h6 font-weight-300 text-sm mb-3">
                  <i class="fas fa-map-marker-alt text-muted me-2"></i> <?php echo $this->session->userdata("direccion") ? $this->session->userdata("direccion") : 'Sin dirección registrada'; ?>
                </div>
                <div class="h6 font-weight-300 text-sm">
                  <i class="fas fa-briefcase text-muted me-2"></i> <?php echo $this->session->userdata("ocupacion") ? $this->session->userdata("ocupacion") : 'Ocupación no especificada'; ?>
                </div>
              </div>
              
            </div>
          </div>
        </div>
        
        <div class="col-md-8 col-xl-9">
          <div class="card shadow-sm h-100">
            <div class="card-header pb-0 bg-transparent border-bottom">
              <div class="d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase"><i class="fas fa-user-edit me-2 text-primary"></i> Mi Información Personal</h6>
                <button class="btn btn-primary btn-sm ms-auto mb-0"><i class="fas fa-save me-1"></i> Actualizar Datos</button>
              </div>
            </div>
            
            <div class="card-body">
              <p class="text-muted text-sm mb-4">Manten tus datos de contacto actualizados para recibir tus notificaciones médicas correctamente.</p>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Documento / Usuario <i class="fas fa-lock text-danger ms-1" title="Dato bloqueado por seguridad"></i></label>
                    <input class="form-control" type="text" value="<?php echo $this->session->userdata("documento"); ?>" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Estado del Paciente <i class="fas fa-lock text-danger ms-1" title="Dato bloqueado por seguridad"></i></label>
                    <input class="form-control font-weight-bold text-success" type="text" value="Activo" readonly>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Nombres <i class="fas fa-lock text-danger ms-1"></i></label>
                    <input class="form-control" type="text" value="<?php echo $this->session->userdata("nombre"); ?>" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Apellidos <i class="fas fa-lock text-danger ms-1"></i></label>
                    <input class="form-control" type="text" value="<?php echo $this->session->userdata("apellido"); ?>" readonly>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Fecha de nacimiento <i class="fas fa-lock text-danger ms-1"></i></label>
                    <input class="form-control" type="date" value="" readonly>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label text-primary">Correo electrónico <i class="fas fa-pencil-alt ms-1"></i></label>
                    <input class="form-control" type="email" placeholder="ejemplo@correo.com">
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label text-primary">Teléfono / Celular <i class="fas fa-pencil-alt ms-1"></i></label>
                    <input class="form-control" type="text" placeholder="Ej. 999 888 777">
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label text-primary">Dirección actual <i class="fas fa-pencil-alt ms-1"></i></label>
                    <input class="form-control" type="text" value="<?php echo $this->session->userdata("direccion"); ?>" placeholder="Ej. Av. Los Pinos 123">
                  </div>
                </div>
                
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url(); ?>public/js/core/popper.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/core/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/plugins/smooth-scrollbar.min.js"></script>
  </body>
</html>