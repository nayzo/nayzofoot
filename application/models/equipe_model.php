<?php
class Equipe_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_all(){
        $query = $this->db->get('equipe');
            return $query->result();
    }
    
    function add_equipe($photo){
            $data = array(
            'nom_equipe' => $this->input->post('nom_equipe'),
            'entreneur'    => $this->input->post('entreneur'),           
            'date_creation' => $this->input->post('date_creation'),
            'lieu_origin' => $this->input->post('lieu_origin'),
            'logo' => $photo,
            'siteweb' => $this->input->post('siteweb'),   
            'stade' =>  $this->input->post('stade'),   
            'ligue' => $this->input->post('ligue')   
        );

        $this->db->insert('equipe', $data);
    }
    
    function update_equipe($id){
            $data = array(
            'nom_equipe' => $this->input->post('nom_equipe'),
            'entreneur'    => $this->input->post('entreneur'),           
            'date_creation' => $this->input->post('date_creation'),
            'lieu_origin' => $this->input->post('lieu_origin'),
            'siteweb' => $this->input->post('siteweb'),   
            'stade' =>  $this->input->post('stade'),   
            'ligue' => $this->input->post('ligue')   
        );
        $this->db->where('id', $id);     
        $this->db->update('equipe', $data);
    }
    
    function update_equipephoto($tab){
            $data = array(
            'nom_equipe' => $this->input->post('nom_equipe'),
            'entreneur'    => $this->input->post('entreneur'),           
            'date_creation' => $this->input->post('date_creation'),
            'lieu_origin' => $this->input->post('lieu_origin'),
            'logo' => $tab['photo'],    
            'siteweb' => $this->input->post('siteweb'),   
            'stade' =>  $this->input->post('stade'),   
            'ligue' => $this->input->post('ligue')   
        );
        $this->db->where('id', $tab['id']);     
        $this->db->update('equipe', $data);
    }
    
    public function get_equipe($id){
        $this->db->select('*')
                ->from('equipe')
                ->where('id', $id)
                ->limit(1);
        return $this->db->get();
    }
    
    function delete_equipe($id){
        $this->db->where('id', $id);     
        $this->db->delete('equipe');
    }
}