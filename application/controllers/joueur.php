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
            $this->form_validation->set_rules('nom', 'Nom', 'trim|required');
            $this->form_validation->set_rules('equipe', 'Equipe', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data['equipes'] = $this->equipe_model->get_all();
                $this->twig->render('joueur/ajoutjoueur', $data);
            } else {
                $this->joueur_model->add_joueur();
                redirect('/joueur');
            }
        }
    }

    public function modifier($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('nom', 'Nom', 'trim|required');
            $this->form_validation->set_rules('equipe', 'Equipe', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data['equipes'] = $this->equipe_model->get_all();
                $data['joueur'] = $this->joueur_model->get_joueur($id)->row();
                $this->twig->render('joueur/modifierjoueur', $data);
            } else {
                $this->joueur_model->update_joueur();
                redirect('/joueur');
            }
        }
    }

    public function voir($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->twig->render('joueur/voirjoueur', $this->joueur_model->get_joueur($id)->row());
        }
    }

    public function supprimer($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->joueur_model->delete_joueur($id);
                redirect('/joueur');
        }
    }
}