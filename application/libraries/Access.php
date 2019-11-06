<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access {
	private $default_response = [
		'status' => 3,
		'pesan' => '',
		'data' => NULL
	];

	function __construct()
	{
		$this->instance =& get_instance();
	}

	public function get_access($token)
	{
		if (!empty($token))
		{
			if (!$this->instance->token_model->valid_token($token))
			{
				$this->default_response['pesan'] = 'Token kadaluarsa atau anda tidak mempunyai hak akses';

				$this->instance->response($this->default_response, 200);
				$this->instance->output->_display();
				exit;
			}

			$user = $this->instance->user_model->get_user_by_identity($token);

			if ($user)
			{
				if (strcmp($user['status'], 'AKTIF') == 0)
				{
					return $user;
				}
				else
				{
					$this->default_response['pesan'] = 'Akun anda di ban, pesan admin : ' . $user['keterangan'];

					$this->instance->response($this->default_response, 200);
					$this->instance->output->_display();
					exit;
				}
			}
		}

		$this->default_response['pesan'] = 'Anda tidak punya hak akses';
		$this->instance->response($this->default_response, 200);
		$this->instance->output->_display();
		exit;
	}

	public function get_access_two($token)
	{
		if (!empty($token))
		{
			if (!$this->instance->token_model->valid_token($token))
			{
				echo 'Token kadaluarsa atau anda tidak mempunyai hak akses';
				exit;
			}

			$user = $this->instance->user_model->get_user_by_identity($token);

			if ($user)
			{
				if (strcmp($user['status'], 'AKTIF') == 0)
				{
					return $user;
				}
				else
				{
					echo $this->default_response['pesan'] = 'Akun anda di ban, pesan admin : ' . $user['keterangan'];
					exit;
				}
			}
		}

		echo 'Anda tidak punya hak akses';
		exit;
	}

	public function filter_access($access, $id_user)
	{
		$peran = $this->instance->peran_model->get_peran_by_user($id_user);
		foreach ($access as $filter => $nama_access) {
			if (!is_array($nama_access))
				$nama_access = [ $nama_access ];

			$required = 0;
			$valid = 0;

			if (strcmp(strtolower($filter), 'peran') == 0)
			{
				$required++;
				foreach ($nama_access as $key)
				{
					if ($this->filter_access_peran($key, $peran, $id_user))
					{
						$valid++;
						break;
					}
				}
			}
			
			if (strcmp(strtolower($filter), 'level') == 0)
			{
				$required++;
				foreach ($nama_access as $key)
				{
					if ($this->filter_access_level($key, $peran, $id_user))
					{
						$valid++;
						break;
					}
				}
			}

			return $valid >= $required;
		}

		return false;
	}

	public function filter_access_peran($nama_peran, $peran = NULL, $id_user = NULL)
	{
		if (empty($peran))
		{
			if (empty($id_user))
				return false;

			$peran = $this->instance->peran_model->get_peran_by_user($id_user);
		}

		foreach ($peran as $key)
		{
			if (strcmp($key['nama_level'], $nama_peran) == 0)
				return true;
		}

		return false;
	}

	public function filter_access_level($nama_level, $peran = NULL, $id_user = NULL)
	{
		if (empty($peran))
		{
			if (empty($id_user))
				return false;

			$peran = $this->instance->peran_model->get_peran_by_user($id_user);
		}

		foreach ($peran as $key)
		{
			$level[] = $key['id_level'];
		}

		// Algoritma Untuk mendapatkan izin-izin dari beberapa level
		$sql = $this->instance->db
		->select('tbl_izin.nama AS nama_izin')
		->from('tbl_izin_level')
		->join('tbl_izin', 'tbl_izin.id_izin = tbl_izin_level.id_izin', 'LEFT')
		->where_in('tbl_izin_level.id_level', $level)
		->group_by('tbl_izin.nama')
		->get();

		if ($sql->num_rows() > 0)
		{
			foreach ($sql->result_array() as $key) {
				if (strcmp($nama_level, $key['nama_izin']) == 0)
					return true;
			}
		}

		return false;
	}
}