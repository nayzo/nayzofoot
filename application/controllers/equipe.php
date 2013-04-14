<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Equipe extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('equipe_model');
        $this->twig->addFunction('getsessionhelper');
    }

    public function index() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
            $data['equipes'] = $this->equipe_model->get_all();
            $this->twig->render('equipe/gestionequipe', $data);
            
    }

}