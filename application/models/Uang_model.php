<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Jakarta');

class Uang_model extends CI_Model {
	public function transfer_uang($id_pemberi, $id_penerima, $uang)
	{
		return $this->db->insert('tbl_transaksi', 
			[
				'id_penerima' => $id_penerima,
				'id_pemberi' => $id_pemberi,
				'harga' => $uang,
				'waktu_transaksi' => date('Y-m-d H:i:s')
			]
		) > 0;
	}
	public function get_topup_by_pengirim($id_pengirim)
	{
		$sql = $this->db
		->select('tbl_topup.*')
		->from('tbl_topup')
		->where('tbl_topup.id_pengirim', $id_pengirim)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}
	public function get_topup_by_user($id_penerima)
	{
		$sql = $this->db
		->select('tbl_topup.*')
		->from('tbl_topup')
		->where('tbl_topup.id_penerima', $id_penerima)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function get_total_topup_by_user($id_user)
	{
		$sql = $this->db
		->select('SUM(tbl_topup.bayar) AS uang')
		->from('tbl_topup')
		->where('tbl_topup.id_penerima', $id_user)
		->group_by('tbl_topup.id_penerima')
		->get();

		return $sql->num_rows() > 0 ? $sql->row_array()['uang'] : 0;
	}

	public function get_pengeluaran_by_user($id_user)
	{
		$sql = $this->db
		->select('tbl_transaksi.*')
		->from('tbl_transaksi')
		->where('tbl_transaksi.id_pemberi', $id_user)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function get_total_pengeluaran_by_user($id_user)
	{
		$sql = $this->db
		->select('SUM(tbl_transaksi.harga) AS keluaran')
		->from('tbl_transaksi')
		->where('tbl_transaksi.id_pemberi', $id_user)
		->group_by('tbl_transaksi.id_pemberi')
		->get();

		return $sql->num_rows() > 0 ? $sql->row_array()['keluaran'] : 0;
	}

	public function get_pendapatan_by_user($id_user)
	{
		$sql = $this->db
		->select('tbl_transaksi.*')
		->from('tbl_transaksi')
		->where('tbl_transaksi.id_penerima', $id_user)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function get_total_pendapatan_by_user($id_user)
	{
		$sql = $this->db
		->select('SUM(tbl_transaksi.harga) AS uang')
		->from('tbl_transaksi')
		->where('tbl_transaksi.id_penerima', $id_user)
		->group_by('tbl_transaksi.id_penerima')
		->get();

		return $sql->num_rows() > 0 ? $sql->row_array()['uang'] : 0;
	}

	public function get_spp_by_user($id_user)
	{
		$sql = $this->db
		->select('tbl_spp.*')
		->from('tbl_spp')
		->join('tbl_siswa', 'tbl_siswa.id_user = tbl_spp.id_user')
		->where('tbl_siswa.id_user', $id_user)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function get_total_spp_by_user($id_user)
	{
		$sql = $this->db
		->select('SUM(tbl_spp.bayar) AS keluaran')
		->from('tbl_spp')
		->join('tbl_siswa', 'tbl_siswa.id_siswa = tbl_spp.id_siswa', 'LEFT')
		->join('tbl_user', 'tbl_user.id_user = tbl_siswa.id_user', 'LEFT')
		->where('tbl_user.id_user', $id_user)
		->group_by('tbl_spp.id_siswa')
		->get();

		return $sql->num_rows() > 0 ? $sql->row_array()['keluaran'] : 0;
	}

	public function get_sequlcash_by_user($id_user)
	{
		$sql = $this->db->get_where('tbl_sequlcash', ['id_user' => $id_user]);

		return $sql->num_rows() > 0 ? $sql->row_array()['uang'] : 0;
	}

	public function get_uang_by_user($id_user)
	{
		$uang = $this->get_sequlcash_by_user($id_user);
		$keluaran = 0;
		// Algoritma ambil pendapatan
		$data = $this->get_total_topup_by_user($id_user);
		if ($data)
			$uang += (int) $data;

		$data = $this->get_total_pendapatan_by_user($id_user);
		if ($data)
			$uang += (int) $data;
		// Akhir algoritma pendapatan

		// Algoritma ambil pengeluaran
		$data = $this->get_total_pengeluaran_by_user($id_user);
		if ($data)
			$keluaran += (int) $data;

		$data = $this->get_total_spp_by_user($id_user);
		if ($data)
			$keluaran += (int) $data;
		// Akhir algoritma pengeluaran

		return $uang - $keluaran;
	}
}