<?php
class Classement_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_all(){
        $query = $this->db->get('saison');
            return $query->result();
    }
  
    
    function add_classement($id){
        $this->load->model('equipe_model');
        $equipes = $this->equipe_model->get_all();
        foreach($equipes as $eq) 
        {
            $data['saison'] = $id;
            $data['equipe'] = $eq->id;
            $this->db->insert('classement', $data);
        }
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
        $query = $this->db->get();
        if($query->num_rows()==1)
            return $query->result();
        else
            return false;
    }
}