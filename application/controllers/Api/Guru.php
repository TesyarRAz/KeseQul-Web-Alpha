<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru extends KCOREST_Controller {
	
	function __construct()
	{
		parent::__construct($this);

		$this->load->model('guru_model', '', true);
	}

	public function all_get()
	{
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');

		$this->default_response['status'] = 1;
		$this->default_response['data'] = $this->guru_model->get_all_guru($limit, $offset);
		
		$this->print_response();
	}

	public function data_get()
	{
		if ($this->access->filter_access(['peran' => 'GURU'], $user['id_user']))
		{
			$guru = $this->guru_model->get_guru_by_user($user['id_user']);

			if ($guru)
			{
				$this->default_response['status'] = 1;
				$this->default_response['data'] = $guru;
			}
			else
			{
				$this->default_response['pesan'] = 'Anda tidak terdata sebagai Guru';
			}
		}
		else
		{
			$this->default_response['pesan'] = 'Tidak punya hak akses';
		}
		

		$this->print_response();
	}

	public function tambahguru_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka'),
			'is_unique' => $this->lang->line('rest_input_unik')
		];

		$this->form_validation
		->set_rules('username', 'Username', 'trim|required|is_unique[tbl_user.username]', $input_failed);
		$this->form_validation
		->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_user.email]', $input_failed);
		$this->form_validation->set_rules('password', 'Password', 'trim|required', $input_failed);
		$this->form_validation->set_rules('nip', 'NIP', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required', $input_failed);
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$nip = $this->post('nip');
			$email = $this->post('email');
			$username = $this->post('username');
			$password = $this->post('password');
			$id_user = $this->post('id_user');
			$nama = $this->post('nama');
			$gender = $this->post('gender');
			$tanggal_lahir = $this->post('tanggal_lahir');

			$user = $this->access->get_access($token);
			if ($this->access->filter_access(['level' => 'W_GURU'], $user['id_user']))
			{
				$level = $this->level_model->get_level_by_nama('GURU');
				if ($level)
				{
					if ($this->user_model->tambah_user($email, $username, $password))
					{
						$register_user = $this->user_model
						->get_user_by_identity($username, $password);

						$this->db->trans_begin();
						try
						{
							$this->guru_model->tambah_guru(
								$register_user['id_user'], $nip, $nama, $gender, $tanggal_lahir);
							$this->peran_model->tambah_peran(
								$register_user['id_user'], $level['id_level'], $user['id_user']
							);

							if ($this->db->trans_status() == false)
							{
								$this->db->trans_rollback();
								$this->user_model->hapus_user($register_user['id_user']);
								$this->default_response['pesan'] = 'Gagal';
							}
							else
							{
								$this->db->trans_commit();
								$this->default_response['status'] = 1;
								$this->default_response['pesan'] = 'Berhasil';
							}
						}
						catch (Exception $ex)
						{
							$this->db->trans_rollback();
							$this->user_model->hapus_user($register_user['id_user']);
							$this->default_response['pesan'] = 'Gagal';
						}
					}
					else
					{
						$this->user_model->hapus_user($register_user['id_user']);
						$this->default_response['pesan'] = 'Gagal';
					}
				}
				else
				{
					$this->default_response['pesan'] = 'Tidak ada level dengan nama itu';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak punya hak akses';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}
		
		$this->print_response();
	}

	public function editguru_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka'),
			'is_unique' => $this->lang->line('rest_input_unik')
		];

		$this->form_validation->set_rules('id_user', 'ID User', 'trim|required|integer', $input_failed);
		$this->form_validation
		->set_rules('username', 'Username', 'trim|required', $input_failed);
		$this->form_validation
		->set_rules('email', 'Email', 'trim|required|valid_email', $input_failed);
		$this->form_validation->set_rules('password', 'Password', 'trim|required', $input_failed);
		$this->form_validation->set_rules('nip', 'NIP', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required', $input_failed);
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_user = $this->post('id_user');
			$nip = $this->post('nip');
			$email = $this->post('email');
			$username = $this->post('username');
			$password = $this->post('password');
			$id_user = $this->post('id_user');
			$nama = $this->post('nama');
			$gender = $this->post('gender');
			$tanggal_lahir = $this->post('tanggal_lahir');

			$user = $this->access->get_access($token);

			if ($this->access->filter_access(['level' => 'W_GURU'], $user['id_user']))
			{
				// CARA AGAR TIDAK BISA EDIT ADMIN
				if ($this->access->filter_access(['peran' => ['ADMIN', 'SUPERADMIN']], $id_user) 
					&& $id_user != $user['id_user'])
				{
					$this->default_response['status'] = 1;
					$this->default_response['pesan'] = 'Tidak bisa edit admin kau :v';
				}
				elseif ($this->user_model->edit_user($id_user, $email, $username, $password))
				{
					$status = $this->guru_model->edit_guru($id_user, $nip, $nama, $gender, $tanggal_lahir);
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
					$this->default_response['pesan'] = 'Gagal';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak punya hak akses';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}
		
		$this->print_response();
	}
	public function hapusguru_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_user', 'ID User', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_user = $this->post('id_user');
			$user = $this->access->get_access($token);

			// CARA AGAR TIDAK BISA HAPUS ADMIN
			if ($this->access->filter_access(['peran' => ['ADMIN', 'SUPERADMIN']], $id_user) 
				&& $id_user != $user['id_user'])
			{
				$this->default_response['status'] = 1;
				$this->default_response['pesan'] = 'Tidak bisa hapus admin kau :v';
			}
			elseif ($id_user != $user['id_user'])
			{
				if ($this->access->filter_access(['level' => 'W_GURU'], $user['id_user']))
				{
					$this->db->trans_begin();
					try
					{
						$peran = $this->peran_model->get_peran_by_user($id_user);
						if ($peran != false && count($peran) > 1)
						{
							$this->peran_model
							->hapus_peran_by_user($this->level_model->get_level_by_nama('GURU')['id_level'], $id_user);
						}
						else 
						{
							$this->peran_model->hapus_all_peran_by_user($id_user);
							$this->guru_model->hapus_guru_by_user($id_user);
							$this->user_model->hapus_user($id_user);
						}

						if ($this->db->trans_status() == false)
						{
							$this->db->trans_rollback();
							$this->default_response['pesan'] = 'Gagal';
						}
						else
						{
							$this->db->trans_commit();
							$this->default_response['status'] = 1;
							$this->default_response['pesan'] = 'Berhasil';
						}
					}
					catch (Exception $ex)
					{
						$this->db->trans_rollback();
						$this->default_response['pesan'] = 'Gagal';
					}
				}
				else
				{
					$this->default_response['pesan'] = 'Tidak punya hak akses';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak bisa hapus diri sendiri';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}
		
		$this->print_response();
	}
}
