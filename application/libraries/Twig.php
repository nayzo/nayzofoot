<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Twig class.
 */
class Twig
{

	/**
	 * twig -- the twig environment
	 * 
	 * @var mixed
	 * @access private
	 */
	private $twig;

	/**
	 * CI -- the CodeIgniter instance
	 * 
	 * @var mixed
	 * @access private
	 */
	private $CI;

	/**
	 * data -- an array of data to be passed to the template context
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access private
	 */
	private $data = array(); // data passed to template

	/**
	 * loadMyFunctions -- makes CI functions available within templates
	 *
	 * @access private
	 * @return void
	 */
	private function loadMyFunctions()
	{
		// this is probably best for most people
		$functions = get_defined_functions();
		foreach($functions['user'] as $index => $function) {
			$this->addFunction($function);
		}

/*
		// larger footprint but exposes everything and the kitchen sink
		foreach(get_defined_functions() as $functions) {
			foreach($functions as $function) {
				$this->addFunction($function);
			}
    	}

		// less expensive, includes only user space functions (most common ... includes all the CI helpers and such)
		$functions = get_defined_functions();
		foreach($functions['user'] as $index => $function) {
			$this->addFunction($function);
		}

		// or, add only what you need
		$this->addFunction('anchor');
*/
	}

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->CI = get_instance();
		$this->CI->config->load('twig', TRUE);

		$this->CI->load->file(APPPATH . 'libraries/Twig/Autoloader' . EXT);
		Twig_Autoloader::register();

		$loader = new Twig_Loader_Filesystem($this->CI->config->item('template_dir', 'twig'));

		// it's kind of annoying that CI returns FALSE instead of NULL for indexes that don't exist
		$this->twig = new Twig_Environment($loader, array(
			'auto_reload' => $this->CI->config->item('auto_reload', 'twig'),
			'autoescape' => $this->CI->config->item('autoescape', 'twig'),
			'base_template_class' => $this->CI->config->item('base_template_class', 'twig'),
			'cache' => $this->CI->config->item('cache', 'twig'),
			'charset' => $this->CI->config->item('charset', 'twig'),
			'debug' => $this->CI->config->item('debug', 'twig'),
			'optimizations' => $this->CI->config->item('optimizations', 'twig'),
			'strict_variables' => $this->CI->config->item('strict_variables', 'twig')
		));

		$this->loadMyFunctions();
		log_message('debug', '[Twig] Library loaded -- twig templates are available for use');
	}

	/**
	 * addFunction function -- expose a single function to the templates
	 * 
	 * @access public
	 * @param string $name The name of the function to be added
	 * @return void
	 */
	public function addFunction($name)
	{
		if  ( function_exists($name) ) $this->twig->addFunction($name, new Twig_Function_Function($name));
	}

	/**
	 * render function -- Renders the template with the given context
	 * 
	 * @access public
	 * @param string $name Name of template file (including paths from root template path)
	 * @param array $data (default: array())
	 * @param boolean $render Determines whether the compiled template is output or returned (default: TRUE)
	 * @return mixed
	 */
	public function render($name, $data = array(), $render = TRUE)
	{
		$template = $this->loadTemplate($name);
		$this->_preOutput();
		if ( ! $render )
			return $template->render(array_merge($this->data, $data));

		echo $template->render(array_merge($this->data, $data));
	}

	/**
	 * loadTemplate function -- load a template
	 * 
	 * @access public
	 * @param string $name Name of template file (including paths from root template path)
	 * @return Twig_Template
	 */
	public function loadTemplate($name) {
		$result = $this->twig->loadTemplate($name . '.' . $this->CI->config->item('template_ext', 'twig'));
		log_message('debug', '[Twig] Template loaded -- ' . $name . '.' . $this->CI->config->item('template_ext', 'twig'));
		return $result;
	}

	/**
	 * _preOutput function -- some last minute helpers
	 * 
	 * @access private
	 * @return void
	 */
	private function _preOutput()
	{
		$this->data['memory_usage'] = round(memory_get_usage()/1024/1024, 2) . 'MB';
		$this->data['elapsed_time'] = $this->CI->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');
	}
}