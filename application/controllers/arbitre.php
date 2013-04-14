<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Arbitre extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('arbitre_model');
        $this->twig->addFunction('getsessionhelper');
    }

    public function index() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $data['arbitres'] = $this->arbitre_model->get_all();
            $this->twig->render('arbitre/gestionarbitre', $data);
        }
    }

    public function ajout() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('nom', 'Nom', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->twig->render('arbitre/ajoutarbitre');
            } else {
                $this->arbitre_model->add_arbitre();
                redirect('/arbitre');
            }
        }
    }

    public function modifier($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('nom', 'Nom', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->twig->render('modifierarbitre', $this->arbitre_model->get_arbitre($id)->row());
            } else {
                $this->arbitre_model->update_arbitre();
                redirect('/arbitre');
            }
        }
    }
}