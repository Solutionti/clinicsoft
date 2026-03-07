<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecografías | ClinicSoft</title>
    <link rel="icon" href="<?php echo base_url(); ?>public/img/theme/logo2.ico" type="image/ico" />
    <link id="pagestyle" href="<?php echo base_url(); ?>public/css/argon-dashboard.css?v=2.0.2" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>public/css/overhang.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/fontawesome/css/all.min.css">
    <style>
        /* ===== ESTILOS PARA LAS TARJETAS DE RESULTADOS ===== */
        .result-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
            background-color: #fff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            border-color: #5e72e4; /* Color Índigo (Primary) para ecografías */
        }

        .result-card .icon-box {
            background: rgba(94, 114, 228, 0.1); /* Índigo transparente */
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5e72e4;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .result-card .card-body {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .result-card .btn-download {
            background-color: #f8f9fa;
            color: #344767;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
            margin-top: auto; 
        }

        .result-card:hover .btn-download {
            background-color: #5e72e4;
            color: white;
            border-color: #5e72e4;
        }

        /* Banner de cabecera temático (Imagen médica/Ecografía) */
        .header-banner {
            background-image: url('https://images.unsplash.com/photo-1559757175-5700dde675bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-position: center 40%;
            background-size: cover;
        }
        
        /* Botón volver */
        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .btn-back:hover {
            background: white;
            color: #344767;
        }

        /* Títulos de sección */
        .section-title {
            font-size: 1.1rem;
            color: #344767;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            margin-top: 1rem;
            font-weight: 700;
        }
    </style>
</head>
<body class="g-sidenav-show bg-gray-100">
  
  <div class="position-absolute w-100 min-height-300 top-0 header-banner">
    <span class="mask bg-gradient-primary opacity-7"></span>
  </div>
  
  <div class="main-content position-relative max-height-vh-100 h-100">
    
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3 mt-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-8 text-white" href="<?php echo base_url(); ?>clientes/perfil">Portal Paciente</a></li>
            <li class="breadcrumb-item text-sm text-white active font-weight-bold" aria-current="page">Ecografías</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0" style="font-size: 1.5rem;">Mis Ecografías</h6>
        </nav>
        
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 justify-content-end" id="navbar">
          <ul class="navbar-nav">
            <li class="nav-item d-flex align-items-center me-3">
              <a href="<?php echo base_url(); ?>clientes/inicio" class="nav-link btn-back font-weight-bold px-3 py-2 rounded-pill shadow-sm">
                <i class="fas fa-arrow-left me-sm-1"></i>
                <span class="d-sm-inline d-none">Volver al Perfil</span>
              </a>
            </li>
            <li class="nav-item d-flex align-items-center">
              <a href="<?php echo base_url(); ?>cerrarsesionclientes" class="nav-link text-white font-weight-bold px-0">
                <i class="fas fa-sign-out-alt me-sm-1"></i>
                <span class="d-sm-inline d-none">Cerrar Sesión</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  
    <div class="card shadow-lg mx-4 mt-4 mb-5">
      <div class="card-body p-4">
          
        <div class="row align-items-center mb-4 pb-3 border-bottom">
          <div class="col-auto">
            <div class="icon-shape bg-gradient-primary text-white rounded-circle shadow-sm text-center d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
              <i class="fas fa-wave-square fs-3"></i>
            </div>
          </div>
          <div class="col">
            <h4 class="mb-0 text-dark">Informes Ecográficos</h4>
            <p class="text-sm text-muted mb-0">
                Paciente: <span class="font-weight-bold"><?php echo $this->session->userdata("apellido")." ".$this->session->userdata("nombre"); ?></span>
            </p>
          </div>
        </div>

        <div class="row">
          <?php
            $ecografias_config = [
                [
                    'data' => $ecoAbdominales,
                    'title' => 'Ecografía Abdominal',
                    'icon' => 'fas fa-procedures',
                    'url_segment' => 'pdfecografiaabdominal'
                ],
                [
                    'data' => $ecoMamas,
                    'title' => 'Ecografía de Mama',
                    'icon' => 'fas fa-female',
                    'url_segment' => 'pdfecografiamama'
                ],
                [
                    'data' => $ecoGeneticas,
                    'title' => 'Ecografía Genética',
                    'icon' => 'fas fa-dna',
                    'url_segment' => 'pdfecografiagenetica'
                ],
                [
                    'data' => $ecoMorfologicas,
                    'title' => 'Ecografía Morfológica',
                    'icon' => 'fas fa-baby',
                    'url_segment' => 'pdfecografiamorfologica'
                ],
                [
                    'data' => $ecoTrasvaginals,
                    'title' => 'Ecografía Transvaginal',
                    'icon' => 'fas fa-female',
                    'url_segment' => 'pdfecografiatrasvaginal'
                ],
                [
                    'data' => $ecoPelvicas,
                    'title' => 'Ecografía Pélvica',
                    'icon' => 'fas fa-procedures',
                    'url_segment' => 'pdfecografiapelvica'
                ],
                [
                    'data' => $ecoObstetricas,
                    'title' => 'Ecografía Obstétrica',
                    'icon' => 'fas fa-baby',
                    'url_segment' => 'pdfecografiaobstetrica'
                ],
                [
                    'data' => $ecoProstaticas,
                    'title' => 'Ecografía Prostática',
                    'icon' => 'fas fa-mars',
                    'url_segment' => 'pdfecografiaprostatica'
                ],
                [
                    'data' => $ecoRenals,
                    'title' => 'Ecografía Renal',
                    'icon' => 'fas fa-procedures',
                    'url_segment' => 'pdfecografiarenal'
                ],
                [
                    'data' => $ecoTiroidess,
                    'title' => 'Ecografía Tiroides',
                    'icon' => 'fas fa-user-md',
                    'url_segment' => 'pdfecografiatiroides'
                ],
                [
                    'data' => $ecoHisterosonografias,
                    'title' => 'Histerosonografía',
                    'icon' => 'fas fa-female',
                    'url_segment' => 'pdfecografiahisterosonografia'
                ],
                [
                    'data' => $ecoArterials,
                    'title' => 'Ecografía Arterial',
                    'icon' => 'fas fa-heartbeat',
                    'url_segment' => 'pdfecografiaarterial'
                ],
                [
                    'data' => $ecoVenosas,
                    'title' => 'Ecografía Venosa',
                    'icon' => 'fas fa-heartbeat',
                    'url_segment' => 'pdfecografiavenosa'
                ]
            ];

            $found_any = false;
            foreach ($ecografias_config as $config) {
                if ($config['data'] && $config['data']->num_rows() > 0) {
                    $found_any = true;
                    foreach ($config['data']->result() as $row) {
          ?>
          <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
            <div class="result-card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="icon-box"><i class="<?php echo $config['icon']; ?>"></i></div>
                  <span class="badge bg-success-soft text-success border border-success px-2 py-1 rounded-pill" style="font-size: 0.65rem;"><i class="fas fa-check-circle me-1"></i> Listo</span>
                </div>
                <h6 class="text-dark font-weight-bolder text-uppercase text-sm mb-1 mt-2" title="<?php echo $config['title']; ?>"><?php echo $config['title']; ?></h6>
                <div class="d-flex align-items-center text-muted text-xs mb-3">
                  <i class="far fa-calendar-alt me-2"></i><span>Realizado: <strong><?php echo isset($row->fecha) ? date('d-m-Y', strtotime($row->fecha)) : date("d-m-Y"); ?></strong></span>
                </div>
                <a target="_blank" href="<?php echo base_url(); ?>administracion/<?php echo $config['url_segment']; ?>/<?php echo $row->documento_paciente; ?>" class="btn btn-download w-100 mb-0 d-flex justify-content-center align-items-center">
                  <i class="fas fa-file-pdf text-danger fs-5 me-2"></i> Ver Informe
                </a>
              </div>
            </div>
          </div>
          <?php 
                    }
                }
            }

            if (!$found_any) {
          ?>
            <div class="col-12 text-center py-5">
                <div class="icon-shape bg-gray-200 text-muted rounded-circle shadow-none mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-wave-square fs-2"></i>
                </div>
                <h5 class="text-muted">Aún no hay informes ecográficos disponibles</h5>
                <p class="text-sm">Tus ecografías aparecerán aquí cuando estén listas.</p>
            </div>
          <?php } ?>
        </div>
        
      </div>
    </div>
  </div>

  <script>var baseurl = "<?php echo base_url();?>";</script>
  <script src="<?php echo base_url(); ?>public/js/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/core/popper.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/core/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/plugins/smooth-scrollbar.min.js"></script>
</body>
</html>