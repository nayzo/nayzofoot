<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getsessionhelper'))
{
    function getsessionhelper()
    {
        $ci =& get_instance();
        //return $ci->session->userdata("username");
        $data = array();
        $data['userdata'] = $ci->session->userdata('login_in');
       // return $data;
        return $ci->session->userdata('login_in');
    }   
}