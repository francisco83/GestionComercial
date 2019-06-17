<?php

class Authors_model extends CI_Model {

    protected $table = 'authors';

    public function __construct() {
		//parent::__construct();
		$this->load->database();
    }

    public function get_count() {
        return $this->db->count_all($this->table);
    }

    public function get_authors($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);

        return $query->result();
    }
}
