<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurusan_model extends CI_Model {
	public function get_all_jurusan($limit = NULL, $offset = NULL)
	{
		$sql = $this->db->get('tbl_jurusan', $limit, $offset);

		return $sql->num_rows() > 0 ? $sql->result_array() : NULL;
	}
}