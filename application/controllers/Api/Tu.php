<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TU extends KCOREST_Controller {

	function __construct()
	{
		parent::__construct($this);

		$this->load->model('tu_model', '', true);
		$this->load->model('topup_model', '', true);

		$this->load->library('aktifasi');
	}

	public function data_get()
	{
		$token = $this->input->get('token');
		$user = $this->access->get_access($token);

		if ($this->access->filter_access(['peran' => 'TU'], $user['id_user']))
		{
			$tu = $this->tu_model->get_tu_by_user($user['id_user']);

			if ($tu)
			{
				$this->default_response['status'] = 1;
				$this->default_response['data'] = $tu;
			}
			else
			{
				$this->default_response['pesan'] = 'Anda tidak terdata sebagai TU';
			}
		}
		else
		{
			$this->default_response['pesan'] = 'Tidak punya hak akses';
		}

		$this->print_response();
	}

	public function topup_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_penerima', 'ID Penerima', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('uang_transfer', 'Uang Transfer', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_penerima = (int) $this->post('id_penerima');
			$uang_transfer = (int) $this->post('uang_transfer');

			$user = $this->access->get_access($token);

			if ($id_penerima != $user['id_user'])
			{
				// AGAR MINIMAL TRANSFER 1.000, MENGHINDARI ORANG YANG ISENG MENUHIN DATABASE
				if ($uang_transfer >= 1000)
				{
					$status = $this->topup_model->topup_uang($id_penerima, $user['id_user'], $uang_transfer);

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
					$this->default_response['pesan'] = 'Uang nya harus lebih dari Rp 10.0000';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak boleh topup akun sendiri';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}
		

		$this->print_response();
	}

	public function tambahtu_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka'),
			'is_unique' => $this->lang->line('rest_input_unik')
		];

		$this->form_validation->set_rules(
			'email', 'Email', 'trim|required|valid_email|is_unique[tbl_user.email]', $input_failed);
		$this->form_validation->set_rules('nip', 'NIP', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required', $input_failed);
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');

			$email = $this->post('email');
			$nip = $this->post('nip');
			$nama = $this->post('nama');
			$gender = $this->post('gender');
			$tanggal_lahir = $this->post('tanggal_lahir');
			$gambar = $this->post('gambar');

			$user = $this->access->get_access($token);
			if ($this->access->filter_access(['level' => 'W_TU'], $user['id_user']))
			{
				$level = $this->level_model->get_level_by_nama('TU');
				if ($level)
				{
					if ($this->user_model->tambah_user($email))
					{
						$register_user = $this->user_model->get_user_by_email($email);

						if ($this->aktifasi->kirim_aktifasi($register_user['email'], $register_user['keterangan']))
						{
							$gambar_location = NULL;
							if (!empty($gambar))
							{
								$image_data = $this->file->upload_gambar('gambar');
								if ($image_data['status'] == 0)
								{
									$this->user_model->hapus_user($register_user['id_user']);
									$this->default_response['pesan'] = $image_data['pesan'];
								}
								else
								{
									$gambar_location = $image_data['pesan'];
								}
							}

							$this->db->trans_begin();
							try
							{
								$this->tu_model->tambah_tu(
									$register_user['id_user'], $nip, $nama, $gender, $tanggal_lahir, $gambar_location
								);
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
							$this->default_response['status'] = 0;
							$this->default_response['pesan'] = 'Tidak dapat mengirim email aktifasi';
							$this->user_model->hapus_user($register_user['id_user']);
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


	public function edittu_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka'),
			'is_unique' => $this->lang->line('rest_input_unik')
		];

		$this->form_validation->set_rules('id_user', 'ID User', 'trim|required|integer', $input_failed);
		$this->form_validation
		->set_rules('email', 'Email', 'trim|required|valid_email', $input_failed);
		$this->form_validation->set_rules('nip', 'NIP', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required', $input_failed);
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$email = $this->post('email');
			$id_user = $this->post('id_user');
			$nip = $this->post('nip');
			$nama = $this->post('nama');
			$gender = $this->post('gender');
			$tanggal_lahir = $this->post('tanggal_lahir');

			$user = $this->access->get_access($token);

			if ($this->access->filter_access(['level' => 'W_TU'], $user['id_user']))
			{
				// CARA AGAR TIDAK BISA EDIT ADMIN
				if ($this->access->filter_access(['peran' => ['ADMIN', 'SUPERADMIN']], $id_user) 
					&& $id_user != $user['id_user'])
				{
					$this->default_response['status'] = 1;
					$this->default_response['pesan'] = 'Tidak bisa edit admin kau :v';
				}
				elseif ($this->user_model->edit_user($id_user, $email))
				{
					$status = $this->tu_model->edit_tu($id_user, $nip, $nama, $gender, $tanggal_lahir);
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

	public function hapustu_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka'),
			'is_unique' => $this->lang->line('rest_input_unik')
		];

		$this->form_validation->set_rules('id_user', 'ID User', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_user = $this->post('id_user');
			$user = $this->access->get_access($token);

			// CARA AGAR TIDAK BISA HAPUS KECUALI DIA SENDIRI ADMIN
			if ($this->access->filter_access(['peran' => ['ADMIN', 'SUPERADMIN']], $id_user) 
				&& $id_user != $user['id_user'])
			{
				$this->default_response['status'] = 1;
				$this->default_response['pesan'] = 'Tidak bisa hapus admin kau :v';
			}
			elseif ($this->access->filter_access(['level' => 'W_TU'], $user['id_user']))
			{
				$this->db->trans_begin();
				try
				{
					$peran = $this->peran_model->get_peran_by_user($id_user);
					if ($peran != false && count($peran) > 1)
					{
						$this->peran_model
						->hapus_peran_by_user($this->level_model->get_level_by_nama('TU')['id_level'], $id_user);
					}
					else 
					{
						$this->peran_model->hapus_all_peran_by_user($id_user);
						$this->tu_model->hapus_tu_by_user($id_user);
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
			$this->default_response['pesan'] = validation_errors();
		}
		
		$this->print_response();
	}
}
