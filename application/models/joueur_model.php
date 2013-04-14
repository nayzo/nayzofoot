<?php
class Joueur_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_all(){
        $this->db->select('*');
        $this->db->from('joueur');
        $this->db->join('equipe', 'joueur.id = equipe.id');
        $query = $this->db->get();
        return $query->result;
    }
    
    function save_joueur(){
            $data = array(
            'nom' => $this->input->post('nom'),
            'equipe'    => $this->input->post('equipe'),
            'date_naissance'      => $this->input->post('date_naissance'),            
            'ville' => $this->input->post('ville'),
            'date_affectation' => $this->input->post('date_affectation'),
            'post' => $this->input->post('post')
        );

        $this->db->insert('joueur', $data);
    }
    
    
    function get_equipe($id){
        $this->db->select('*')
                ->from('equipe')
                ->where('id', $id)
                ->limit(1);
        $query = $this->db->get();
        if($query->num_rows()==1)
            return $query->result();
        else
            return false;
    }
}