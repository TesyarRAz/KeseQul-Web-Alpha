<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Jakarta');

class Topup_model extends CI_Model {
	public function topup_uang($id_penerima, $id_pengirim, $bayar)
	{
		return $this->db->insert('tbl_topup', 
			[
				'id_penerima' => $id_penerima,
				'id_pengirim' => $id_pengirim,
				'bayar' => $bayar,
				'waktu' => date('Y-m-d H:i:s')
			]
		) > 0;
	}
}