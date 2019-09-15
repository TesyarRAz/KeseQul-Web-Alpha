<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aktifasi {

	function __construct()
	{
		$this->instance =& get_instance();

		$this->instance->load->library('email', 
			[
				'smtp_host' => 'localhost',
				'smtp_user' => 'info',
				'smtp_pass' => 'greget6565',
				'mailtype' => 'html'
			]
		);
	}

	public function kirim_aktifasi($email, $kode) {
		$this->instance->email->from('info@localhost', 'KeseQul');
		$this->instance->email->to($email);
		$this->instance->email->subject('Aktifasi Akun');
		$this->instance->email->message($this->instance->load->view('kode_aktifasi_user', ['kode' => $kode], true));
		return $this->instance->email->send();
	}

}