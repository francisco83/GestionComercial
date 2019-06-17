<?php
    class ProductoModel extends CI_Model{

		// Declare variables
		private $_limit;
		private $_pageNumber;
		private $_offset;

        public $id;
        public $codigo;
        public $descripcion;
        public $precioVenta;
        public $precioCompra;
        public $existencia;

        public function __construct(){
            $this->load->database();
		}
		
		public function setLimit($limit) {
            $this->_limit = $limit;
        }
 
        public function setPageNumber($pageNumber) {
            $this->_pageNumber = $pageNumber;
        }
 
        public function setOffset($offset) {
            $this->_offset = $offset;
		}
		
			// Count all record of table "employee" in database.
			public function getAllEmployeeCount() {
				$this->db->from('productos');
				return $this->db->count_all_results();
			}


        public function nuevo($codigo, $descripcion, $precioVenta, $precioCompra, $existencia){
            $this->codigo = $codigo;
            $this->descripcion = $descripcion;
            $this->precioVenta = $precioVenta;
            $this->precioCompra = $precioCompra;
            $this->existencia = $existencia;
            return $this->db->insert('productos', $this);
        }

        public function guardarCambios($id, $codigo, $descripcion, $precioVenta, $precioCompra, $existencia){
            $this->id = $id;
            $this->codigo = $codigo;
            $this->descripcion = $descripcion;
            $this->precioVenta = $precioVenta;
            $this->precioCompra = $precioCompra;
            $this->existencia = $existencia;
            return $this->db->update('productos', $this, array("id" => $id));
        }

        public function todos(){
			//$this->db->limit("5", "2");
			//$this->db->limit($this->_pageNumber, $this->_offset);
            return $this->db->get("productos")->result();
		}
		
		public function todosNew($limit, $start){
			$this->db->limit($limit, $start);
			$query = $this->db->get("productos");
			return $query->result();
			
        }


        public function eliminar($id){
            return $this->db->delete("productos", array("id" => $id));
        }

        public function uno($id){
            return $this->db->get_where("productos", array("id" => $id))->row();
        }

        public function porCodigoDeBarras($codigoDeBarras){
            return $this->db->get_where("productos", array("codigo" => $codigoDeBarras))->row();
		}
		
		    // Fetch data according to per_page limit.
			public function employeeList() {       
				$this->db->select(array('e.id', 'e.codigo', 'e.descripcion', 'e.precioVenta', 'e.precioCompra','existencia'));
				$this->db->from('productos as e');          
				$this->db->limit($this->_pageNumber, $this->_offset);
				$query = $this->db->get();
				return $query->result_array();
			}
    }
?>
