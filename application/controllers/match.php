<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Match extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('equipe_model');
        $this->load->model('match_model');
        $this->load->model('saison_model');
        $this->load->model('arbitre_model');
        $this->load->model('joueur_model');
        $this->load->model('classement_model');
        $this->twig->addFunction('getsessionhelper');
    }

    public function index() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
                // affichage calendrier de liste des matches 
             $this->twig->render('match/gestionmatch');
        }
    }

    public function ajout() {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            $this->form_validation->set_rules('arbitre', 'Arbitre', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data['saisons'] = $this->saison_model->get_all();
                $data['equipes'] = $this->equipe_model->get_all();
                $data['arbitres'] = $this->arbitre_model->get_all();
                $this->twig->render('match/ajoutmatch', $data);
            } else {
                $this->match_model->add_match();
                redirect('/equipe');
            }
        }
    }

    public function modifier($id) {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else {
            if(!$id) redirect('/');
            $this->form_validation->set_rules('arbitre', 'Arbitre', 'trim|required');

           if ($this->form_validation->run() == FALSE) {
                $data['saisons'] = $this->saison_model->get_all();
                $data['equipes'] = $this->equipe_model->get_all();
                $data['arbitres'] = $this->arbitre_model->get_all();
                $data['match'] = $this->match_model->get_match($id)->row();
                if(!$data['match']) redirect('/');
                $this->twig->render('match/modifiermatch', $data);
            } else {
                $this->match_model->update_match($id);
                redirect('/equipe');
            }
        }
    }


    function voir($idmatch)
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            if(!$idmatch) redirect('/');
            $data['match'] = $this->match_model->get_match($idmatch)->row();
            if(!$data['match']) redirect('/');
            $data['equipe_recev'] = $this->equipe_model->get_equipe($data['match']->equipe_recev)->row();
            $data['equipe_visit'] = $this->equipe_model->get_equipe($data['match']->equipe_visit)->row();
            $data['resultats'] = $this->match_model->get_match_resultats($idmatch);
            $data['arbitre'] = $this->arbitre_model->get_arbitre($data['match']->arbitre)->row();
            $this->twig->render('match/voirmatch', $data);
        }
    }
    
    function resultat($idmatch)
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            if(!$idmatch) redirect('/');
            $this->form_validation->set_rules('sendvalbutrecev', 'Receveur', 'trim|required');
            $this->form_validation->set_rules('sendvalbutvisit', 'Visiteur', 'trim|required');
            $data['match'] = $this->match_model->get_match($idmatch)->row();  
            if(!$data['match']) redirect('/');
            if ($this->form_validation->run() == FALSE) 
            {                              
                $data['equiperecev'] = $this->equipe_model->get_equipe($data['match']->equipe_recev)->row();
                $data['equipevisit'] = $this->equipe_model->get_equipe($data['match']->equipe_visit)->row();
                $data['jreqrecevs'] = $this->joueur_model->get_joueur_by_equipe($data['match']->equipe_recev);
                $data['jreqvisits'] = $this->joueur_model->get_joueur_by_equipe($data['match']->equipe_visit);
                $this->twig->render('resultat/ajoutresultat', $data);
            }
            else 
            {
                // partie equipe receveur
                $recevjoueurs = $this->input->post('recevjoueur');
                if($recevjoueurs)
                    $counttablrecev = count($recevjoueurs);
                else
                    $counttablrecev = 0;
                $recevtimes = $this->input->post('recevtime');
                 
                for($i=0; $i<$counttablrecev; $i++)
                {
                    $datarecev['match'] = $idmatch;
                    $datarecev['equipe'] = $data['match']->equipe_recev;
                    $datarecev['joueur'] = $recevjoueurs[$i];
                    $datarecev['date_but'] = $recevtimes[$i];
                    $this->match_model->add_resultat_match($datarecev);
                }
                // partie equipe visiteur
                $visitjoueurs = $this->input->post('visitjoueur');
                if($visitjoueurs)
                    $counttablvisit = count($visitjoueurs);                
                else
                    $counttablvisit = 0;     
                $visittimes = $this->input->post('visittime');
                
                for($i=0; $i<$counttablvisit; $i++)
                {
                    $datavisit['match'] = $idmatch;
                    $datavisit['equipe'] = $data['match']->equipe_visit;
                    $datavisit['joueur'] = $visitjoueurs[$i];
                    $datavisit['date_but'] = $visittimes[$i];
                    $this->match_model->add_resultat_match($datavisit);
                }
                $tabres['id'] = $idmatch;
                $tabres['recev'] = $counttablrecev;
                $tabres['visit'] = $counttablvisit;
                $this->match_model->update_match_resultat_equipe($tabres);
                //update classement
                if($data['match']->categorie == 'championnat')
                {
                    if($counttablrecev == $counttablvisit)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, 1);
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, 1);
                    }
                    else if($counttablrecev < $counttablvisit)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, 3);
                    }
                    else
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, 3); 
                    }
                }
                else if($data['match']->categorie == 'coupe')
                {
                    if($counttablrecev == $counttablvisit)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, 1);
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, 1);
                    }
                    else if($counttablrecev < $counttablvisit)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, 3);
                    }
                    else
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, 3); 
                    }
                }
                redirect('/');
            }
        }
    }
    
    
    function modifresultat($idmatch)
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            if(!$idmatch) redirect('/');
            $this->form_validation->set_rules('sendvalbutrecev', 'Receveur', 'trim|required');
            $this->form_validation->set_rules('sendvalbutvisit', 'Visiteur', 'trim|required');
            $data['match'] = $this->match_model->get_match($idmatch)->row();  
            if(!$data['match']) redirect('/');
            if ($this->form_validation->run() == FALSE) 
            {                              
                $data['equiperecev'] = $this->equipe_model->get_equipe($data['match']->equipe_recev)->row();
                $data['equipevisit'] = $this->equipe_model->get_equipe($data['match']->equipe_visit)->row();
                $data['jreqrecevs'] = $this->joueur_model->get_joueur_by_equipe($data['match']->equipe_recev);
                $data['jreqvisits'] = $this->joueur_model->get_joueur_by_equipe($data['match']->equipe_visit);
                $this->twig->render('resultat/ajoutresultat', $data);
            }
            else 
            {
                // partie equipe receveur
                $recevjoueurs = $this->input->post('recevjoueur');
                if($recevjoueurs)
                    $counttablrecev = count($recevjoueurs);
                else
                    $counttablrecev = 0;
                $recevtimes = $this->input->post('recevtime');
                 
                for($i=0; $i<$counttablrecev; $i++)
                {
                    $datarecev['match'] = $idmatch;
                    $datarecev['equipe'] = $data['match']->equipe_recev;
                    $datarecev['joueur'] = $recevjoueurs[$i];
                    $datarecev['date_but'] = $recevtimes[$i];
                    $this->match_model->add_resultat_match($datarecev);
                }
                // partie equipe visiteur
                $visitjoueurs = $this->input->post('visitjoueur');
                if($visitjoueurs)
                    $counttablvisit = count($visitjoueurs);                
                else
                    $counttablvisit = 0;     
                $visittimes = $this->input->post('visittime');
                
                for($i=0; $i<$counttablvisit; $i++)
                {
                    $datavisit['match'] = $idmatch;
                    $datavisit['equipe'] = $data['match']->equipe_visit;
                    $datavisit['joueur'] = $visitjoueurs[$i];
                    $datavisit['date_but'] = $visittimes[$i];
                    $this->match_model->add_resultat_match($datavisit);
                }
                $tabres['id'] = $idmatch;
                $tabres['recev'] = $counttablrecev;
                $tabres['visit'] = $counttablvisit;
                $this->match_model->update_match_resultat_equipe($tabres);
                //update classement
                if($data['match']->categorie == 'championnat')
                {
                    if($counttablrecev == $counttablvisit)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, 1);
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, 1);
                    }
                    else if($counttablrecev < $counttablvisit)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, 3);
                    }
                    else
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, 3); 
                    }
                }
                else if($data['match']->categorie == 'coupe')
                {
                    if($counttablrecev == $counttablvisit)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, 1);
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, 1);
                    }
                    else if($counttablrecev < $counttablvisit)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, 3);
                    }
                    else
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, 3); 
                    }
                }
                redirect('/');
            }
        }
    }

    function supprimer($idmatch)
    {
        if (!$this->session->userdata('login_in'))
            redirect('/');
        else
        {
            if(!$idmatch) redirect('/');
            $this->match_model->supprimer_resultat_match($idmatch);
            $this->match_model->supprimer_match($idmatch);
            redirect('/');
        }
    }
}