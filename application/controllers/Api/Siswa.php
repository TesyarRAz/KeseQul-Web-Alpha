<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends KCOREST_Controller {

	function __construct()
	{
		parent::__construct($this);

		$this->load->model('siswa_model', '', true);
		$this->load->model('setting_model', '', true);
		$this->load->model('spp_model', '', true);
		$this->load->model('uang_model', '', true);

		$this->load->library('aktifasi');
	}
	public function datauser_get()
	{
		$token = $this->input->get('token');
		$username = $this->input->get('username');

		$user = $this->access->get_access($token);
		$siswa = $this->siswa_model->get_siswa_by_identity($username);

		if ($siswa)
		{
			$this->default_response['status'] = 1;
			$this->default_response['data'] = $siswa;
		}
		else
		{
			$this->default_response['pesan'] = 'Tidak ada siswa yang cocok dengan username itu';
		}
		
		$this->print_response();
	}

	public function data_get()
	{
		$token = $this->input->get('token');
		$user = $this->access->get_access($token);

		if ($this->access->filter_access(['peran' => 'SISWA'], $user['id_user']))
		{
			$siswa = $this->siswa_model->get_siswa_by_user($user['id_user']);

			if ($siswa)
			{
				$this->default_response['status'] = 1;
				$this->default_response['data'] = $siswa;
			}
			else
			{
				$this->default_response['pesan'] = 'Anda tidak terdata sebagai siswa';
			}
		}
		else
		{
			$this->default_response['pesan'] = 'Tidak punya hak akses';
		}
		

		$this->print_response();
	}

	public function spp_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('bulan', 'Bulan', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$bulan = (int) $this->post('bulan');
			$user = $this->access->get_access($token);
			$siswa = $this->siswa_model->get_siswa_by_user($user['id_user']);
			$uang = (int) $this->uang_model->get_uang_by_user($user['id_user']);
			$setting = (int) $this->setting_model
			->get_setting_angkatan_by_nama('HARGA_SPP', $siswa['kelas'], $siswa['id_jurusan']);

			if ($setting)
			{
				$bayar_per_bulan = $setting['nilai'];
				$total = $bulan * $bayar_per_bulan;
				if ($uang >= $total)
				{
					$status = $this->spp_model->bayar_spp($siswa['id_siswa'], $total, $bulan);

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
					$this->default_response['pesan'] = 'Uang anda tidak cukup';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Admin belum mengatur harga spp untuk angkatan anda';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}
		
		$this->print_response();
	}

	public function spp_get()
	{
		$token = $this->input->get('token');
		$type = $this->get('type');
		$limit = $this->get('limit');
		$offset = $this->get('offset');

		$user = $this->access->get_access($token);
		$this->default_response['status'] = 1;
		if (strcmp(strtolower($type), 'detail') == 0)
		{
			$this->default_response['status'] = 1;
			$this->default_response['data'] = $this->spp_model->get_spp_by_user($user['id_user'], $limit, $offset);
		}
		else
		{
			$this->default_response['status'] = 1;
			$this->default_response['data'] = $this->spp_model->get_bulan_spp_by_user($user['id_user']);
		}

		$this->print_response();
	}

	public function tambahsiswa_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation
		// ->set_rules('email', 'Email', 'trim|required|is_unique[tbl_user.email]', $input_failed);
		->set_rules('email', 'Email', 'trim|required|is_unique[tbl_user.email]', $input_failed);
		$this->form_validation->set_rules('nisn', 'NISN', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required', $input_failed);
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required', $input_failed);
		$this->form_validation->set_rules('kelas', 'Kelas', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('id_jurusan', 'ID Jurusan', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('index_jurusan', 'Index Jurusan', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$email = $this->post('email');
			$nisn = $this->post('nisn');
			$nama = $this->post('nama');
			$gender = $this->post('gender');
			$tanggal_lahir = $this->post('tanggal_lahir');
			$kelas = $this->post('kelas');
			$id_jurusan = $this->post('id_jurusan');
			$index_jurusan = $this->post('index_jurusan');
			$gambar = $this->post('gambar');

			$user = $this->access->get_access($token);

			if ($this->access->filter_access(['level' => 'W_SISWA'], $user['id_user']))
			{
				$level = $this->level_model->get_level_by_nama('SISWA');
				if ($level)
				{
					if ($this->user_model->tambah_user($email))
					{
						$register_user = $this->user_model->get_user_by_email($email);

						if ($this->aktifasi->kirim_aktifasi($register_user['email'], $register_user['keterangan']))
						{
							$gambar_location = NULL;

							$image_data = $this->file->upload_gambar('gambar');
							if ($image_data['status'] == 0 && $image_data['pesan'] == NULL)
							{
								$this->user_model->hapus_user($register_user['id_user']);
								$this->default_response['pesan'] = $image_data['pesan'];
								$this->print_response();
								$this->output->_display();
								exit;
							}
							else if ($image_data['pesan'] != NULL)
							{
								$gambar_location = $image_data['pesan'];
							}

							$this->db->trans_begin();
							try
							{
								$this->siswa_model->tambah_siswa(
									$register_user['id_user'],
									$nisn, $nama, $gender, $tanggal_lahir, $kelas, $id_jurusan, $index_jurusan, $gambar_location
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
									$this->default_response['pesan'] = 'Berhasil terdaftar, email sudah sampai';
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

	public function editsiswa_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_user', 'ID User', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules(
			'email', 'Email', 'trim|required|valid_email', $input_failed);
		$this->form_validation->set_rules('nisn', 'NISN', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required', $input_failed);
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required', $input_failed);
		$this->form_validation->set_rules('kelas', 'Kelas', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('id_jurusan', 'ID Jurusan', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('index_jurusan', 'Index Jurusan', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_user = $this->post('id_user');
			$email = $this->post('email');
			$nisn = $this->post('nisn');
			$nama = $this->post('nama');
			$gender = $this->post('gender');
			$tanggal_lahir = $this->post('tanggal_lahir');
			$kelas = $this->post('kelas');
			$id_jurusan = $this->post('id_jurusan');
			$index_jurusan = $this->post('index_jurusan');

			$user = $this->access->get_access($token);

			if ($this->access->filter_access(['level' => 'W_SISWA'], $user['id_user']))
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
					$status = $this->siswa_model->edit_siswa(
						$id_user, $nisn, $nama, $gender, $tanggal_lahir, $kelas, $id_jurusan, $index_jurusan
					);

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
	public function hapussiswa_post()
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
			// CARA AGAR TIDAK BISA EDIT ADMIN
			if ($this->access->filter_access(['peran' => ['ADMIN', 'SUPERADMIN']], $id_user) 
				&& $id_user != $user['id_user'])
			{
				$this->default_response['status'] = 1;
				$this->default_response['pesan'] = 'Tidak bisa hapus admin kau :v';
			}
			elseif ($id_user != $user['id_user'])
			{
				if ($this->access->filter_access(['level' => 'W_SISWA'], $user['id_user']))
				{
					$this->db->trans_begin();
					try
					{
						$peran = $this->peran_model->get_peran_by_user($id_user);
						if ($peran != false && count($peran) > 1)
						{
							$this->peran_model->hapus_peran_by_user($this->level_model->get_level_by_nama('SISWA')['id_level'], $id_user);
						}
						else 
						{
							$this->peran_model->hapus_all_peran_by_user($id_user);
							$this->siswa_model->hapus_siswa_by_user($id_user);
							$this->user_model->hapus_user($id_user);
						}

						if ($this->db->trans_status() == false)
						{
							$this->db->trans_rollback();
							$this->default_response['pesan'] = 'Gagal';
						}
						else
						{
							$this->default_response['status'] = 1;
							$this->default_response['pesan'] = 'Berhasil';
							$this->db->trans_commit();
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
	public function kelas_get()
	{
		$token = $this->input->get('token');

		$user = $this->access->get_access($token);

		$siswa = $this->siswa_model->get_siswa_by_user($user['id_user']);
		if ($siswa)
		{
			$kelas = $siswa['kelas'];
			$id_jurusan = $siswa['id_jurusan'];
			$index_jurusan = $siswa['index_jurusan'];

			$this->default_response['status'] = 1;
			$this->default_response['data'] = $this->siswa_model->get_siswa_by_kelas($kelas, $id_jurusan, $index_jurusan);
		}
		else
		{
			$this->default_response['pesan'] = 'Anda tidak punya hak akses';
		}

		$this->print_response();
	}
}
