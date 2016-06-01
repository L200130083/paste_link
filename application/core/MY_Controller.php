<?php
defined('BASEPATH') OR die('Denied');
class MY_Controller extends CI_Controller
{
	use Jade;
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('site', 'my_config'));
		//$this->load->library("security");
		$this->template = site_config('template') . DIRECTORY_SEPARATOR;
		$this->data = [];
		$this->jadeVars();
	}
	function jadeVars(){
		$this->data['base_url'] = base_url();
		$this->data['site_url'] = trim(site_url(), '/');
		$this->data['is_admin'] = $this->ion_auth->is_admin();
		$this->data['logged_in'] = $this->ion_auth->logged_in();
		$this->data['csrf_token'] = $this->security->get_csrf_token_name();
		$this->data['csrf_hash'] = $this->security->get_csrf_hash();
	}
}
//maintenance mode
class GOV_Controller extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		if (site_config('maintenance') == TRUE && !$this->ion_auth->is_admin()) die('Maintenance Mode');
	}
	function view($a = NULL, $b = array(), $c = FALSE){
		if (! is_array($b)){
			die("Error #1 : Gov_Controller::view array expected str given");
		}
		return parent::view($a, $b, $c);
	}
	
}