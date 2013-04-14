<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Twig
| -------------------------------------------------------------------------
|
|	https://github.com/fabpot/Twig
|	http://twig.sensiolabs.org
|
*/

$config['template_dir']= APPPATH . 'views';
$config['template_ext']= 'twig';
$config['debug']= TRUE; // debug: When set to true, the generated templates have a __toString() method that you can use to display the generated nodes (default to false).
$config['cache'] = FALSE; // cache: An absolute path where to store the compiled templates, or false to disable caching (which is the default).
$config['auto_reload'] = TRUE; // auto_reload: When developing with Twig, it's useful to recompile the template whenever the source code changes. If you don�t provide a value for the auto_reload option, it will be determined automatically based on the debug value.
$config['charset'] = 'utf-8'; // charset: The charset used by the templates (default to utf-8).
$config['base_template_class'] = 'Twig_Template'; // base_template_class: The base template class to use for generated templates (default to Twig_Template).
$config['strict_variables'] = FALSE; // strict_variables: If set to false, Twig will silently ignore invalid variables (variables and or attributes/methods that do not exist) and replace them with a null value. When set to true, Twig throws an exception instead (default to false).
$config['autoescape'] = FALSE; // autoescape: If set to true, auto-escaping will be enabled by default for all templates (default to true).
$config['optimizations'] = -1; // optimizations: A flag that indicates which optimizations to apply (default to -1 � all optimizations are enabled; set it to 0 to disable)