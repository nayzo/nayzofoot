<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backend extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('statestique_model');
        $this->twig->addFunction('getsessionhelper'); 
    }

    public function index() {
        if ($this->session->userdata('login_in'))
            $this->twig->render('home');  
        else
        {
            $this->form_validation->set_rules('login', 'Login', 'trim|required|min_length[4]|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]|xss_clean|callback_check_login');
            if ($this->form_validation->run() == FALSE)
                $this->twig->render('login');
            else
                $this->twig->render('home');        
        }
    }

    public function check_login() {

        $req = $this->login_model->login();
        if (!$req) {
            $this->form_validation->set_message('check_login', 'Email ou mot de passe invalide!');
            return false;
        } else {
             foreach($req as $val){
                 $this->load->model('saison_model');
                 $saison = $this->saison_model->get_saison_courant()->row();
                 if($saison)
                 {
                     $login_in = array('id' => $val->id, 'login' => $val->login, 'saison' => $saison->saison, 'saisonid' => $saison->id);
                 }
                 else
                 {
                     $login_in = array('id' => $val->id, 'login' => $val->login);
                 }
                 
                $this->session->set_userdata('login_in', $login_in);
             }
            return TRUE;
        }
    }

    public function changepass() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            $this->form_validation->set_rules('password','Ancien mot de passe','trim|required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('password1','mot de passe','trim|required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('password2','Confirmer mot de passe','trim|required|min_length[4]|max_length[32]|matches[password1]|callback_check_pass');

            if ($this->form_validation->run() == FALSE) {
                $this->twig->render('changepass');
            } else {
                $this->login_model->save_pass();
                redirect('/');
            }
        }
    }
    
    public function check_pass() {

        $req = $this->login_model->pass();
        if (!$req) {
            $this->form_validation->set_message('check_pass', 'Mot de passe incorrecte');
            return false;
        } else
            return TRUE;
    }  

    function logout() {
        $this->session->unset_userdata('login_in');
        $this->session->sess_destroy();
        redirect('/');
    }
    
    function statestique()
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else 
        {
            $data['nbmatch'] = $this->statestique_model->getnbmatches();
            $data['nbbuts'] = $this->statestique_model->getnbbuts();
            $data['nbcarterouge'] = $this->statestique_model->getnbcarterouge();
            $data['nbcartejaune'] = $this->statestique_model->getnbcartejaune();
            $this->twig->render('statestique/statestique', $data);
        }    
    }

}