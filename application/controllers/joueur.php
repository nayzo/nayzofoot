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

                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']	= '100';
                $config['max_width']  = '1024';
                $config['max_height']  = '768';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload())
                {
                        $data['equipes'] = $this->equipe_model->get_all();                
                        $data['error'] = $this->upload->display_errors();
                        $this->twig->render('joueur/ajoutjoueur', $data);
                }
                else
                {
                    $photo = $this->upload->data();
                    $this->joueur_model->add_joueur($photo['file_name']);
                    redirect('/joueur');
                } 
                
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
                
                            $config['upload_path'] = './uploads/';
                            $config['allowed_types'] = 'gif|jpg|png';
                            $config['max_size']	= '100';
                            $config['max_width']  = '1024';
                            $config['max_height']  = '768';
                            $config['encrypt_name']  = TRUE;
                            $this->load->library('upload', $config);
                            
                            if ( ! $this->upload->do_upload()){}
                            $photo = $this->upload->data();
                            $data['photo'] = $photo['file_name']; 
                            $data['id'] = $id;
                            if($photo['file_name'])
                            { 
                                    $this->suppphoto($id);      
                                    $this->joueur_model->update_joueurphoto($data);
                                    redirect('/joueur');
                            }
                            else
                            {
                                $this->joueur_model->update_joueur($id);
                                redirect('/joueur');
                            }
            }
        }
    }

    public function voir($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $data['joueur'] = $this->joueur_model->get_joueur($id)->row();
            $this->twig->render('joueur/voirjoueur', $data);
        }
    }

    public function supprimer($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->suppphoto($id);
            $this->joueur_model->delete_joueur($id);
                redirect('/joueur');
        }
    }
    
    public function suppphoto($id)
    {
        $photo = $this->joueur_model->get_joueur($id)->row()->photo;
        $path = __DIR__.'/../../uploads/'.$photo;
        unlink ($path);
    }
}