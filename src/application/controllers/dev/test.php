<?php

class De {
	static $output = '';
	
	static function getOutput() {
		return De::$output;
	}
	
	static function bug($msg) {
		De::$output .= $msg.'<br/>';
	}
	
	static function bug_d($date) {
		De::bug($date->format('D d M Y'));
	}
	
	static function bug_d2($date) {
		De::bug($date->format(DateTime::RSS));
	}
	
	static function dump($obj) {
		De::bug( var_export($obj, true));
	}
}

class Test extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('accounts_model', 'accounts');
	}
		
	
	function index() {
		
		
		$this->load->library('email');

		$this->email->from('your@example.com', 'Your Name');
		$this->email->to('someone@example.com'); 
		$this->email->cc('another@another-example.com'); 
		$this->email->bcc('them@their-example.com'); 

		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');	

		$this->email->send();

		echo $this->email->print_debugger();
		
		
	}
	
		
		
		
}
	
