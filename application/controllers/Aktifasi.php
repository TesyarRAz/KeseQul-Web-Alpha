<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aktifasi extends CI_Controller {
	function __construct()
	{
		parent::__construct();

		$this->load->database();
	}

	public function aktifkan()
	{
		$input_failed = [
			'required' => '%s tidak boleh kosong',
			'is_unique' => '%s sudah ada yang punya'
		];

		$this->form_validation->set_rules(
			'username', 'Username', 'trim|required|is_unique[tbl_user.username]', $input_failed);
		$this->form_validation->set_rules(
			'password', 'Password', 'trim|required', $input_failed);
		$this->form_validation->set_rules(
			'password_key', 'Password key', 'trim|required', $input_failed);

		$password_key = $this->input->post('password_key');

		if ($this->form_validation->run() == TRUE)
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$data = $this->user_model->get_id_by_aktifasi($password_key);

			if ($data)
			{
				$status = $this->user_model->edit_user($data['id_user'], NULL, 'AKTIF', NULL, $username, md5($password));

				if ($status)
				{
					echo "<h1 align='center'>Berhasil</h1>";
				}
				else
				{
					echo 'Gagal';
				}
			}
			else
			{
				echo 'Kode aktifasi salah';
			}
		}
		else
		{
			if (!empty($password_key))
			{
				$this->load->view('aktifasi_user', ['password_key' => $password_key]);
			}
			else
			{
				echo 'Hacker yah?';
			}
		}

	}

	public function user($password_key = NULL)
	{
		if (!empty($password_key))
		{
			$data = $this->user_model->get_id_by_aktifasi($password_key);

			if ($data)
			{
				$this->load->view('aktifasi_user', ['password_key' => $password_key]);
			}
			else
			{
				echo 'Kode aktifasi mungkin salah';
			}

		}
	}
}