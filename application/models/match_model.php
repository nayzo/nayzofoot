<?php
class Match_model extends CI_Model {
    
    private $table = 'match';

    function __construct()
    {
        parent::__construct();
    }
    
    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }
    
    function add_match(){
            $data = array(
            'type' => $this->input->post('type'),
            'saison'    => $this->input->post('saison'),           
            'categorie' => $this->input->post('categorie'),
            'date_match' => $this->input->post('date_match'),
            'heur_match' => $this->input->post('heur_match'),    
            'stade' => $this->input->post('stade'),          
            'arbitre1' =>  $this->input->post('arbitre1'),   
            'arbitre2' =>  $this->input->post('arbitre2'),   
            'arbitre3' =>  $this->input->post('arbitre3'),   
            'arbitre4' =>  $this->input->post('arbitre4'),   
            'equipe_visit' => $this->input->post('equipe_visit'),
            'equipe_recev' => $this->input->post('equipe_recev')       
        );

        $this->db->insert('match', $data);
    }
    
    function update_match($id){
            $data = array(
            'type' => $this->input->post('type'),
            'saison'    => $this->input->post('saison'),           
            //'categorie' => $this->input->post('categorie'),
            'date_match' => $this->input->post('date_match'),
            'heur_match' => $this->input->post('heur_match'),        
            'stade' => $this->input->post('stade'),   
            'arbitre1' =>  $this->input->post('arbitre1'),   
            'arbitre2' =>  $this->input->post('arbitre2'),   
            'arbitre3' =>  $this->input->post('arbitre3'),   
            'arbitre4' =>  $this->input->post('arbitre4')   
            //'equipe_visit' => $this->input->post('equipe_visit'),
            //'equipe_recev' => $this->input->post('equipe_recev')       
        );
        $this->db->where('id', $id);        
        $this->db->update('match', $data);
    }
    
    function update_match_after_resulat($id){
            $data = array(
            'type' => $this->input->post('type'),
            //'saison'    => $this->input->post('saison'),           
            //'categorie' => $this->input->post('categorie'),
            'date_match' => $this->input->post('date_match'),
            'heur_match' => $this->input->post('heur_match'),        
            'stade' => $this->input->post('stade'),   
            'arbitre1' =>  $this->input->post('arbitre1'),   
            'arbitre2' =>  $this->input->post('arbitre2'),   
            'arbitre3' =>  $this->input->post('arbitre3'),   
            'arbitre4' =>  $this->input->post('arbitre4')
            //'equipe_visit' => $this->input->post('equipe_visit'),
            //'equipe_recev' => $this->input->post('equipe_recev')       
        );
        $this->db->where('id', $id);        
        $this->db->update('match', $data);
    }
    
    public function get_match($id){
        $this->db->select('*')
                ->from('match')
                ->where('id', $id)
                ->limit(1);
        return $this->db->get();
    }
    
    function add_resultat_match($data)
    {
        $this->db->insert('resultatmatch', $data);
    }
    
    function update_match_resultat_equipe($table)
    {
        $data = array(
            'resultat_equipe_recev' => $table['recev'],
            'resultat_equipe_visit' => $table['visit']                   
        );
        $this->db->where('id', $table['id']);        
        $this->db->update('match', $data); 
    }
    
    function supprimer_resultats_match($id)
    {
        $this->db->where('match', $id);     
        $this->db->delete('resultatmatch');
    }
    
    function supprimer_match($id)
    {
        $this->db->where('id', $id);     
        $this->db->delete('match');
    }
    
    public function get_match_resultats($id)
    {
        $this->db->select('*');
        $this->db->from('resultatmatch');
        $this->db->join('joueur', 'joueur.id = resultatmatch.joueur')
                 ->where('resultatmatch.match', $id);
        $query = $this->db->get();
        return $query->result();
    }
    
    function delete_match_by_equipe($id)
    {
        $this->db->where(" (equipe_visit = $id OR equipe_recev = $id) ", null, false);
        //$this->db->where('(name LIKE %name1% OR name LIKE %name2%)', null, false);
        $this->db->delete('match');
    }
    
    function update_carte_match($id)
    {
        $data = array('carterouge' => $this->input->post('carterouge'), 'cartejaune' => $this->input->post('cartejaune'));
        $this->db->where('id', $id);        
        $this->db->update('match', $data); 
    }
}