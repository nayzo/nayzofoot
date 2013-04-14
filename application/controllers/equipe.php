<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Equipe extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('equipe_model');
        $this->twig->addFunction('getsessionhelper');
    }

    public function index() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $data['equipes'] = $this->equipe_model->get_all();
            $this->twig->render('equipe/gestionequipe', $data);
        }
    }

    public function ajout() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('nom', 'Nom', 'trim|required');
            $this->form_validation->set_rules('directeur', 'directeur', 'trim|required');
            $this->form_validation->set_rules('entreneur', 'entreneur', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->twig->render('equipe/ajoutequipe');
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
                $this->twig->render('equipe/modifierequipe', $this->equipe_model->get_equipe($id)->row());
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