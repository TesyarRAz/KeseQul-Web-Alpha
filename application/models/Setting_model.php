<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {
	public static $HARGA_SPP = 'HARGA_SPP';

	public function get_setting_angkatan_by_nama($nama, $kelas, $id_jurusan)
	{
		$sql = $this->db->get_where('tbl_setting_angkatan', ['nama' => $nama, 'kelas' => $kelas, 'id_jurusan' => $id_jurusan]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}
}