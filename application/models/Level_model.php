<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Level_model extends CI_Model {
	public function get_all_level($limit = NULL, $offset = NULL)
	{
		$sql = $this->db->get('tbl_level', $limit, $offset);

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}
	public function get_level_by_id($id_level)
	{
		$sql = $this->db->get_where('tbl_level', ['id_level' => $id_level]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_level_by_nama($nama_level)
	{
		$sql = $this->db->get_where('tbl_level', ['nama' => $nama_level]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}
}