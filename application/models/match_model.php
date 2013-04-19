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
            'arbitre' =>  $this->input->post('arbitre'),   
            'equipe_visit' => $this->input->post('equipe_visit'),
            'equipe_recev' => $this->input->post('equipe_recev')       
        );

        $this->db->insert('match', $data);
    }
    
    function update_match($id){
            $data = array(
            'type' => $this->input->post('type'),
            'saison'    => $this->input->post('saison'),           
            'categorie' => $this->input->post('categorie'),
            'date_match' => $this->input->post('date_match'),
            'heur_match' => $this->input->post('heur_match'),        
            'stade' => $this->input->post('stade'),   
            'arbitre' =>  $this->input->post('arbitre'),   
            'equipe_visit' => $this->input->post('equipe_visit'),
            'equipe_recev' => $this->input->post('equipe_recev')       
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
    
    function supprimer_resultat_match($id)
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
}