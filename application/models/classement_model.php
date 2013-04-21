<?php

class Classement_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function add_classement($id) {
        $this->load->model('equipe_model');
        $equipes = $this->equipe_model->get_all();
        foreach ($equipes as $eq) {
            $data['saison'] = $id;
            $data['equipe'] = $eq->id;
            $this->db->insert('classement', $data);
        }
    }

    function get_all_championnat($saison) {
        $this->db->select('*');
        $this->db->from('equipe');
        $this->db->join('classement', 'equipe.id = classement.equipe')->where('classement.saison', $saison)->order_by('nb_point_championnat', 'asc');
        $query = $this->db->get();
        return $query->result;
    }

    function get_all_coupe($saison) {  
        $this->db->select('*');
        $this->db->from('equipe');
        $this->db->join('classement', 'equipe.id = classement.equipe')->where('classement.saison', $saison)->order_by('nb_point_coupe', 'asc');
        $query = $this->db->get();
        return $query->result;
    }
    
    function get_if_one_saison_courant()
    {
        $this->db->where('saison_courant', 1);     
        $query = $this->db->get('saison');
        
        if($query->num_rows() == 1)
            return true;
        else
            return false;
    }
    
     function delete_classement_equipe($id){
        $this->db->where('equipe', $id);     
        $this->db->delete('classement');
    }
    
    function delete_classement_saison($id){
        $this->db->where('saison', $id);     
        $this->db->delete('classement');
    }
    
    function add_point_championnat($equipe, $val, $saisin)
    {
        $this->db->where('saison', $saisin);
        $this->db->where('equipe', $equipe);
        $this->db->set('nb_point_championnat', "nb_point_championnat+$val", FALSE);
        $this->db->update('classement');
    }
    
    function add_point_coupe($equipe, $val, $saisin)
    {
        $this->db->where('saison', $saisin);
        $this->db->where('equipe', $equipe);
        $this->db->set('nb_point_coupe', "nb_point_coupe+$val", FALSE);
        $this->db->update('classement');
    }
}