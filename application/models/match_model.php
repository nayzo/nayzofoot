<?php
class Match_model extends CI_Model
{
    private $table = 'match';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }
    
//    function add_saison(){
//            $data = array(
//            'saison' => $this->input->post('saison')
//        );
//        $this->db->insert('saison', $data);
//        return  $this->db->insert_id()  ;
//    }
//    
//    function update_saison(){
//            $data = array(
//            'saison' => $this->input->post('saison'),
//        );
//        $this->db->update('saison', $data);
//    }    
//    
//    function get_saison($id){
//        $this->db->select('*')
//                ->from('saison')
//                ->where('id', $id)
//                ->limit(1);
//        return $this->db->get();
//    }
//    
//    function get_saison_courant(){
//        $this->db->select('id')
//                ->from('saison')
//                ->where('saison_courant', 1)
//                ->limit(1);
//        return $this->db->get();
//    }
}