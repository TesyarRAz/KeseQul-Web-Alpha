<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Token_model extends CI_Model {
	public function get_token_by_token($token)
	{
		$sql = $this->db->get_where('tbl_token', ['token' => $token]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_token_by_user($id_user)
	{
		$sql = $this->db->get_where('tbl_token', ['id_user' => $id_user]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	// MASIH ADA BUG, USER YANG MASUK BISA MENGGUNAKAN TOKEN BARU, DAN USER LAMA JUGA BISA MENGGUNAKAN TOKEN DENGAN 
	// GENERATE YANG BARU, INI AKAN MEMBUAT SERVER BERJALAN LEBIH LAMBAT
	public function generate_token($id_user)
	{
		do
		{
			$token = random_string('alnum', 64);
		}
		while ($this->get_token_by_token($token) != false);

		$data = [
			'token' => $token,
			'ip' => $this->input->ip_address(),
			'waktu_dibuat' => date('Y-m-d H:i:s')
		];

		if ($this->db->get_where('tbl_token', ['id_user' => $id_user])->num_rows() > 0)
		{
			$this->db->update('tbl_token', $data, ['id_user' => $id_user]);
		}
		else
		{
			$data['id_user'] = $id_user;
			$this->db->insert('tbl_token', $data);
		}
		return $token;
	}

	// Cek Apakah Token Valid atau Tidak
	// MASIH ADA BUG
	public function valid_token($token)
	{
		$sql = $this->db->get_where('tbl_token', ['token' => $token]);

		if ($sql->num_rows() > 0)
		{
			return true;

			
			// BUG BELUM TERTANGANI

			// $data = $sql->row_array();

			// $waktu_lama = date_create($data['waktu_dibuat']);
			// $waktu_sekarang = date_create(date("Y-m-d H:i:s"));

			// Menyetal kadaluarsa token ketika sudah 2 jam
			// $sisa = date_diff($waktu_sekarang, $waktu_lama);

			// return $sisa->h < 2 && $sisa->d == 0;
			
		}

		return false;
	}
}