<?php

class Login_model extends CI_model {

    public function iniciarSesion($correo, $password) {
        $this->db->select("*");
        $this->db->from("doctores");
        $this->db->where("email", $correo);
        $this->db->or_where("usuario", $correo);
        $this->db->where("estado", "Activo");
        $resultado = $this->db->get();

        if($resultado->num_rows() > 0){
            $contador = 0;
            $data = [];

            foreach($resultado->result() as $value){
                $passAct = $value->password;
                if(password_verify($password, $passAct)){
                    $contador ++;
                    $data = $value;
                }
            }
            if($contador == 1) {
                return $data;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

   public function iniciarSesionClientes($documento, $password) {
    // 1. Buscamos al paciente SOLO por su documento (DNI)
    $this->db->select("*");
    $this->db->from("pacientes");
    $this->db->where("documento", $documento);
    $resultado = $this->db->get(); 

    if ($resultado->num_rows() > 0) {
        $paciente = $resultado->row();

        // 2. VERIFICAMOS LA CONTRASEÑA
        // Usamos password_verify para comparar lo que escribió el usuario 
        // contra el código encriptado de la base de datos.
        if (password_verify($password, $paciente->password)) {
            return $paciente; // ¡Contraseña Correcta!
        }
        
        // OPCIONAL: Mantenemos compatibilidad con contraseñas viejas (si aún tienes usuarios sin encriptar)
        // Si la verificación anterior falló, miramos si es igual en texto plano
        else if ($paciente->password == $password) {
             // Aquí podrías aprovechar para encriptarla automáticamente si quisieras
             return $paciente;
        }
    }

    // Si no encontramos al usuario o la contraseña no coincide
    return false;
}
}

?>