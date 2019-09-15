<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru_model extends CI_Model {
	public function get_all_guru($limit = NULL, $offset = NULL)
	{
		$sql = $this->db->get('tbl_guru', $limit, $offset);

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function get_all_guru_for_admin($limit = NULL, $offset = NULL)
	{
		$sql = $this->db
		->select('tbl_guru.*, tbl_user.username, tbl_user.email')
		->from('tbl_guru')
		->join('tbl_user', 'tbl_user.id_user = tbl_guru.id_user')
		->limit($limit, $offset)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : NULL;
	}

	public function get_guru_by_user($id_user)
	{
		$sql = $this->db->get_where('tbl_guru', ['id_user' => $id_user]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	// Akan ada bug ketika memasukan tanggal lahir dan gender
	public function tambah_guru($id_user, $nip, $nama, $gender, $tanggal_lahir)
	{
		return $this->db->insert('tbl_guru', 
			[
				'id_user' => $id_user,
				'nip' => $nip,
				'nama' => $nama,
				'gender' => $gender,
				'tanggal_lahir' => $tanggal_lahir
			]
		) > 0;
	}

	public function edit_guru($id_user, $nip, $nama, $gender, $tanggal_lahir)
	{
		return $this->db->update('tbl_guru', 
			[
				'nip' => $nip,
				'nama' => $nama,
				'gender' => $gender,
				'tanggal_lahir' => $tanggal_lahir
			],
			['id_user' => $id_user]
		) > 0;
	}

	public function hapus_guru_by_user($id_user)
	{
		return $this->db->delete('tbl_guru', ['id_user' => $id_user]) > 0;
	}
}