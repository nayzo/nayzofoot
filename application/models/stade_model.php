<?php
class Stade_model extends CI_Model {
    
    private $table = 'stade';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }
    
    function add_stade()
    {
        $data = array('nom' => $this->input->post('nom'),
                      'ville' => $this->input->post('ville'),
                      'capacite' => $this->input->post('capacite')
                     );
        $this->db->insert($this->table, $data);
    }
    
    public function get_stade($id)
    {
        $query = $this->db->where('id', $id)->get($this->table);
        
        if($query->num_rows() == 1)
            return $query->row();
        else
            return false;
    }
    
    public function update_stade($id)
    {
        $data = array('nom' => $this->input->post('nom'),
                      'ville' => $this->input->post('ville'),
                      'capacite' => $this->input->post('capacite')
                     );
        
        $this->db->update($this->table, $data, array('id' => $id));
    }
    
    public function delete_stade($id)
    {
        $this->db->delete($this->table, array('id' => $id));
    }
}