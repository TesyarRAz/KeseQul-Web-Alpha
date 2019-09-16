<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {
	public function get_all_siswa_for_admin($limit = NULL, $offset = NULL)
	{
		$sql = $this->db
		->select('tbl_siswa.*, tbl_jurusan.nama_jurusan, tbl_user.username, tbl_user.email')
		->from('tbl_siswa')
		->join('tbl_jurusan', 'tbl_jurusan.id_jurusan = tbl_siswa.id_jurusan')
		->join('tbl_user', 'tbl_user.id_user = tbl_siswa.id_user')
		->limit($limit, $offset)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : NULL;
	}

	public function get_siswa_by_identity($username)
	{
		$sql = $this->db
		->select('tbl_siswa.*, tbl_jurusan.nama_jurusan')
		->from('tbl_siswa')
		->join('tbl_user', 'tbl_siswa.id_user = tbl_user.id_user', 'RIGHT')
		->join('tbl_jurusan', 'tbl_jurusan.id_jurusan = tbl_siswa.id_jurusan')
		->where('tbl_user.username', $username)
		->get();

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_siswa_by_user($id_user)
	{
		$sql = $this->db->get_where('tbl_siswa', ['id_user' => $id_user]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_siswa_by_kelas($kelas, $id_jurusan, $index_jurusan)
	{
		$sql = $this->db
		->select('*')
		->from('tbl_siswa')
		->where('tbl_siswa.kelas', $kelas)
		->where('tbl_siswa.id_jurusan',$id_jurusan)
		->where('tbl_siswa.index_jurusan',$index_jurusan)
		->order_by('tbl_siswa.nama')
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	// Akan ada bug ketika memasukan tanggal lahir, kelas, indexjurusan dan gender
	public function tambah_siswa
	($id_user, $nisn, $nama, $gender, $tanggal_lahir, $kelas, $id_jurusan, $index_jurusan, $gambar_location = NULL)
	{
		$data = [
			'id_user' => $id_user,
			'nisn' => $nisn,
			'nama' => $nama,
			'gender' => $gender,
			'tanggal_lahir' => $tanggal_lahir,
			'kelas' => $kelas,
			'id_jurusan' => $id_jurusan,
			'index_jurusan' => $index_jurusan
		];
		if ($gambar_location != NULL) $data['image_link'] = $gambar_location;
		
		return $this->db->insert('tbl_siswa', $data) > 0;
	}

	public function edit_siswa($id_user, $nisn, $nama, $gender, $tanggal_lahir, $kelas, $id_jurusan, $index_jurusan)
	{
		return $this->db->update('tbl_siswa', 
			[
				'nisn' => $nisn,
				'nama' => $nama,
				'gender' => $gender,
				'tanggal_lahir' => $tanggal_lahir,
				'kelas' => $kelas,
				'id_jurusan' => $id_jurusan,
				'index_jurusan' => $index_jurusan
			],
			['id_user' => $id_user]
		) > 0;
	}

	public function hapus_siswa_by_user($id_user)
	{
		return $this->db->delete('tbl_siswa', ['id_user' => $id_user]) > 0;
	}
}