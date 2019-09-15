<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurusan extends KCOREST_Controller {
	function __construct()
	{
		parent::__construct($this);
		
		$this->load->model('jurusan_model', '', true);
	}

	public function all_get()
	{
		$limit = (int) $this->input->get('limit');
		$offset = (int) $this->input->get('offset');

		$this->default_response['status'] = 1;
		$this->default_response['data'] = $this->jurusan_model->get_all_jurusan($limit, $offset);

		$this->print_response();
	}
}