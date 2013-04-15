<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('saison_model');
        $this->load->model('classement_model');
        $this->twig->addFunction('getsessionhelper'); 
    }

    public function championnat() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            if(!$this->classement_model->get_if_one_saison_courante())
            {
                $data['testcourantexist'] = false;
                $this->twig->render('classement/championnat', $data);
            }
            else
            {
                $data['testcourantexist'] = true;
                $saison = $this->saison_model->get_saison_courante()->row();
                $data['classements'] = $this->classement_model->get_all_championnat($saison);
                $this->twig->render('classement/championnat', $data);
            }
            
        }
    }
 
    public function coupe() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            if(!$this->classement_model->get_if_one_saison_courante())
            {
                $data['testcourantexist'] = false;
                $this->twig->render('classement/coupe', $data);
            }
            else
            {
                $data['testcourantexist'] = true;
                $saison = $this->saison_model->get_saison_courante()->row();
                $data['classements'] = $this->classement_model->get_all_coupe($saison);
                $this->twig->render('classement/coupe', $data);
            }            
        }
    }
}