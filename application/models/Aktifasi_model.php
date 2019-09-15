<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aktifasi_model extends CI_Model {
	public function get_aktifasi_user_by_identity($nilai)
	{
		$sql = $this->db->get_where('tbl_aktifasi_user', ['nilai' => $nilai]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function aktifkan_user($id_user)
	{
		$this->db->trans_begin();
		$this->db->delete('tbl_aktifasi_user', ['id_user' => $id_user]);
		$this->db->update('tbl_user', ['aktif' => true, 'reason_ban' => NULL], ['id_user' => $id_user]);

		if ($this->db->trans_status() == FALSE)
		{
			$this->db->trans_rollback();

			return false;
		}
		else
		{
			$this->db->trans_commit();

			return true;
		}
	}
}