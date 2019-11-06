<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

CATATAN KERAS JIKA ADA ERROR YANG SANGAT SULIT,
UNTUK FORM VALIDATION, SAMAKAN DENGAN NAME DARI HTTP REQUEST NYA
CONTOH set_rules('uang_transfer', 'blabla', blabla)
$uang = $this->get('uang_transfer');

JIKA MASIH ERROR, HARUS PERHATIKAN INI

*/

class Voting extends KCOREST_Controller {
	function __construct()
	{
		parent::__construct($this);

		$this->load->model('voting_model', '', true);
		$this->load->model('admin_model', '', true);
		$this->load->model('siswa_model', '', true);
	}

	public function event_get()
	{
		$token = $this->input->get('token');
		$type = $this->input->get('as');

		$user = $this->access->get_access($token);
		$admin = $this->admin_model->get_admin_by_user($user['id_user']);

		if ($admin && strcmp($type, 'admin') == 0)
		{
			$data = $this->voting_model->get_event_by_pembuat($admin['id_admin']);
			if ($data)
			{
				$this->default_response['status'] = 1;
				$this->default_response['data'] = $data;
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak Ada Event Dengan password itu';
			}
		}

		if ($this->default_response['status'] == 0)
		{
		    $this->form_validation->set_data(['password' => $this->input->get('password')]);
			$this->form_validation->set_rules('password', 'Password', 'trim|required', 
				['required' => $this->lang->line('rest_input_kosong')]
			);

			if ($this->form_validation->run() == TRUE)
			{
				$password = $this->input->get('password');
				$data = $this->voting_model->get_event_by_identity($password);
				if ($data)
				{
					if ($data['status'] == true)
					{
						$this->default_response['status'] = 1;
						$this->default_response['data'] = $data;
					}
					else
					{
						$this->default_response['pesan'] = 'Event sudah selesai';
					}
				}
				else
				{
					$this->default_response['pesan'] = 'Tidak Ada Event Dengan password itu';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Password tidak boleh kosong';
			}
		}

		$this->print_response();
	}

	public function tambahevent_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('nama_event', 'Nama Event', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_selesai', 'Tanggal Selesai', 'trim|required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$nama_event = $this->post('nama_event');
			$tanggal_mulai = $this->post('tanggal_mulai');
			$tanggal_selesai = $this->post('tanggal_selesai');

			$user = $this->access->get_access($token);
			if ($this->access->filter_access(['peran', ['SUPERADMIN', 'ADMIN']], $user['id_user']))
			{
				$admin = $this->admin_model->get_admin_by_user($user['id_user']);
				if ($admin)
				{
					$status = $this->voting_model->tambah_event($admin['id_admin'], $nama_event, $tanggal_mulai, $tanggal_selesai);

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
					$this->default_response['pesan'] = 'Anda admin yang belum terdata';
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

	public function statusevent_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_event_voting', 'ID Event Voting', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('status', 'Status Event Voting', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_event_voting = $this->post('id_event_voting');
			$status = ((int) $this->post('status')) > 0;
			
			$user = $this->access->get_access($token);
			if ($this->access->filter_access(['peran', ['SUPERADMIN', 'ADMIN']], $user['id_user']))
			{
				$event_voting = $this->voting_model->get_event_by_id($id_event_voting);

				if ($event_voting)
				{
					$admin = $this->admin_model->get_admin_by_user($user['id_user']);

					if ($admin)
					{
						if ($event_voting['id_pembuat'] == $admin['id_admin'])
						{
							$status = $this->db
							->update('tbl_event_voting', 
								['status' => $status],
								['id_event_voting' => $id_event_voting]) > 0;

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
							$this->default_response['pesan'] = 'Event dibuat oleh admin lain, tidak bisa anda hapus';
						}
					}
					else
					{
						$this->default_response['pesan'] = 'Anda admin yang belum terdata';
					}
				}
				else
				{
					$this->default_response['pesan'] = "Tidak ditemukan event dengan ID itu";
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

	public function editevent_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_event_voting', 'ID Event Voting', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('nama_event', 'Nama Event', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim|required', $input_failed);
		$this->form_validation->set_rules('tanggal_selesai', 'Tanggal Selesai', 'trim|required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_event_voting = $this->post('id_event_voting');
			$nama_event = $this->post('nama_event');
			$tanggal_mulai = $this->post('tanggal_mulai');
			$tanggal_selesai = $this->post('tanggal_selesai');

			$user = $this->access->get_access($token);
			if ($this->access->filter_access(['peran', ['SUPERADMIN', 'ADMIN']], $user['id_user']))
			{
				$event_voting = $this->voting_model->get_event_by_id($id_event_voting);

				if ($event_voting)
				{
					$admin = $this->admin_model->get_admin_by_user($user['id_user']);

					if ($admin)
					{
						if ($event_voting['id_pembuat'] == $admin['id_admin'])
						{
							$status = $this->db
							->update('tbl_event_voting', 
								[
									'nama' => $nama_event,
									'tanggal_mulai' => $tanggal_mulai,
									'tanggal_selesai' => $tanggal_selesai
								],
								[
									'id_event_voting' => $id_event_voting
								]) > 0;

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
							$this->default_response['pesan'] = 'Event dibuat oleh admin lain, tidak bisa anda hapus';
						}
					}
					else
					{
						$this->default_response['pesan'] = 'Anda admin yang belum terdata';
					}
				}
				else
				{
					$this->default_response['pesan'] = "Tidak ditemukan event dengan ID itu";
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

	public function hapusevent_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_event_voting', 'ID Event Voting', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_event_voting = $this->post('id_event_voting');

			$user = $this->access->get_access($token);
			if ($this->access->filter_access(['peran', ['SUPERADMIN', 'ADMIN']], $user['id_user']))
			{
				$event_voting = $this->voting_model->get_event_by_id($id_event_voting);

				if ($event_voting)
				{
					$admin = $this->admin_model->get_admin_by_user($user['id_user']);

					if ($admin)
					{
						if ($event_voting['id_pembuat'] == $admin['id_admin'])
						{
							$this->db->trans_begin();
							try {
								$this->db->delete('tbl_voting', ['id_event_voting' => $id_event_voting]);
								$this->db->delete('tbl_nominasi_team', ['id_event_voting' => $id_event_voting]);
								$this->db->delete('tbl_event_voting', ['id_event_voting' => $id_event_voting]);


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
							$this->default_response['pesan'] = 'Event dibuat oleh admin lain, tidak bisa anda hapus';
						}
					}
					else
					{
						$this->default_response['pesan'] = 'Anda admin yang belum terdata';
					}
				}
				else
				{
					$this->default_response['pesan'] = "Tidak ditemukan event dengan ID itu";
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

	public function team_get()
	{
		$token = $this->input->get('token');
		$id_event_voting = $this->get('id_event_voting');
		$type = $this->input->get('as');

		$user = $this->access->get_access($token);
		$admin = $this->admin_model->get_admin_by_user($user['id_user']);

		if ($admin && strcmp($type, 'admin') == 0)
		{
			$data = $this->voting_model->get_team_by_pembuat($id_event_voting, $admin['id_admin']);
			if ($data)
			{
				$this->default_response['status'] = 1;
				$this->default_response['data'] = $data;
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak ada team dengan id itu';
			}
		}
		
		if ($this->default_response['status'] == 0)
		{
			$this->form_validation->reset_validation();
			$this->form_validation->set_data(['password' => $this->input->get('password')]);
			$this->form_validation->set_rules('password', 'Password', 'trim|required', 
				['required' => $this->lang->line('rest_input_kosong')]
			);

			if ($this->form_validation->run() == TRUE)
			{
				$password = $this->input->get('password');

				$event_voting = $this->voting_model->get_event_by_id($id_event_voting);

				if ($event_voting)
				{
					$data = $this->voting_model->get_team_by_identity($id_event_voting, $password);

					if ($data)
					{
						if ($event_voting['status'] == TRUE)
						{
							$this->default_response['status'] = 1;
							$this->default_response['data'] = $data;
						}
						else
						{
							$this->default_response['pesan'] = 'Event sudah selesai';
						}
					}
					else
					{
						$this->default_response['pesan'] = 'Tidak ditemukan Kandidat';
					}
				}
				else
				{
					$this->default_response['pesan'] = 'Tidak ditemukan event dengan id itu';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Inputan tidak boleh kosong';
			}
		}

		$this->print_response();
	}

	// ADA BUG
	// DIMANA SISWA YANG MENJADI WAKIL ATAU KETUA DARI TIM LAIN
	// BISA MENJADI KETUA ATAU WAKIL DARI TIM YANG LAIN JUGA
	public function tambahteam_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_event_voting', 'ID Event Voting', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('nama', 'Nama Team', 'trim|required', $input_failed);
		$this->form_validation->set_rules('id_ketua', 'ID Ketua', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('id_wakil', 'ID Wakil', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_event_voting = $this->post('id_event_voting');
			$nama = $this->post('nama');
			$id_ketua = $this->post('id_ketua');
			$id_wakil = $this->post('id_wakil');

			if ($id_ketua != $id_wakil)
			{
				$event_voting = $this->voting_model->get_event_by_id($id_event_voting);

				if ($event_voting)
				{
				    $user = $this->access->get_access($token);
				    
					$admin = $this->admin_model->get_admin_by_user($user['id_user']);

					if ($admin)
					{
						if ($event_voting['id_pembuat'] == $admin['id_admin'])
						{
							$status = $this->voting_model
							->tambah_team($id_event_voting, $nama, $id_ketua, $id_wakil);

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
							$this->default_response['pesan'] = 'Event dibuat oleh admin lain, tidak bisa anda hapus';
						}
					}
					else
					{
						$this->default_response['pesan'] = 'Anda admin yang belum terdata';
					}
				}
				else
				{
					$this->default_response['pesan'] = "Tidak ditemukan dengan ID itu";
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Ketua Dan Wakil harus berbeda';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}

		$this->print_response();
	}

	public function editteam_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_team', 'ID Team', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('nama', 'Nama Team', 'trim|required', $input_failed);
		$this->form_validation->set_rules('id_ketua', 'ID Ketua', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('id_wakil', 'ID Wakil', 'trim|required|integer', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_team = $this->post('id_team');
			$nama = $this->post('nama');
			$id_ketua = $this->post('id_ketua');
			$id_wakil = $this->post('id_wakil');

			$user = $this->access->get_access($token);

			if ($id_ketua != $id_wakil)
			{
				$event_team = $this->voting_model->get_team_by_id($id_team);
				if ($event_team)
				{
					$event_voting = $this->voting_model->get_event_by_id($event_team['id_event_voting']);

					if ($event_voting)
					{
						$admin = $this->admin_model->get_admin_by_user($user['id_user']);

						if ($admin)
						{
							if ($event_voting['id_pembuat'] == $admin['id_admin'])
							{
								$status = $this->db->update('tbl_nominasi_team', 
									[
										'nama' => $nama,
										'id_ketua' => $id_ketua,
										'id_wakil'=> $id_wakil
									],
									[
										'id_nominasi_team' => $id_team
									]
								) > 0;

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
								$this->default_response['pesan'] = 'Event dibuat oleh admin lain, tidak bisa anda hapus';
							}
						}
						else
						{
							$this->default_response['pesan'] = 'Anda admin yang belum terdata';
						}
					}
					else
					{
						$this->default_response['pesan'] = "Tidak ditemukan dengan ID itu";
					}
				}
				else
				{
					$this->default_response['pesan'] = "Tidak ditemukan team dengan ID itu";
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Ketua Dan Wakil harus berbeda';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}

		$this->print_response();
	}

	public function hapusteam_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_team', 'ID Team', 'trim|required|integer', $input_failed);
		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$id_team = $this->post('id_team');


			$event_team = $this->voting_model->get_team_by_id($id_team);
			if ($event_team)
			{
				$event_voting = $this->voting_model->get_event_by_id($event_team['id_event_voting']);
				
				// BUG ADA TEAM TIDAK ADA EVENT
				if ($event_voting)
				{
				    $user = $this->access->get_access($token);
				    
					$admin = $this->admin_model->get_admin_by_user($user['id_user']);

					if ($event_voting['id_pembuat'] == $admin['id_admin'])
					{
						$this->db->trans_begin();
						try 
						{
							$this->db->delete('tbl_voting', ['id_event_voting' => $event_team['id_event_voting']]);
							$this->db->delete('tbl_nominasi_team', ['id_nominasi_team' => $id_team]);

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
						$this->default_response['pesan'] = 'Event dibuat oleh admin lain, tidak bisa anda hapus';
					}
				}
				else
				{
					$this->default_response['pesan'] = 'Ada bug mohon hubungi admin';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak ada team dengan id itu';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}

		$this->print_response();
	}

	public function coblos_post()
	{
		$input_failed = [
			'required' => $this->lang->line('rest_input_kosong'),
			'integer' => $this->lang->line('rest_input_angka')
		];

		$this->form_validation->set_rules('id_team_pilihan', 'ID Team Pilihan', 'trim|required|integer', $input_failed);
		$this->form_validation->set_rules('password', 'Password', 'trim|required', $input_failed);

		if ($this->form_validation->run() == TRUE)
		{
			$token = $this->input->get('token');
			$password = $this->post('password');
			$id_team = $this->post('id_team_pilihan');

			$user = $this->access->get_access($token);
			$event = $this->voting_model->get_event_by_identity($password);

			if ($event)
			{
				$siswa = $this->siswa_model->get_siswa_by_user($user['id_user']);
				if ($siswa)
				{
					if ($event['status'] == true)
					{
						$status = $this->voting_model
						->coblos($event['id_event_voting'], $siswa['id_siswa'], $id_team);

						if ($status)
						{
							$this->default_response['status'] = 1;
							$this->default_response['pesan'] = 'Berhasil, Terima kasih sudah menggunakan hak pilih anda';
						}
						else
						{
							$this->default_response['pesan'] = 'Mohon maaf tapi anda sudah pernah nyoblos';
						}
					}
					else
					{
						$this->default_response['pesan'] = 'Event sudah selesai';
					}
				}
				else
				{
					$this->default_response['pesan'] = 'Anda bukan siswa';
				}
			}
			else
			{
				$this->default_response['pesan'] = 'Tidak ada event dengan password itu';
			}
		}
		else
		{
			$this->default_response['pesan'] = validation_errors();
		}

		$this->print_response();
	}
}