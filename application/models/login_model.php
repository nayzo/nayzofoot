<?php
class Login_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function login(){
        $this->db->select('id, login')
                ->from('admin')
                ->where('login', $this->input->post('login'))
                ->where('password', sha1($this->input->post('password')))
                ->limit(1);
        $query = $this->db->get();
        if($query->num_rows()==1)
            return $query->result();
        else
            return false;
    }
    
    function pass(){
        $this->db->select('login')
                ->from('admin')
                ->where('id', getsessionhelper()['id'] )
                ->where('password', sha1($this->input->post('password')))
                ->limit(1);
        $query = $this->db->get();
        if($query->num_rows()==1)
            return true;
        else
            return false;
    }
    function save_pass()
    {
            $data = array(
            'password' => sha1($this->input->post('password1'))
        );

        $this->db->update('admin', $data);
    }
    
    
}