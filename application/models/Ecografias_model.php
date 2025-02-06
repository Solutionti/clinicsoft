<?php

class Ecografias_model extends CI_model {

    //ECOGRAFIAS DE MAMA 
    public function createEcografiaMama($data) {
      $datos = [
<<<<<<< HEAD
        "documento_paciente" => $data["documento"],
        "doctor" => $data["doctor"],
        "pezon_izq" => $data["pezon_izq"],
        "tcsc_izq" => $data["tscs_izq"],
        "glandular_izq" => $data["glandular_izq"],
        "axila_izq" => $data["axila_izq"],
        "comentario_izq" => $data["comentario_izq"],
        "pezon_dere" => $data["pezon_dere"],
        "tcsc_dere" => $data["tscs_dere"],
        "glandular_dere" => $data["glandular_dere"],
        "axila_dere" => $data["axila_dere"],
        "comentario_dere" => $data["comentario_dere"],
        "conclusion" => $data["conclusion"],
        "sugerencias" => $data["sugerencias"],
        "fecha" => date("Y-m-d"),
=======
        "documento_paciente" => $data["documento_paciente"],
        "codigo_doctor" => $data["codigo_doctor"],
        "pezon_izq" => $data["pezon_izq"],
        "tcsc_izq" => $data["tcsc_izq"],
        "tejido_glandular_izq" => $data["tejido_glandular_izq"],
        "axila_izq" => $data["axila_izq"],
        "comentario_mama_izq" => $data["comentario_mama_izq"],
        "pezon_der" => $data["pezon_der"],
        "tcsc_der" => $data["tcsc_der"],
        "tejido_glandular_der" => $data["tejido_glandular_der"],
        "axila_der" => $data["axila_der"],
        "comentario_der" => $data["comentario_der"],
        "conclusion_mama" => $data["conclusion_mama"],
        "sugerencias_mama" => $data["sugerencias_mama"],
        "fecha" => date("Y-m-d"),
        "hora" => date("h:i A"),
>>>>>>> cd293fed287ac25c35f3662c0af1615de000b5a2
        "usuario" => $this->session->userdata("nombre"),
      ];
      $this->db->insert("ecografia_mama", $datos);
    }

<<<<<<< HEAD
=======
    // ECOGRAFIA TRANSVAGINAL

    public function createEcografiaTransvaginal($data) {
      $datos = [
        "documento_paciente" => $data["documento_paciente"],
        "codigo_doctor" => $data["codigo_doctor"],
        "fecha" => date("Y-m-d"),
        "hora" => date("h:i A"),
        "usuario" => $this->session->userdata("nombre"),
      ];
    }

    // ECOGRAFIA PELVICA

    public function createEcografiaPelvica($data) {
      $datos = [
        "documento_paciente" => $data["documento_paciente"],
        "codigo_doctor" => $data["codigo_doctor"],
        "fecha" => date("Y-m-d"),
        "hora" => date("h:i A"),
        "usuario" => $this->session->userdata("nombre"),
      ];
    }

    // ECOGRAFIA MORFOLOGICA

    public function createEcografiaMorfologica($data) {
      $datos = [
        "documento_paciente" => $data["documento_paciente"],
        "codigo_doctor" => $data["codigo_doctor"],
        "fecha" => date("Y-m-d"),
        "hora" => date("h:i A"),
        "usuario" => $this->session->userdata("nombre"),
      ];
    }

    // ECOGRAFIA GENETICA

    public function createEcografiaGenetica($data) {
      $datos = [
        "documento_paciente" => $data["documento_paciente"],
        "codigo_doctor" => $data["codigo_doctor"],
        "fecha" => date("Y-m-d"),
        "hora" => date("h:i A"),
        "usuario" => $this->session->userdata("nombre"),
      ];
    }

    // ECOGRAFIA OBSTETRICA

    public function createEcografiaObstetrica($data) {
      $datos = [
        "documento_paciente" => $data["documento_paciente"],
        "codigo_doctor" => $data["codigo_doctor"],
        "fecha" => date("Y-m-d"),
        "hora" => date("h:i A"),
        "usuario" => $this->session->userdata("nombre"),
      ];
    }

>>>>>>> cd293fed287ac25c35f3662c0af1615de000b5a2
    public function subirDocumentoEcografias($data) {

      $datos = [
          "paciente" => $data["paciente"],
          "titulo" => $data["titulo"],
          "url_documento" => $data["icono"],
          "tp_documento" => "ecografias",
          "fecha" => date("Y-m-d")
      ];
      $this->db->insert("documentos_pacientes", $datos);
  }

    
}