<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
	public function get_admin_by_user($id_user)
	{
		$sql = $this->db->get_where('tbl_admin', ['id_user' => $id_user]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function is_admin($username)
	{
		$sql = $this->db
		->select('tbl_admin.id_admin')
		->from('tbl_admin')
		->join('tbl_user', 'tbl_user.id_user = tbl_admin.id_user')
		->where('tbl_user.username', $username)
		->get();

		return $sql->num_rows() > 0;
	}

	public function tambah_admin($id_user, $nama)
	{
		return $this->db->insert('tbl_admin',
			[
				'id_user' => $id_user,
				'nama' => $nama
			]
		) > 0;
	}
}