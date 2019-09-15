<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {
	function __construct()
	{
		parent::__construct();

		$this->load->library('upload');
	}

	// BARU NIAT
	public function gambar()
	{
		$user = $this->access->get_access($this->input->get('token'));

		if (false)
		{
			$config['upload_path'] = base_url('assets/upload/image/');
	        $config['allowed_types'] = 'jpg|png';
	        $config['max_size'] = 100;
	        $config['max_width'] = 1024;
	        $config['max_height'] = 768;
	        $config['file_name']

	        $this->upload->initialize($config);

	        if ( ! $this->upload->do_upload('userfile'))
	        {
	                $error = array('error' => $this->upload->display_errors());

	                $this->load->view('upload_form', $error);
	        }
	        else
	        {
	                $data = array('upload_data' => $this->upload->data());

	                $this->load->view('upload_success', $data);
	        }
		}
	}
}