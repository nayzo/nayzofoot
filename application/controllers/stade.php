<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stade extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('stade_model');
        $this->twig->addFunction('getsessionhelper');
    }

    public function index()
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            $data['stades'] = $this->stade_model->get_all();
            $this->twig->render('stade/gestionstade', $data);
        }
    }

    public function ajout()
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            $this->form_validation->set_rules('nom', 'Nom', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ville', 'Ville', 'trim|required|xss_clean');
            $this->form_validation->set_rules('capacite', 'Capacité', 'trim|integer|required|xss_clean');

            if ($this->form_validation->run() == FALSE)
                $this->twig->render('stade/ajoutstade');
            else
            {
                $this->stade_model->add_stade();
                redirect('/stade');
            }
        }
    }

    public function modifier($id)
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            if(!$id) redirect('/');
            
            $this->form_validation->set_rules('nom', 'Nom', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ville', 'Ville', 'trim|required|xss_clean');
            $this->form_validation->set_rules('capacite', 'Capacité', 'trim|integer|required|xss_clean');

            if ($this->form_validation->run() == FALSE)
            {
                $data['stade'] = $this->stade_model->get_stade($id);
                
                if(!$data['arbitre'])
                    redirect('/');
                
                $this->twig->render('stade/modifierstade', $data);
            }
            else
            {
                $this->stade_model->update_stade($id);
                redirect('/stade');
            }
        }
    }
    
    public function supprimer($id)
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            if(!$id)
                redirect('/');
            
            $this->stade_model->delete_stade($id);
                redirect('/stade');
        }
    }
}