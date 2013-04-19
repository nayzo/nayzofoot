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
        else
        {
            $data = array();
            $str = '';
            $res = $this->match_model->get_all();
            
            foreach($res as $row)
            {
                $str .= '{title: ';
                $str .= '\''. $row->categorie .'\'';
                $str .= ', start: new Date(';
                $str .= date('Y', strtotime($row->date_match));
                $str .= ', ';
                $str .= date('m', strtotime($row->date_match)) - 1;
                $str .= ', ';
                $str .= date('d', strtotime($row->date_match));
                $str .= ', ';
                $str .= date('H', strtotime($row->heur_match));
                $str .= ', ';
                $str .= date('i', strtotime($row->heur_match));
                $str .= '), url: ';
                $str .= '\'' . base_url() . 'match/voir/' . $row->id . '\'';
                $str .= ', allDay: false';
                $str .= '}, ';
            }
            
            $data['val'] = substr($str, 0, strlen($str) - 2);
            
            // affichage calendrier de liste des matches 
            $this->twig->render('match/gestionmatch', $data);
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
                redirect("/match/voir/$id");
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
                redirect("/match/voir/$idmatch");
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
                $data['resultats'] = $this->match_model->get_match_resultats($idmatch);
                $this->twig->render('resultat/modifierresultat', $data);
            }
            else 
            {
                // suppression des resultats de la BD
                $this->mutch_model->supprimer_resultat_match($idmatch);
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
                //update classement
                $resequimatch_rec = $data['match']->resultat_equipe_recev;
                $resequimatch_vis = $data['match']->resultat_equipe_visit;
        if($data['match']->categorie == 'championnat')
        {            
                if($resequimatch_vis > $resequimatch_rec)
                {
                    if($counttablvisit < $counttablrecev)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, -3);
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, 3); 
                    }
                    else if($counttablvisit == $counttablrecev)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, -2);
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, 1);  
                    }
                }
                else if($resequimatch_vis < $resequimatch_rec)
                {
                    if($counttablvisit > $counttablrecev)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, 3);
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, -3); 
                    }
                    else if($counttablvisit == $counttablrecev)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, 1);
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, -2);  
                    }
                }
                else if($resequimatch_vis == $resequimatch_rec)
                {
                    if($counttablvisit > $counttablrecev)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, 2);
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, -1); 
                    }
                    else if($counttablvisit < $counttablrecev)
                    {
                        $this->classement_model->add_point_championnat($data['match']->equipe_visit, -1);
                        $this->classement_model->add_point_championnat($data['match']->equipe_recev, 2);  
                    }
                }
                
        }
        else if($data['match']->categorie == 'coupe')
        {
                if($resequimatch_vis > $resequimatch_rec)
                {
                    if($counttablvisit < $counttablrecev)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, -3);
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, 3); 
                    }
                    else if($counttablvisit == $counttablrecev)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, -2);
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, 1);  
                    }
                }
                else if($resequimatch_vis < $resequimatch_rec)
                {
                    if($counttablvisit > $counttablrecev)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, 3);
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, -3); 
                    }
                    else if($counttablvisit == $counttablrecev)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, 1);
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, -2);  
                    }
                }
                else if($resequimatch_vis == $resequimatch_rec)
                {
                    if($counttablvisit > $counttablrecev)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, 2);
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, -1); 
                    }
                    else if($counttablvisit < $counttablrecev)
                    {
                        $this->classement_model->add_point_coupe($data['match']->equipe_visit, -1);
                        $this->classement_model->add_point_coupe($data['match']->equipe_recev, 2);  
                    }
                } 
        }
                // update resultat de table match
                $tabres['id'] = $idmatch;
                $tabres['recev'] = $counttablrecev;
                $tabres['visit'] = $counttablvisit;

                $this->match_model->update_match_resultat_equipe($tabres);
                
                redirect("/match/voir/$idmatch");
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