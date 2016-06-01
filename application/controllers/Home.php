<?php
defined('BASEPATH') OR die('Denied');
class Home extends GOV_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('site_model' => 'sm'));
	}
	function index()
	{

		$this->data['title'] = site_config('site_title');
		$this->view($this->template."home_index", $this->data);
	}
	function submit()
	{
		$this->input->is_ajax_request() OR show_404();
		//i dunno but working with sqlite3 i just wanna fill these data manually XD
		if ( ! isset($_POST['url'])) {
			echo json_encode(array(
					'error' => 1,
					'msg' => 'Access Denied',
					'data' => array(),
					'csrf_token' => $this->data['csrf_token'],
					'csrf_hash' => $this->data['csrf_hash']
			));
			return;
		}
		$this->load->library("form_validation");
		$this->form_validation->set_rules("url", "Url", "required|min_length[1]");
		if ($this->form_validation->run()){
			$get_last_id = get_last_id();
			$data['id'] = $get_last_id+1;
			$data['title'] = $this->input->post("title");
			$data['urls'] = trim(preg_replace("/[\r\n]+/", "\n", $this->input->post('url')), PHP_EOL);
			$data['password'] = $this->input->post("password")?MD5($this->input->post("password")):NULL;
			$data['date'] = date("Y-m-d H:i:s");
			$data['owner'] = isset($this->ion_auth->user()->row()->id)?$this->ion_auth->user()->row()->id:1;
			$data['views'] = 0;
			$data['alias'] = create_alias($get_last_id);
			$data['urls'] OR redirect();
			if ($this->sm->insert("links", $data)){
				//success
				echo json_encode(array(
					'error' => 0,
					'msg' => NULL,
					'data' => array(
						'title' => $data['title'],
						'alias' => $data['alias'],
						'use_password' => $data['password']?1:0,
						'links' => explode("\n", $data['urls'])
					),
					'csrf_token' => $this->data['csrf_token'],
					'csrf_hash' => $this->data['csrf_hash']
				));
			}else{
				//bring the error message to show in other page
				//$this->session->set_flashdata("msg", array("Error while inserting data"));
				echo json_encode(array(
					'error' => 1,
					'msg' => 'Inserting to database failed',
					'data' => array(),
					'csrf_token' => $this->data['csrf_token'],
					'csrf_hash' => $this->data['csrf_hash']
				));
			}
		}else{
			echo json_encode(array(
					'error' => 1,
					'msg' => strip_tags(validation_errors()),
					'data' => array(),
					'csrf_token' => $this->data['csrf_token'],
					'csrf_hash' => $this->data['csrf_hash']
				));
		}
		//print_r($this->sm->read("links")->row());
		//redirect(site_url()."/home/view/{$data['alias']}");
	}
	function visit(){
		$this->input->is_ajax_request() OR show_404();
		//check post request
		if ( ! isset($_POST['alias'])) {
			echo json_encode(array(
					'error' => 1,
					'msg' => 'Access Denied',
					'data' => array(),
					'csrf_token' => $this->data['csrf_token'],
					'csrf_hash' => $this->data['csrf_hash']
			));
			return; //stop execution
		}
		//validate the post content
		$this->load->library("form_validation");
		$this->form_validation->set_rules("alias", "Alias", "required|alpha_numeric");
		if ( ! $this->form_validation->run()){
			echo json_encode(array(
					'error' => 1,
					'msg' => validation_errors(),
					'data' => array(),
					'csrf_token' => $this->data['csrf_token'],
					'csrf_hash' => $this->data['csrf_hash']
			));
			return;
		}
		$this->data['detail'] = $this->sm->read('links', array('alias' =>$this->input->post("alias")));
		//check if alias is exists in database
		if ( ! $this->data['detail']->num_rows() > 0) {
			echo json_encode(array(
					'error' => 4,
					'msg' => 'Page not found error',
					'data' => array(),
					'csrf_token' => $this->data['csrf_token'],
					'csrf_hash' => $this->data['csrf_hash']
			));
			return;
		}
		$this->data['detail'] = $this->data['detail']->row();
		//check if alias has password
		if ($this->data['detail']->password && ! isset($_POST['password'])){
			echo json_encode(array(
				'error' => 2, //if the error code is 2, then it marked as need authentication
				'msg' => NULL,
				'data' => array(
					'title' => $this->data['detail']->title,
					'alias' => $this->data['detail']->alias,
					'use_password' => $this->data['detail']->password?1:0
				),
				'csrf_token' => $this->data['csrf_token'],
				'csrf_hash' => $this->data['csrf_hash']
			));
		}elseif ($this->data['detail']->password && isset($_POST['password'])) {
			//check if password mattch
			if (MD5($this->input->post("password")) !== $this->data['detail']->password){
				echo json_encode(array(
					'error' => 3, //if the error code is 3, then password is incorrect
					'msg' => "Incorrect Password",
					'data' => array(
						'title' => $this->data['detail']->title,
						'alias' => $this->data['detail']->alias,
					),
					'csrf_token' => $this->data['csrf_token'],
					'csrf_hash' => $this->data['csrf_hash']
				));
			}else{
				echo json_encode(array(
					'error' => 0,
					'msg' => NULL,
					'data' => array(
						'title' => $this->data['detail']->title,
						'alias' => $this->data['detail']->alias,
						'links' => explode("\n", $this->data['detail']->urls)
					),
					'csrf_token' => $this->data['csrf_token'],
					'csrf_hash' => $this->data['csrf_hash']
				));
			}
		}else{
			//no password required
			echo json_encode(array(
				'error' => 0,
				'msg' => NULL,
				'data' => array(
					'title' => $this->data['detail']->title,
					'alias' => $this->data['detail']->alias,
					'links' => explode("\n", $this->data['detail']->urls)
				),
				'csrf_token' => $this->data['csrf_token'],
				'csrf_hash' => $this->data['csrf_hash']
			));
		}
		
		
	}
	function validate_password(){
		
	}
}