<?php
class Statestique_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    
    
    
    function getnbmatches()
    {
        $this->db->select('*')
                ->from('match')
                ->where('saison', getsessionhelper()['saisonid'] )
                ->where('resultat_equipe_visit != ', -1);
            $query = $this->db->get();
            return $rowcount = $query->num_rows();
    }
    
   function getnbbuts()
    {
        $this->db->select('*')
                ->from('match')
                ->where('saison', getsessionhelper()['saisonid'] )
                ->where('resultat_equipe_visit != ', -1);
            $query = $this->db->get()->result();
        $nb = 0;
       foreach($query as $res)     
       {
           $nb += ($res->resultat_equipe_recev + $res->resultat_equipe_visit);
       }
       return $nb;
    }
}