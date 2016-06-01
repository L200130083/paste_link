<?php
defined('BASEPATH') OR die('Not Allowed');
class Equiv extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('site_model');
		
	}
	function index()
	{
		print_r($this->site_model->read('groups'));
	}
	
}