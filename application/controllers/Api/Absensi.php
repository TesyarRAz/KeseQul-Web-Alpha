<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends KCOREST_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('siswa_model', '', true);
		$this->load->model('absensi_model', '', true);
	}
}