<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Match extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('equipe_model');
        $this->load->model('match_model', 'matchManager');
        $this->load->model('saison_model');
        $this->twig->addFunction('getsessionhelper');
    }

    public function index()
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            $data = array();
            $str = '';
            $res = $this->matchManager->get_all();
            
            foreach($res as $row)
            {
                $str .= '{title: ';
                $str .= '\''. $row->categorie .'\'';
                $str .= ', start: new Date(';
                $str .= date('Y', strtotime($row->date_match));
                $str .= ', ';
                $str .= date('m', strtotime($row->date_match));
                $str .= ', ';
                $str .= date('d', strtotime($row->date_match));
                $str .= '), url: ';
                $str .= '\'http://localhost:8095/\'';
                $str .= '}, ';
            }
            
            //echo substr($str, 0, strlen($str) - 2);
            //return;
            $data['ddd'] = substr($str, 0, strlen($str) - 2);
            
            // affichage calendrier de liste des matches 
            $this->twig->render('match/gestionmatch', $data);
        }
    }

    public function ajout() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('arbitre', 'Arbitre', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data['saisons'] = $this->saison_model->get_all();
                $this->twig->render('match/ajoutmatch', $data);
            } else {
                $this->equipe_model->add_equipe();
                redirect('/equipe');
            }
        }
    }

    public function modifier($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('nom', 'Nom', 'trim|required');
            $this->form_validation->set_rules('directeur', 'directeur', 'trim|required');
            $this->form_validation->set_rules('entreneur', 'entreneur', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->twig->render('modifierequipe', $this->equipe_model->get_equipe($id)->row());
            } else {
                $this->equipe_model->update_equipe();
                redirect('/equipe');
            }
        }
    }

    public function voir($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->twig->render('equipe/voirequipe', $this->equipe_model->get_equipe($id)->row());
        }
    }

    
}