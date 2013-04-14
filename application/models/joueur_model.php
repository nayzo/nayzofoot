<?php
class Joueur_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_all(){
        $this->db->select('*');
        $this->db->from('equipe');
        $this->db->join('joueur', 'joueur.equipe = equipe.id');
        $query = $this->db->get();
        return $query->result;
    }
    
    function add_joueur(){
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
    
    function update_joueur(){
            $data = array(
            'nom' => $this->input->post('nom'),
            'equipe'    => $this->input->post('equipe'),
            'date_naissance'      => $this->input->post('date_naissance'),            
            'ville' => $this->input->post('ville'),
            'date_affectation' => $this->input->post('date_affectation'),
            'post' => $this->input->post('post')
        );

        $this->db->update('joueur', $data);
    }
    
    
    function get_joueur($id){
        $this->db->select('*')
                ->from('joueur')
                ->where('id', $id)
                ->limit(1);
        $query = $this->db->get();
        if($query->num_rows()==1)
            return $query->result();
        else
            return false;
    }
}