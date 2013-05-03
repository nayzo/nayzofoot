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
    
    public function add_equipe($photo)
    {
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
        
        //************
        $equipeid = $this->db->insert_id();
        $this->load->model('saison_model');
        $saisons = $this->saison_model->get_all();
        foreach ($saisons as $eq) {
            $tab['saison'] = $eq->id;
            $tab['equipe'] = $equipeid;
            $this->db->insert('classement', $tab);
        }
        
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

    public function list_equies_ligue_championnat($ligue){
        $saison = getsessionhelper()['saisonid'];
        $data = " select * FROM classement c
                  inner JOIN equipe e
                  ON c.equipe=e.id
                  where c.saison=$saison
                  AND e.ligue='$ligue'
                  ORDER BY c.nb_point_championnat DESC
                  ;
            ";
        $query = $this->db->query($data);
        return $query->result();
    }
    
    public function list_equies_ligue_coupe($ligue){
        $saison = getsessionhelper()['saisonid'];
        $data = " select * FROM classement c
                  inner JOIN equipe e
                  ON c.equipe=e.id
                  where c.saison=$saison
                  AND e.ligue='$ligue'
                  ORDER BY c.nb_point_coupe DESC
                  ;
            ";
        $query = $this->db->query($data);
        return $query->result();
    }
}