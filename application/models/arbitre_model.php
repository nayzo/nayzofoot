<?php
class Arbitre_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_all(){
        $query = $this->db->get('arbitre');
            return $query->result();
    }
    
    function add_arbitre(){
            $data = array(
            'nom' => $this->input->post('nom'),
            'description'    => $this->input->post('description')
        );
        $this->db->insert('arbitre', $data);
    }
    
    function update_arbitre(){
           $data = array(
            'nom' => $this->input->post('nom'),
            'description'    => $this->input->post('description')
        );
        $this->db->update('arbitre', $data);
    }
    
    
    function get_arbitre($id){
        $this->db->select('*')
                ->from('arbitre')
                ->where('id', $id)
                ->limit(1);
        return $this->db->get();
    }
}