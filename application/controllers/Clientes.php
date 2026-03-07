<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model("Patologia_model");
		$this->load->model("Laboratorio_model");
		$this->load->model("Lineatiempo_model");
		$this->load->model("Historias_model");
	}

    public function index() {
        $this->load->view("login_clientes");
    }

	public function inicio(){
		// $lineas = $this->Lineatiempo_model->getLineaClientes();
		$data = [
			// "linea" => $lineas
		];
		$this->load->view("clientes/inicio", $data);
	}
	
	public function laboratorio() {
	  $laboratorios = $this->Laboratorio_model->getDocumentosLaboratorioclientes();
	  $data = [
		"laboratorio" => $laboratorios
	  ];
	  $this->load->view("clientes/laboratorio", $data);
	}

	public function patologia() {
		$patologias = $this->Patologia_model->getDocumentosPatologiaclientes();
		$data = [
			"patologia" => $patologias
		  ];
		$this->load->view("clientes/patologia", $data);
	}

	public function ecografias() {
		if(!$this->session->userdata("login")){ redirect(base_url()."iniciarsesion"); }

		$documento = $this->session->userdata("documento");

		$data = [
			'ecoAbdominales' => $this->Historias_model->getEcografiaAbdominal($documento),
			'ecoMamas' => $this->Historias_model->getEcografiaMama($documento),
			'ecoGeneticas' => $this->Historias_model->getEcografiaGenetica($documento),
			'ecoMorfologicas' => $this->Historias_model->getEcografiaMorfologica($documento),
			'ecoTrasvaginals' => $this->Historias_model->getEcografiaTrasvaginal($documento),
			'ecoPelvicas' => $this->Historias_model->getEcografiaPelvica($documento),
			'ecoObstetricas' => $this->Historias_model->getEcografiaObstetrica($documento),
			'ecoProstaticas' => $this->Historias_model->getEcografiaProstatica($documento),
			'ecoRenals' => $this->Historias_model->getEcografiaRenal($documento),
			'ecoTiroidess' => $this->Historias_model->getEcografiaTiroides($documento),
			'ecoHisterosonografias' => $this->Historias_model->getEcografiaHisterosonografia($documento),
			'ecoArterials' => $this->Historias_model->getEcografiaArterial($documento),
			'ecoVenosas' => $this->Historias_model->getEcografiaVenosa($documento),
		];

		$this->load->view("clientes/ecografias", $data);
	}

	public function citasDoctores() {
	  $this->load->view("clientes/citas_doctores");
	}

	public function reservarCita() {
		$this->load->model("Doctores_model");
		$doctores = $this->Doctores_model->getDoctores();
		$data = ["doctor" => $doctores];
		$this->load->view("web/reservar_cita", $data);
	}
}