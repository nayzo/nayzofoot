<?php
class Equipe_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_all(){
        $query = $this->db->get('equipe');
            return $query->result();
    }
    
}