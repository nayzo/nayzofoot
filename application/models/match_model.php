<?php
class Match_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
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
}