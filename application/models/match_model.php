<?php
class Match_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_all(){
        $query = $this->db->get('saison');
            return $query->result();
    }
    
    function add_saison(){
            $data = array(
            'saison' => $this->input->post('saison')
        );
        $this->db->insert('saison', $data);
        return  $this->db->insert_id()  ;
    }
    
    function update_saison(){
            $data = array(
            'saison' => $this->input->post('saison'),
        );
        $this->db->update('saison', $data);
    }    
    
    function get_saison($id){
        $this->db->select('*')
                ->from('saison')
                ->where('id', $id)
                ->limit(1);
        return $this->db->get();
    }
    
    function get_saison_courant(){
        $this->db->select('id')
                ->from('saison')
                ->where('saison_courant', 1)
                ->limit(1);
        return $this->db->get();
    }
}