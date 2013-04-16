<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function css_url($nom)
{
    return base_url() . 'assets/css/' . $nom . '.css';
}

function js_url($nom)
{
    return base_url() . 'assets/js/' . $nom . '.js';
}

function img_url($nom)
{
    return base_url() . 'assets/img/' . $nom;
}

function img($nom, $alt = '')
{
    return '<img src="' . img_url($nom) . '" alt="' . $alt . '" />';
}


function cal_css_url($nom)
{
    return base_url() . 'assets/css/cal_css/' . $nom . '.css';
}

function cal_js_url($nom)
{
    return base_url() . 'assets/js/cal_js/' . $nom . '.js';
}

function cal_img_url($nom)
{
    return base_url() . 'assets/img/cal_img/' . $nom;
}

?>


