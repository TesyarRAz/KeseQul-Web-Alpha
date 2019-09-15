<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Pdf extends Dompdf {
	public $filename;

	function __construct()
	{
		parent::__construct();

		$this->filename = 'laporanabsensi.pdf';
	}

	protected function ci()
	{
		return get_instance();
	}

	public function load_view($view, $data = [])
	{
		$html = $this->ci()->load->view($view, $data, true);
		$this->load_html($html);
		$this->render();
		$this->stream($this->filename, ['Attachment' => false]);
	}
}