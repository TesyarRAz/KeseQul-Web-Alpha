<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class SPP_Model extends CI_Model {
	public function bayar_spp($id_siswa, $bayar, $bulan)
	{
		return $this->db->insert('tbl_spp', 
			[
				'id_siswa' => $id_siswa,
				'bayar' => $bayar,
				'bulan' => $bulan,
				'waktu' => date('Y-m-d H:i:s')
			]
		) > 0;
	}

	public function get_spp_by_user($id_user, $limit, $offset)
	{
		$sql = $this->db
		->select('tbl_spp.*')
		->from('tbl_spp')
		->join('tbl_siswa', 'tbl_spp.id_siswa = tbl_siswa.id_siswa', 'LEFT')
		->where('tbl_siswa.id_user', $id_user)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : NULL;
	}

	public function get_bulan_spp_by_user($id_user)
	{
		$sql = $this->db
		->select('SUM(tbl_spp.bulan) AS bulan, SUM(tbl_spp.bayar) AS bayar')
		->from('tbl_spp')
		->join('tbl_siswa', 'tbl_spp.id_siswa = tbl_siswa.id_siswa', 'LEFT')
		->where('tbl_siswa.id_user', $id_user)
		->get();

		return $sql->num_rows() > 0 ? $sql->row_array() : ['bulan' => 0, 'bayar' => 0];
	}

}