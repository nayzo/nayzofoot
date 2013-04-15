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
    
    function setsaison_courante($id){
            $data = array(
            'saison_courante' => 1,
        );
        $this->db->where('id', $id);     
        $this->db->update('saison', $data);
        
        $all = $this->get_all();
        foreach($all as $one)
        {
            if($one->id != $id)
            {
                $data = array(
                'saison_courante' => 0,
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
    
    function get_saison_courante(){
        $this->db->select('id')
                ->from('saison')
                ->where('saison_courante', 1)
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