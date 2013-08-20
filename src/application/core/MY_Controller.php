<?php

class MY_Controller extends CI_Controller {

	public function __construct() {
		
		parent::__construct();
		
		if ($this->session->userdata('user.logged') === false) {
			redirect('');
			exit;
		}
		
		//$this->output->enable_profiler(TRUE);
	}
}
