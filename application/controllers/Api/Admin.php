<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends KCOREST_Controller {

	private $filter_access = [
		'peran' => ['ADMIN', 'SUPERADMIN']
	];

	function __construct()
	{
		parent::__construct($this);

		$this->load->model('admin_model', '', true);
		$this->load->model('guru_model', '', true);
		$this->load->model('siswa_model', '', true);
		$this->load->model('tu_model', '', true);
	}

	public function data_get()
	{
		$token = $this->input->get('token');
		$user = $this->access->get_access($token);

		if ($this->access->filter_access($this->filter_access, $user['id_user']))
		{
			$siswa = $this->admin_model->get_admin_by_user($user['id_user']);

			if ($siswa)
			{
				$this->default_response['status'] = 1;
				$this->default_response['data'] = $siswa;
			}
			else
			{
				$this->default_response['pesan'] = 'Anda tidak terdata sebagai admin';
			}
		}
		else
		{
			$this->default_response['pesan'] = 'Tidak punya hak akses';
		}
		

		$this->print_response();
	}

	public function allguru_get()
	{
		$token = $this->input->get('token');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');

		$user = $this->access->get_access($token);
		
		if ($this->access->filter_access($this->filter_access, $user['id_user']))
		{
			$this->default_response['status'] = 1;
			$this->default_response['data'] = $this->guru_model->get_all_guru_for_admin($limit, $offset);
		}
		else
		{
			$this->default_response['pesan'] = 'Tidak punya hak akses';
		}

		$this->print_response();
	}

	public function allsiswa_get()
	{
		$token = $this->input->get('token');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');

		$user = $this->access->get_access($token);
		
		if ($this->access->filter_access($this->filter_access, $user['id_user']))
		{
			$this->default_response['status'] = 1;
			$this->default_response['data'] = $this->siswa_model->get_all_siswa_for_admin($limit, $offset);
		}
		else
		{
			$this->default_response['pesan'] = 'Tidak punya hak akses';
		}

		$this->print_response();
	}

	public function alltu_get()
	{
		$token = $this->input->get('token');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');

		$user = $this->access->get_access($token);
		
		if ($this->access->filter_access($this->filter_access, $user['id_user']))
		{
			$this->default_response['status'] = 1;
			$this->default_response['data'] = $this->tu_model->get_all_tu_for_admin($limit, $offset);
		}
		else
		{
			$this->default_response['pesan'] = 'Tidak punya hak akses';
		}

		$this->print_response();
	}
}