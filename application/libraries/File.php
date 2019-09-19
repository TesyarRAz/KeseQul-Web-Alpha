<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File {
	function __construct()
	{
		$this->instance =& get_instance();

        $this->instance->load->helper('string');
		$this->instance->load->library('upload');
	}

	public function upload_gambar($field_name)
	{
        $result = [
            'status' => 0,
            'pesan' => NULL
        ];

		try
        {
            $config['upload_path'] = 'assets/upload/image/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 1024 * 20;
            $config['max_width'] = 1024;
            $config['max_height'] = 768;
            $config['file_name'] = uniqid();

            $this->instance->upload->initialize($config);
            if (!$this->instance->upload->do_upload($field_name))
            {
                $result['status'] = 0;
                $result['pesan'] = $this->instance->upload->display_errors();
            }
            else
            {
                $result['status'] = 1;
                $result['pesan'] = $this->instance->upload->data('file_name');
            }
        }
        catch (Exception $ex)
        {
            $result['pesan'] = 'Gagal';
        }

        return $result;
	}
}