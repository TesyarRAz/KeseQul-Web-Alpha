<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

CATATAN KERAS JIKA ADA ERROR YANG SANGAT SULIT,
UNTUK FORM VALIDATION, SAMAKAN DENGAN NAME DARI HTTP REQUEST NYA
CONTOH set_rules('uang_transfer', 'blabla', blabla)
$uang = $this->get('uang_transfer');

JIKA MASIH ERROR, HARUS PERHATIKAN INI

*/

class User extends KCOREST_Controller {

	function __construct()
	{
		parent::__construct($this);

		$this->load->model('uang_model', '', true);
	}

	public function transfer_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_penerima', 'ID Penerima', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('uang_transfer', 'Uang', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_penerima = (int) $this->post('id_penerima');
			$uang = (int) $this->post('uang_transfer');

			$user = $this->access->get_access($token);

			if ($user['id_user'] != $id_penerima)
			{
				$my_uang = $this->uang_model->get_uang_by_user($user['id_user']);
				if ($my_uang >= $uang)
				{
					$status = $this->uang_model->transfer_uang($user['id_user'], $id_penerima, $uang);

					if ($status)
					{
						$this->default_response['status'] = 1;
						$this->default_response['pesan'] = 'Berhasil';
					}
					else
					{
						$this->default_response['pesan'] = 'Gagal';
					}
				}
				else
				{
					$this->default_response['pesan'] = 'Tidak bisa transfer, uang anda kurang';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak bisa transfer ke diri sendiri';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}

		$this->print_response();
	}

	public function login_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong')
		];

		$this->form_validation->set_rules('username', 'Username', 'required', $input_failed);
		$this->form_validation->set_rules('password', 'Password', 'required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$username = $this->post('username');
			$password = $this->post('password');

			$user = $this->user_model->get_user_by_identity($username, $password);
			if ($user)
			{
				if (strcmp($user['status'], 'AKTIF') == 0)
				{
					unset($user['password']);

					$this->default_response['status'] = 1;

					$this->default_response['data'] = $user;
					$this->default_response['data']['token'] = $this->token_model->generate_token($user['id_user']);
					$this->default_response['data']['peran'] = $this->peran_model->get_peran_by_user($user['id_user']);
					$this->default_response['data']['uang'] = $this->uang_model->get_uang_by_user($user['id_user']);
				}
				else if (strcmp($user['status'], 'BAN'))
				{
					$this->default_response['pesan'] = 'Akun anda di ban, pesan admin : ' . $user['keterangan'];
				}
				else
				{
					$this->default_response['pesan'] = 'Akun anda belum diaktifasi';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Username atau password salah';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}

		$this->print_response();
	}

	public function data_get()
	{
		$token = $this->input->get('token');

		$user = $this->access->get_access($token);

		unset($user['password']);

		$this->default_response['status'] = 1;
		$this->default_response['data'] = $user;
		$this->default_response['data']['token'] = $token;
		$this->default_response['data']['peran'] = $this->peran_model->get_peran_by_user($user['id_user']);
		$this->default_response['data']['uang'] = $this->uang_model->get_uang_by_user($user['id_user']);

		$this->print_response();
	}

	public function ban_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];
		$this->form_validation->set_rules('id_user', 'ID User', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('reason_ban', 'Alasan Ban', 'trim|required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$target_ban = $this->post('id_user');
			$reason_ban = $this->post('reason_ban');
			$user = $this->access->get_access($token);

			if ($target_ban != $user['id_user'])
			{
				$target_user = $this->user_model->get_user_by_id($target_ban);
				if ($target_user)
				{
					if (strcmp($target_user['status'], 'AKTIF') == 0)
					{
						if ($this->access->filter_access(['peran' => ['SUPERADMIN', 'ADMIN']], $user['id_user']))
						{
							// ANTI BAN ADMIN DAN SUPERADMIN B)
							if (!$this->access->filter_access(['peran' => ['SUPERADMIN', 'ADMIN']], $target_ban))
							{

								$status = $this->user_model->ban_user($target_ban, $reason_ban);

								if ($status)
								{
									$this->default_response['status'] = 1;
									$this->default_response['pesan'] = 'Berhasil';
								}
								else
								{
									$this->default_response['pesan'] = 'Gagal';
								}
							}
							else
							{
								$this->default_response['pesan'] = 'Tidak bisa ban admin atau superadmin';
							}
						}
						else
						{
							$this->default_response['pesan'] = 'Anda tidak punya hak akses';
						}
					}
					else
					{
						$this->default_response['pesan'] = 'User sudah diban atau belum terdaftar';
					}
				}
				else
				{
					$this->default_response['pesan'] = 'User tidak ada';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak bisa ban diri sendiri';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}

		$this->print_response();
	}

	public function ban_get()
	{
		$token = $this->input->get('token');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');

		$user = $this->access->get_access($token);

		if ($this->access->filter_access(['peran' => ['SUPERADMIN', 'ADMIN']], $user['id_user']))
		{
			$ban_list = $this->user_model->ban_list($limit, $offset);

			if ($ban_list)
			{

				$this->default_response['status'] = 1;
				$this->default_response['data'] = $ban_list;
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak ada user yang di ban';
			}
		}
		else
		{
			$this->default_response['pesan'] = 'Anda tidak punya hak akses';
		}

		$this->print_response();
	}

	public function unban_get()
	{
		$token = $this->input->get('token');
		$username = $this->get('username');

		if (!empty($username))
		{
			$user = $this->access->get_access($token);

			if ($this->access->filter_access(['peran' => ['SUPERADMIN', 'ADMIN']], $user['id_user']))
			{
				$target_unban = $this->user_model->get_user_by_username($username);
				if (strcmp($target_unban['status'], 'BAN') == 0)
				{
					unset($target_unban['password']);

					$this->default_response['status'] = 1;
					$this->default_response['data'] = $target_unban;
				}
				else
				{
					$this->default_response['pesan'] = 'Dia tidak sedang di ban';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Anda tidak punya akses';
			}
		}
		else
		{
			$this->default_response['pesan'] = 'Username tidak boleh kosong';
		}

		$this->print_response();
	}

	public function unban_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];
		$this->form_validation->set_rules('id_user', 'ID User', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');

			$user = $this->access->get_access($token);
			$target_ban = $this->post('id_user');

			if ($target_ban != $user['id_user'])
			{
				$target_user = $this->user_model->get_user_by_id($target_ban);
				if ($this->access->filter_access(['peran' => ['SUPERADMIN', 'ADMIN']], $user['id_user']))
				{
					if ($target_user)
					{
						if (strcmp($target_user['status'], 'BAN') == 0)
						{
							$status = $this->user_model->unban_user($target_ban);

							if ($status)
							{
								$this->default_response['status'] = 1;
								$this->default_response['pesan'] = 'Berhasil';
							}
							else
							{
								$this->default_response['pesan'] = 'Gagal';
							}
						}
						else
						{
							$this->default_response['pesan'] = 'Dia tidak dalam status di ban';
						}
					}
					else
					{
						$this->default_response['pesan'] = 'User tidak ada';
					}
				}
				else
				{
					$this->default_response['pesan'] = 'Anda tidak punya hak akses';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak bisa unban diri sendiri';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}

		$this->print_response();
	}

	public function cekusername_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('username', 'Username', 'trim|required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$username = $this->post('username');

			$user = $this->access->get_access($token);
			$id_user = $this->user_model->get_id_by_username($username);

			if ($id_user) {
				// INI HANYA MENGIRIM KAN 1 DATA JADI DI PESAN AJA
				$this->default_response['status'] = 1;
				$this->default_response['pesan'] = $id_user;
			}
			else
			{
				$this->default_response['pesan'] = 'Username tidak terdaftar';
			}
		}
		else
		{
			$this->default_response['pesan'] = 'Username tidak boleh kosong';
		}

		$this->print_response();
	}

	// FUNGSI UNTUK MEMPERCEPAT SERVER DENGAN MEREKAP TRANSAKSI
	public function rekaptopup_post()
	{
		
	}
}