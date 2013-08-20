<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public function index()
	{	
		// chrono tasks
		
		
		
		
		// display home
		$data['header_title'] = '';
		$data['page'] = $this->load->view('welcome_message', $data, true);
		
		$this->load->view('theme/layout', $data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
