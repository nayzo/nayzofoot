<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Joueur extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('equipe_model');
        $this->load->model('joueur_model');
        $this->twig->addFunction('getsessionhelper');
    }

    public function index() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $data['joueurs'] = $this->joueur_model->get_all();
            $this->twig->render('joueur/gestionjoueur', $data);
        }
    }

    public function ajout() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('Nom', 'Nom', 'trim|required');
            $this->form_validation->set_rules('Equipe', 'Equipe', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data['equipes'] = $this->equipe_model->get_all();
                $this->twig->render('joueur/ajoutjoueur', $data);
            } else {
                $this->equipe_model->save_equipe();
                redirect('/joueur');
            }
        }
    }

    public function modifier($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('Nom', 'Nom', 'trim|required');
            $this->form_validation->set_rules('Directeur', 'directeur', 'trim|required');
            $this->form_validation->set_rules('Entreneur', 'entreneur', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->twig->render('modifierequipe', $this->equipe_model->get_equipe($id)->row());
            } else {
                $this->equipe_model->save_equipe();
                redirect('/joueur');
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