<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Match extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('equipe_model');
        $this->load->model('match_model');
        $this->load->model('saison_model');
        $this->load->model('arbitre_model');
        $this->twig->addFunction('getsessionhelper');
    }

    public function index() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
                // affichage calendrier de liste des matches 
             $this->twig->render('match/gestionmatch');
        }
    }

    public function ajout() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('arbitre', 'Arbitre', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data['saisons'] = $this->saison_model->get_all();
                $data['equipes'] = $this->equipe_model->get_all();
                $data['arbitres'] = $this->arbitre_model->get_all();
                $this->twig->render('match/ajoutmatch', $data);
            } else {
                $this->match_model->add_match();
                redirect('/equipe');
            }
        }
    }

    public function modifier($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('arbitre', 'Arbitre', 'trim|required');

           if ($this->form_validation->run() == FALSE) {
                $data['saisons'] = $this->saison_model->get_all();
                $data['equipes'] = $this->equipe_model->get_all();
                $data['arbitres'] = $this->arbitre_model->get_all();
                $data['match'] = $this->match_model->get_match($id)->row();
                $this->twig->render('match/ajoutmatch', $data);
            } else {
                $this->match_model->update_match($id);
                redirect('/equipe');
            }
        }
    }


    function resultat()
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            
        }
    }

}