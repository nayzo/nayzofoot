<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Saison extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('saison_model');
        $this->load->model('classement_model');
        $this->twig->addFunction('getsessionhelper'); 
    }

    public function index() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $data['saisons'] = $this->saison_model->get_all();
            $data['saisonerreur'] ='';
            $this->twig->render('saison/gestionsaison', $data);
        }
    }

    public function ajout() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('saison', 'Saison', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->twig->render('saison/ajoutsaison');
            } else {                
                $id = $this->saison_model->add_saison();
                if ($this->input->post('saison_courant'))
                {
                    $this->activercourant($id);                    
                }
                $this->classement_model->add_classement($id);
                redirect('/saison');
            }
        }
    }

    public function modifier($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            if(!$id) redirect('/');
            $this->form_validation->set_rules('saison', 'Saison', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data['saison'] = $this->saison_model->get_saison($id)->row();
                if(!$data['saison']) redirect('/');
                $this->twig->render('saison/modifiersaison', $data);
            } else {
                $this->saison_model->update_saison($id);                  
                redirect('/saison');
            }
        }
    }

     public function supprimer($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            if(!$id) redirect('/');
            if(getsessionhelper()['saisonid'] == $id)
            {
                $data['saisonerreur'] ='Impossible de supprimer le saison courant';
                $data['saisons'] = $this->saison_model->get_all();
                $this->twig->render('saison/gestionsaison', $data);
            }
            else
            {
                $this->saison_model->delete_saison($id);
                $this->classement_model->delete_classement_saison($id);
                redirect('/saison');
            }
            
        }
    }
    
    public function activer($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
                if(!$id) redirect('/');
                $this->activercourant($id);
                redirect('/saison');
        }
    }
    
    public function activercourant($id) 
    {
                $this->saison_model->setsaison_courant($id);
                $data = $this->saison_model->get_saison_courant()->row();
                $login_in = $this->session->userdata('login_in');
                $login_in['saison'] = $data->saison;
                $login_in['saisonid'] = $id;
                $this->session->set_userdata('login_in', $login_in);
    }
    
}