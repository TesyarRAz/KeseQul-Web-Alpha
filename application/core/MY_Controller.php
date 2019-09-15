<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
}

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class KCOREST_Controller extends REST_Controller {
	function __construct($instance)
	{
		parent::__construct();
		
		$this->child_instance = $instance;
		$this->child_instance->default_response = [
			'status' => 0,
			'pesan' => '',
			'data' => NULL
		];

		$this->form_validation->set_error_delimiters('', '');
		$this->lang->load('kesequl_rest', 'indonesia');
		$this->load->database();
	}

	public function index_get()
	{
		$this->default_response['pesan'] = 'URL mungkin salah';
		$this->print_response();
	}

	public function print_response($status_code = 200)
	{
		$this->response($this->child_instance->default_response, $status_code);
	}
}