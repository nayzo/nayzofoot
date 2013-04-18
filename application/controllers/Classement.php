<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('saison_model');
        $this->load->model('classement_model');
        $this->load->model('equipe_model');
        $this->twig->addFunction('getsessionhelper'); 
    }

    public function championnat() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            if(!$this->classement_model->get_if_one_saison_courant())
            {
                $data['testcourantexist'] = false;
                $this->twig->render('classement/championnat', $data);
            }
            else
            {
                $data['testcourantexist'] = true;
                $saison = $this->saison_model->get_saison_courant()->row();
                $data['classements'] = $this->classement_model->get_all_championnat($saison->id);
                $this->twig->render('classement/championnat', $data);
            }
            
        }
    }
 
    public function coupe() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            if(!$this->classement_model->get_if_one_saison_courant())
            {
                $data['testcourantexist'] = false;
                $this->twig->render('classement/coupe', $data);
            }
            else
            {
                $data['testcourantexist'] = true;
                $saison = $this->saison_model->get_saison_courant()->row();
                $data['classements'] = $this->classement_model->get_all_coupe($saison->id);
                $this->twig->render('classement/coupe', $data);
            }            
        }
    }
    
    function champligueun()
    {
        $ligue = 'Football Pro ligue 1';
        $data['classement'] = $this->equipe_model->list_equies_ligue_championnat($ligue);
        $this->twig->render('classement/championnat', $data);
    }
    
    function champliguedeux()
    {
        $ligue = 'Football Pro ligue 2';
        $data['classement'] = $this->equipe_model->list_equies_ligue_championnat($ligue);
        $this->twig->render('classement/championnat', $data);
    }
    
    function champligueamateur()
    {
        $ligue = 'Football Amateur';
        $data['classement'] = $this->equipe_model->list_equies_ligue_championnat($ligue);
        $this->twig->render('classement/championnat', $data);
    }
    
    function champliguefeminin()
    {
        $ligue = 'Football Féminin';
        $data['classement'] = $this->equipe_model->list_equies_ligue_championnat($ligue);
        $this->twig->render('classement/championnat', $data);
    }
    
    function champliguefutsal()
    {
        $ligue = 'Futsal';
        $data['classement'] = $this->equipe_model->list_equies_ligue_championnat($ligue);
        $this->twig->render('classement/championnat', $data);
    }
    
    
    //**********************************************************
    
    function coupeligueun()
    {
        $ligue = 'Football Pro ligue 1';
        $data['classement'] = $this->equipe_model->list_equies_ligue_coupe($ligue);
        $this->twig->render('classement/championnat', $data);
    }
    
    function coupeliguedeux()
    {
        $ligue = 'Football Pro ligue 2';
        $data['classement'] = $this->equipe_model->list_equies_ligue_coupe($ligue);
        $this->twig->render('classement/championnat', $data);
    }
    
    function coupeligueamateur()
    {
        $ligue = 'Football Amateur';
        $data['classement'] = $this->equipe_model->list_equies_ligue_coupe($ligue);
        $this->twig->render('classement/championnat', $data);
    }
    
    function coupeliguefeminin()
    {
        $ligue = 'Football Féminin';
        $data['classement'] = $this->equipe_model->list_equies_ligue_coupe($ligue);
        $this->twig->render('classement/championnat', $data);
    }
    
    function coupeliguefutsal()
    {
        $ligue = 'Futsal';
        $data['classement'] = $this->equipe_model->list_equies_ligue_coupe($ligue);
        $this->twig->render('classement/championnat', $data);
    }
} 