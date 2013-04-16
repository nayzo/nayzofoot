<?php
class Saison_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    public function get_all(){
        $query = $this->db->get('saison');
        return $query->result();
    }
    
    function add_saison(){
            $data = array(
            'saison' => $this->input->post('saison'),
        );
        $this->db->insert('saison', $data);
        return  $this->db->insert_id()  ;
    }
    
    function setsaison_courant($id){
            $data = array(
            'saison_courant' => 1,
        );
        $this->db->where('id', $id);     
        $this->db->update('saison', $data);
        
        $all = $this->get_all();
        foreach($all as $one)
        {
            if($one->id != $id)
            {
                $data = array(
                'saison_courant' => 0,
                );
                $this->db->where('id', $one->id);     
                $this->db->update('saison', $data);
            }    
        }
    }    
    
    function update_saison($id){
            $data = array(
            'saison' => $this->input->post('saison'),
        );
        $this->db->where('id', $id);     
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
        $this->db->select('*')
                ->from('saison')
                ->where('saison_courant', 1)
                ->limit(1);
        return $this->db->get();
    }
    
    function delete_saison($id){
        $this->db->where('id', $id);     
        $this->db->delete('saison');
  
                 $this->db->where('saison', $id);     
                 $this->db->delete('classement');
  
    }
}