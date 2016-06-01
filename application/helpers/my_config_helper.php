<?php
defined('BASEPATH') OR die('denied');
function site_config($var = FALSE)
{
	$CI =& get_instance();
	$CI->config->load('site_config');
	if ($var) return config_item($var);
	return $CI->config->load('site_config', TRUE);
}
/*
* convert a number into other character
* this will build like : a b c aa ab ac
* why wont i use random string?
* i think this is better than random string. 
*/
function create_alias($last_id = FALSE){
	if ($last_id){
		$n = $last_id;
	}else{
		$n = get_last_id() ;
	}
	$sim = 'RmSdcVNy8rzxKg2Ab7QJo9PYjtwLOpiHXfTa5403U1l6uIhvkMWZGFCDsBqeEn'; //characters used to create an alias
    for($r = ""; $n >= 0; $n = intval($n / strlen($sim)) - 1)
        $r = $sim[$n%strlen($sim)] . $r;
    return $r;
}
function get_last_id(){
	$CI =& get_instance();
	isset($CI->sm) OR $CI->load->model(array('site_model' => 'sm'));
	$latest_id = $CI->sm->read('links', FALSE, 'id', array('id', 'DESC'),1);
	$n = $latest_id->num_rows() > 0 ? $latest_id->row()->id : 0; //to create an alias based on current latest id.
	return $n;
}