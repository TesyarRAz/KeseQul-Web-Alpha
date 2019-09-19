<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voting_model extends CI_Model {
	function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
	}

	public function get_event_by_id($id_event_voting)
	{
		$sql = $this->db->get_where('tbl_event_voting', ['id_event_voting' => $id_event_voting]);

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_event_by_pembuat($id_pembuat)
	{
		$sql = $this->db->get_where('tbl_event_voting', ['id_pembuat' => $id_pembuat]);

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function get_team_by_event($id_event_voting)
	{
		$sql = $this->db->get_where('tbl_nominasi_team', ['id_event_voting' => $id_event_voting]);

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function get_team_by_pembuat($id_event_voting, $id_pembuat)
	{
		$sql = $this->db
		->select(
			"tbl_nominasi_team.*, ketua.nama AS nama_ketua, concat(ketua.kelas, ' ', kjurusan.nama_jurusan, ' ', ketua.index_jurusan) AS kelas_ketua, wakil.nama AS nama_wakil, concat(wakil.kelas, ' ', wjurusan.nama_jurusan, ' ', wakil.index_jurusan) AS kelas_wakil")
		->from('tbl_nominasi_team')
		->join('tbl_event_voting', 'tbl_event_voting.id_event_voting = tbl_nominasi_team.id_event_voting')
		->join('tbl_siswa AS ketua', 'ketua.id_siswa = tbl_nominasi_team.id_ketua')
		->join('tbl_siswa AS wakil', 'wakil.id_siswa = tbl_nominasi_team.id_wakil')
		->join('tbl_jurusan AS kjurusan', 'kjurusan.id_jurusan = ketua.id_jurusan')
		->join('tbl_jurusan AS wjurusan', 'wjurusan.id_jurusan = wakil.id_jurusan')
		->where('tbl_event_voting.id_pembuat', $id_pembuat)
		->where('tbl_event_voting.id_event_voting', $id_event_voting)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function tambah_event($id_pembuat, $nama, $tanggal_mulai, $tanggal_selesai)
	{
		do {
			$password = random_string('alnum', 8);
		} while (!empty($this->get_event_by_identity($password)));

		return $this->db->insert('tbl_event_voting', 
			[
				'id_pembuat' => $id_pembuat,
				'nama' => $nama,
				'tanggal_mulai' => $tanggal_mulai,
				'tanggal_selesai' => $tanggal_selesai,
				'password' => $password,
				'status' => true
			]
		) > 0;
	}

	public function tambah_team($id_event_voting, $nama, $id_ketua, $id_wakil)
	{
		return $this->db->insert('tbl_nominasi_team', 
			[
				'id_event_voting' => $id_event_voting,
				'nama' => $nama,
				'id_ketua' => $id_ketua,
				'id_wakil' => $id_wakil
			]
		) > 0;
	}

	public function get_team_by_id($id_team)
	{
		$sql = $this->db
		->select(
			"tbl_nominasi_team.*, ketua.nama AS nama_ketua, concat(ketua.kelas, ' ', kjurusan.nama_jurusan, ' ', ketua.index_jurusan) AS kelas_ketua, wakil.nama AS nama_wakil, concat(wakil.kelas, ' ', wjurusan.nama_jurusan, ' ', wakil.index_jurusan) AS kelas_wakil")
		->from('tbl_nominasi_team')
		->join('tbl_event_voting', 'tbl_event_voting.id_event_voting = tbl_nominasi_team.id_event_voting')
		->join('tbl_siswa AS ketua', 'ketua.id_siswa = tbl_nominasi_team.id_ketua')
		->join('tbl_siswa AS wakil', 'wakil.id_siswa = tbl_nominasi_team.id_wakil')
		->join('tbl_jurusan AS kjurusan', 'kjurusan.id_jurusan = ketua.id_jurusan')
		->join('tbl_jurusan AS wjurusan', 'wjurusan.id_jurusan = wakil.id_jurusan')
		->where('tbl_nominasi_team.id_nominasi_team', $id_team)
		->get();

		return $sql->num_rows() > 0 ? $sql->row_array() : false;
	}

	public function get_event_by_identity($password)
	{
		$sql = $this->db
		->select('tbl_event_voting.*')
		->from('tbl_event_voting')
		->where('password', $password)
		->get();

		return $sql->num_rows() > 0 ? $sql->row_array() : NULL;
	}

	public function get_team_by_identity($id_event_voting, $password)
	{
		$sql = $this->db
		->select(
			"tbl_nominasi_team.*, ketua.nama AS nama_ketua, concat(ketua.kelas, ' ', kjurusan.nama_jurusan, ' ', ketua.index_jurusan) AS kelas_ketua, wakil.nama AS nama_wakil, concat(wakil.kelas, ' ', wjurusan.nama_jurusan, ' ', wakil.index_jurusan) AS kelas_wakil")
		->from('tbl_nominasi_team')
		->join('tbl_event_voting', 'tbl_event_voting.id_event_voting = tbl_nominasi_team.id_event_voting')
		->join('tbl_siswa AS ketua', 'ketua.id_siswa = tbl_nominasi_team.id_ketua')
		->join('tbl_siswa AS wakil', 'wakil.id_siswa = tbl_nominasi_team.id_wakil')
		->join('tbl_jurusan AS kjurusan', 'kjurusan.id_jurusan = ketua.id_jurusan')
		->join('tbl_jurusan AS wjurusan', 'wjurusan.id_jurusan = wakil.id_jurusan')
		->where('tbl_event_voting.id_event_voting', $id_event_voting)
		->where('tbl_event_voting.password', $password)
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	public function get_hasil_total_by_event($id_event_voting)
	{
		// ALGORITMA TERSULIT YANG PERNAH SAIA BUAT
		$sql = $this->db
		->select(
			"COUNT(tbl_voting.id_nominasi_team) AS total, tbl_nominasi_team.nama AS nama_team, ketua.nama AS nama_ketua, wakil.nama AS nama_wakil, CONCAT(ketua.kelas, ' ', jketua.nama_jurusan, ' ', ketua.index_jurusan) AS kelas_ketua, CONCAT(wakil.kelas, ' ', jwakil.nama_jurusan, ' ', wakil.index_jurusan) AS kelas_wakil"
		)
		->from('tbl_nominasi_team')
		->join('tbl_voting', 'tbl_voting.id_nominasi_team = tbl_nominasi_team.id_nominasi_team', 'LEFT')
		->join('tbl_siswa AS ketua', 'ketua.id_siswa = tbl_nominasi_team.id_ketua')
		->join('tbl_siswa AS wakil', 'wakil.id_siswa = tbl_nominasi_team.id_wakil')
		->join('tbl_jurusan AS jketua', 'jketua.id_jurusan = ketua.id_jurusan')
		->join('tbl_jurusan AS jwakil', 'jwakil.id_jurusan = wakil.id_jurusan')
		->where('tbl_nominasi_team.id_event_voting', $id_event_voting)
		->group_by('tbl_nominasi_team.id_nominasi_team')
		->order_by('total', 'desc')
		->get();

		return $sql->num_rows() > 0 ? $sql->result_array() : false;
	}

	// DI CONTROLLER HARUS CEK APAKAH USER SUDAH NYOBLOS ATAU BELUM
	public function coblos($id_event_voting, $id_pemilih, $id_nominasi_team)
	{
		if ($this->db->get_where('tbl_voting', ['id_event_voting' => $id_event_voting, 'id_pemilih' => $id_pemilih])->num_rows() > 0)
			return false;

		return $this->db->insert('tbl_voting', 
			[
				'id_event_voting' => $id_event_voting,
				'id_pemilih' => $id_pemilih,
				'id_nominasi_team' => $id_nominasi_team
			]
		) > 0;
	}
}