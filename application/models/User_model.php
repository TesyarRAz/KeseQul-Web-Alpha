<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

Catatan :
Jika saat upload ke hosting bermasalah,
nah itu adalah web nya waktu nya tidak sesuai dengan waktu sql nya

*/

class User_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->helper('date');
	}

	public function get_id_by_aktifasi($password_key)
	{
		$sql = $this->db->select('id_user')->get_where('tbl_user', ['keterangan' => $password_key, 'status' => 'AKTIFASI']);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_id_by_username($username)
	{
		$sql = $this->db->select('id_user')->from('tbl_user')->where('username', $username)->get();

		return $sql->num_rows() > 0 ? $sql->row_array()['id_user'] : false;
	}

	public function get_user_by_username($username)
	{
		$sql = $this->db->get_where('tbl_user', ['username' => $username]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_user_by_id($id_user)
	{
		$sql = $this->db->get_where('tbl_user', ['id_user' => $id_user]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_user_by_email($email)
	{
		$sql = $this->db->get_where('tbl_user', ['email' => $email]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_user_by_identity($username_or_token, $password = '')
	{
		$this->db
		->select('tbl_user.*')
		->from('tbl_user');

		if (empty($password))
		{
			$this->db
			->join('tbl_token', 'tbl_token.id_user = tbl_user.id_user')
			->where('tbl_token.token', $username_or_token);
		}
		else
		{
			$this->db
			->where('username', $username_or_token)
			->where('password', md5($password));
		}

		$sql = $this->db->get();

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function tambah_user($email)
	{
		return $this->db->insert('tbl_user', 
			[
				'username' => md5('u' . date('YmdHis')),
				'password' => md5('p' . date('YmdHis')),
				'email' => $email,
				'status' => 'AKTIFASI',
				'keterangan' => md5(random_string('alnum', 32) . date('YmdHis'))
			]
		) > 0;
	}

	public function hapus_user($id_user)
	{
		return 
		$this->db->delete('tbl_token', ['id_user' => $id_user]) > 0 && 
		$this->db->delete('tbl_user', ['id_user' => $id_user]) > 0;
	}

	public function edit_user
	($id_user, $email = NULL, $status = NULL, $keterangan = NULL, $username = NULL, $password = NULL)
	{
		$updates = [];

		if (!empty($email)) $updates['email'] = $email;
		if (!empty($status)) $updates['status'] = $status;
		if (!empty($keterangan)) $updates['keterangan'] = $keterangan;
		if (!empty($username)) $updates['username'] = $username;
		if (!empty($passwodr)) $updates['password'] = $password;

		return $this->db->update('tbl_user', 
			$updates,
			[
				'id_user' => $id_user
			]
		) > 0;
	}

	public function ban_user($id_user, $keterangan)
	{
		return $this->db->update('tbl_user',
			[
				'status' => 'BAN',
				'keterangan' => $keterangan
			],
			[
				'id_user' => $id_user
			]
		) > 0;
	}

	public function unban_user($id_user)
	{
		return $this->db->update('tbl_user',
			[
				'status' => 'AKTIF',
				'keterangan' => NULL
			],
			[
				'id_user' => $id_user
			]
		) > 0;
	}

	public function ban_list($limit = NULL, $offset = NULL)
	{
		$sql = $this->db->get_where('tbl_user', ['aktif' => false], $limit, $offset);

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}
}