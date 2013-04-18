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
        return $query->result();
    }
    
    function add_joueur($photo){
            $data = array(
            'nom' => $this->input->post('nom'),
            'taille' => $this->input->post('taille'),
            'poids' => $this->input->post('poids'),
            'photo' => $photo,    
            'equipe'    => $this->input->post('equipe'),
            'date_naissance'      => $this->input->post('date_naissance'),            
            'ville' => $this->input->post('ville'),
            'date_affectation' => $this->input->post('date_affectation'),
            'post' => $this->input->post('post')
        );

        $this->db->insert('joueur', $data);
    }
    
    function update_joueur($id){
            $data = array(
            'nom' => $this->input->post('nom'),
            'taille' => $this->input->post('taille'),
            'poids' => $this->input->post('poids'),  
            'equipe'    => $this->input->post('equipe'),
            'date_naissance'      => $this->input->post('date_naissance'),            
            'ville' => $this->input->post('ville'),
            'date_affectation' => $this->input->post('date_affectation'),
            'post' => $this->input->post('post')
        );
        $this->db->where('id', $id);     
        $this->db->update('joueur', $data);
    }
    
    function update_joueurphoto($tab){
       
            $data = array(
            'nom' => $this->input->post('nom'),
            'taille' => $this->input->post('taille'),
            'poids' => $this->input->post('poids'),
            'photo' => $tab['photo'],    
            'equipe'    => $this->input->post('equipe'),
            'date_naissance'      => $this->input->post('date_naissance'),            
            'ville' => $this->input->post('ville'),
            'date_affectation' => $this->input->post('date_affectation'),
            'post' => $this->input->post('post')
        );
        $this->db->where('id', $tab['id']);     
        $this->db->update('joueur', $data);
    }
       
    function get_joueur($id){
        
        $this->db->select('*');
        $this->db->from('equipe');
        $this->db->join('joueur', 'joueur.equipe = equipe.id')->where('joueur.id', $id);
        return $this->db->get();   
    }
    
    function get_joueur_by_equipe($idequipe){
        
        $this->db->select('*');
        $this->db->from('joueur');
        $this->db->where('equipe', $idequipe);
        $query = $this->db->get();
        return $query->result();
    }
    
    function delete_joueur($id){
        $this->db->where('id', $id);     
        $this->db->delete('joueur');
    }
    
    function delete_joueur_by_equipe($id){
        $this->db->where('equipe', $id);     
        $this->db->delete('joueur');
    }
    
    function get_all_by_equipe($id){
        $this->db->select('photo');
        $this->db->from('joueur');
        $this->db->where('equipe', $id);
        $query = $this->db->get();
        return $query->result();
    }
}
