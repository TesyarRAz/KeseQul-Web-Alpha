<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_model extends CI_Model {
	public function get_absensi_sekarang()
	{
		$sql = $this->db
		->select(
			"COUNT(tbl_absensi.kehadiran = 'A') AS alfa, COUNT(tbl_absensi.kehadiran = 'S') AS sakit, COUNT(tbl_absensi.kehadiran = 'I') AS izin, tbl_siswa.kelas AS kelas, CONCAT(tbl_jurusan.nama_jurusan, ' ', tbl_siswa.index_jurusan) AS jurusan")
		->from('tbl_absensi')
		->join('tbl_siswa', 'tbl_siswa.id_siswa = tbl_absensi.id_siswa')
		->join('tbl_jurusan', 'tbl_jurusan.id_jurusan = tbl_siswa.id_jurusan')
		->where('tbl_absensi.tanggal = curdate()')
		->group_by('tbl_siswa.kelas')
		->group_by('tbl_siswa.id_jurusan')
		->group_by('tbl_siswa.index_jurusan')
		->order_by('tbl_siswa.kelas')
		->order_by('tbl_siswa.id_jurusan')
		->order_by('tbl_siswa.index_jurusan')
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}
	public function get_absensi_kelas($id_penginput, $tanggal = 'now()')
	{
		$sql = $this->db->get_where('tbl_absensi', 
			[
				'id_penginput' => $id_penginput,
				'tanggal' => empty($tanggal) ? 'now()' : $tanggal
			]
		);

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}
	public function tambah_absensi_kelas($id_penginput, $id_target, $kehadiran)
	{
		$sudah = $this->db->get_where('tbl_absensi', 
			[
				'id_siswa' => $id_target,
				'tanggal' => 'CURRENT_DATE'
			])->num_rows() > 0;

		if ($sudah)
		{
			return $this->db->update('tbl_absensi', 
				[
					'id_penginput' => $id_penginput,
					'kehadiran' => $kehadiran
				],
				['id_siswa' => $id_target, 'tanggal' => 'CURRENT_DATE']
			) > 0;
		}
		else
		{
			return $this->db->insert('tbl_absensi', 
				[
					'id_penginput' => $id_penginput,
					'id_siswa' => $id_target,
					'kehadiran' => $kehadiran,
					'tanggal' => 'CURRENT_DATE'
				]
			) > 0;
		}
	}
}