<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File {
	function __construct()
	{
		$this->instance =& get_instance();

		$this->instance->load->library('upload');
	}

	public function upload_gambar($field_name)
	{
		$config['upload_path'] = base_url('assets/upload/image/');
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
        $config['file_name'] = uniqid();

        $this->instance->upload->initialize($config);
        if (!$this->upload->do_upload($field_name))
        {
        	$result['status'] = 0;
            $result['pesan'] = $this->upload->display_errors();
        }
        else
        {
            $result['status'] = 1;
            $result['pesan'] = $this->upload->data('full_path');
        }

        return $result;
	}
}