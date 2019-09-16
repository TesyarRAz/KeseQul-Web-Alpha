<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TU_model extends CI_Model {
	public function get_all_tu_for_admin($limit = NULL, $offset = NULL)
	{
		$sql = $this->db
		->select('tbl_tu.*, tbl_user.username, tbl_user.email')
		->from('tbl_tu')
		->join('tbl_user', 'tbl_user.id_user = tbl_tu.id_user')
		->limit($limit, $offset)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : NULL;
	}
	public function get_tu_by_user($id_user)
	{
		$sql = $this->db->get_where('tbl_tu', ['id_user' => $id_user]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	// Akan ada bug ketika memasukan tanggal lahir dan gender
	public function tambah_tu($id_user, $nip, $nama, $gender, $tanggal_lahir)
	{
		$data = [
			'id_user' => $id_user,
			'nip' => $nip,
			'nama' => $nama,
			'gender' => $gender,
			'tanggal_lahir' => $tanggal_lahir
		];

		if ($gambar_location != NULL) $data['image_link'] = $gambar_location;

		return $this->db->insert('tbl_tu', $data) > 0;
	}

	public function edit_tu($id_user, $nip, $nama, $gender, $tanggal_lahir)
	{
		return $this->db->update('tbl_tu', 
			[
				'nip' => $nip,
				'nama' => $nama,
				'gender' => $gender,
				'tanggal_lahir' => $tanggal_lahir
			],
			['id_user' => $id_user]
		) > 0;
	}

	public function hapus_tu_by_user($id_user)
	{
		return $this->db->delete('tbl_tu', ['id_user' => $id_user]) > 0;
	}
}