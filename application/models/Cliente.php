<?php
    class Cliente extends CI_Model{
        public $id;
        public $dni;
        public $apellido;
        public $nombre;
        public $direccion;
        public $telefono;
 
        public function __construct(){
            $this->load->database();
        }
 
        public function nuevo($dni, $apellido, $nombre, $direccion, $telefono){
            $this->dni = $dni;
            $this->apellido = $apellido;
            $this->nombre = $nombre;
            $this->direccion = $direccion;
            $this->telefono = $telefono;
            return $this->db->insert('productos', $this);
        }
 
        public function guardarCambios($id, $dni, $apellido, $nombre, $direccion, $telefono){
            $this->id = $id;
            $this->dni = $dni;
            $this->apellido = $apellido;
            $this->nombre = $nombre;
            $this->direccion = $direccion;
            $this->telefono = $telefono;
            return $this->db->update('productos', $this, array("id" => $id));
        }
 
        public function todos(){
            return $this->db->get("productos")->result();
        }
 
        public function eliminar($id){
            return $this->db->delete("productos", array("id" => $id));
        }
 
        public function uno($id){
            return $this->db->get_where("productos", array("id" => $id))->row();
        }
 
        public function pordniDeBarras($codigoDeBarras){
            return $this->db->get_where("productos", array("dni" => $codigoDeBarras))->row();
        }
    }
?>
