<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {
	function __construct()
	{
		parent::__construct();

		$this->load->model('absensi_model', '', true);
		$this->load->model('admin_model', '', true);
		$this->load->model('voting_model', '', true);

		$this->load->helper('date');
		
		$this->load->library('pdf');
	}

	public function voting($id_event_voting)
	{
		$user = $this->access->get_access_two($this->input->get('token'));

		$admin = $this->admin_model->get_admin_by_user($user['id_user']);

		if (empty($id_event_voting))
		{
			echo 'Tidak boleh kosong';
			return;
		}

		if ($admin)
		{
			$event_voting = $this->voting_model->get_event_by_id($id_event_voting);
			if ($event_voting == false)
			{
				echo 'Tidak ada event dengan id itu';
				return;
			}

			$data = $this->voting_model->get_hasil_total_by_event($id_event_voting);

			if (!empty($data))
			{
				$this->pdf->setPaper('A4', 'Landscape');
				$this->pdf->filename = 'Laporan_Voting_' . str_replace(' ', '_', $event_voting['nama']) . '_' . str_replace(' ', '_', $admin['nama']);

				$this->pdf->load_view('laporan_voting', ['header' => $event_voting['nama'], 'data' => $data]);
				$this->load->view('laporan_voting', ['header' => $event_voting['nama'], 'data' => $data]);
			}
			else
			{
				echo 'Tidak ada yang nyoblos';
			}
		}
		else
		{
			echo 'Anda bukan admin';
		}
	}
}