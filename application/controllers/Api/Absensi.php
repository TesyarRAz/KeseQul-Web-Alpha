<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends KCOREST_Controller {
	function __construct()
	{
		parent::__construct($this);
		$this->load->model('siswa_model', '', true);
		$this->load->model('absensi_model', '', true);
	}
}