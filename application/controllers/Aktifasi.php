<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aktifasi extends CI_Controller {
	function __construct()
	{
		parent::__construct();

		$this->load->model('aktifasi_model', '', true);
	}

	public function user($password_key = NULL)
	{
		$this->load->view('kode_aktifasi_user', ['kode' => $password_key]);
		// if (!empty($password_key))
		// {
		// 	$data = $this->aktifasi_model->get_aktifasi_user_by_identity($password_key);

		// 	if ($data)
		// 	{
		// 		$this->load->view('aktifasi_user');
		// 	}
		// }

		// echo 'Tidak Jelas';
	}
}