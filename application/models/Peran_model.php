<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peran_model extends CI_Model {
	public function get_peran_by_id($id_peran)
	{
		$sql = $this->db->get_where('tbl_peran', ['id_peran' => $id_peran]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_peran_by_user($id_user, $limit = NULL, $offset = NULL)
	{
		$sql = $this->db
		->select('tbl_peran.id_peran, tbl_peran.id_level, tbl_level.nama AS nama_level')
		->join('tbl_level', 'tbl_peran.id_level = tbl_level.id_level')
		->where('tbl_peran.id_user', $id_user)
		->get('tbl_peran', $limit, $offset);

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function get_peran_by_level($id_level)
	{
		$sql = $this->db->get_where('tbl_peran', ['id_level' => $id_level]);

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function tambah_peran($id_user, $id_level, $id_pengizin)
	{
		return $this->db->insert('tbl_peran', 
			[
				'id_user' => $id_user,
				'id_level' => $id_level,
				'id_pengizin' => $id_pengizin
			]
		) > 0;
	}

	public function hapus_all_peran_by_user($id_user)
	{
		return $this->db->delete('tbl_peran', ['id_user' => $id_user]) > 0;
	}

	public function hapus_peran_by_user($id_level, $id_user)
	{
		return $this->db->delete('tbl_peran', ['id_level' => $id_level, 'id_user' => $id_user]) > 0;
	}
}