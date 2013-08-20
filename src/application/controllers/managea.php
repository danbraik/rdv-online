<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Manage account from dashboard
 */ 
class Managea extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('accounts_model', 'accounts');
	}
	
	public function index()
	{
		
		$data['url_change_password'] 	= '/managea/password';
		$data['url_remove_account'] 	= '/managea/remove';
		
		
		$id_account = $this->session->userdata('user.id');
		$account = $this->accounts->getById($id_account);
		$data['mail'] = $account[$this->accounts->gfMail()];
		
		$data['page'] = $this->load->view('managea/home_view', $data, true);
		$data['header_title'] = 'Compte';
		$data['header_url_home'] = '/dashboard';
		$this->load->view('theme/layout', $data);
	}
	
	/**
	 * Change password account
	 */
	public function password() {
		
		
		$data['url_back'] = '/managea';
		
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('password', 'lang:password',
			'trim|required|min_length[5]');
		$this->form_validation->set_rules('new_password', 'lang:password_new',
			'trim|required|min_length[5]');
		$this->form_validation->set_rules('new_conf_password', 'lang:password_conf',
			'trim|required|matches[new_password]');
		
		$changed = false;
		$data['change_error'] = '';
		
		if($this->form_validation->run() !== FALSE) {
			$id_account = $this->session->userdata('user.id');
			
			
			$account = $this->accounts->getById($id_account);
			
			if ($this->accounts->comparePasswords($this->input->post('password'),
					$account[$this->accounts->gfPassword()])) {
				
				$this->accounts->updatePassword($id_account, 
					$this->input->post('new_password'));
					
				$changed = true;
			}
		}
		
		
		if (!$changed) {	
			
			$this->load->helper('form');
					
			$data['header'] = '';
			$data['url_form_post'] = 'managea/password';
			$data['header_title'] = 'Changer mot de passe';
			
			
			$data['page'] = $this->load->view('managea/password_view', $data, true);
			$data['header_url_back'] = '/managea';
			$data['header_title'] = 'Compte';
			$data['header_url_home'] = '/dashboard';
			$this->load->view('theme/layout', $data);
		
		} else {
			$this->load->helper('url');
			redirect('/managea');
		}
		
		
	}
	
	
	public function remove() {
		
		$data['url_back'] = '/managea';
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('password', 'lang:password',
			'trim|required|min_length[5]');
		
		$removed = false;
		$data['change_error'] = '';
		
		if($this->form_validation->run() !== FALSE) {
			$id_account = $this->session->userdata('user.id');
			
			$account = $this->accounts->getById($id_account);
			
			if ($this->accounts->comparePasswords($this->input->post('password'),
					$account[$this->accounts->gfPassword()])) {
				
				$this->load->model('patients_model', 'patients');
				$this->load->model('slots_model', 'slots');
				
				$members = $this->patients->getAllByIdAccount($id_account);
				
				
				foreach($members as $pat) {
					$id_patient = $pat[$this->patients->gfId()];
					
					// suppression rendez-vous
					$this->slots->freeRdvsByIdPatient($id_patient);
					
					// suppression membres
					$this->patients->remove($id_patient);
				}
				
				// suppression compte
				$this->accounts->remove($id_account);
				
				$this->session->set_userdata(array('user.logged' => false,
												   'user.id' => 0,
												   'user.rank' => 0,
												   ));
				
				$removed = true;
			} else {
				$data['change_error'] = 'Mot de passe incorrect.';
				$this->form_validation->set_message('required', 'Your custom message here');
			}
		}
		
		
		if (!$removed) {	
			
			$this->load->helper('form');
					
			$data['header'] = 'Destruction';
			$data['url_form_post'] = 'managea/remove';
			$data['header_title'] = 'Suppression';
			$data['url_cancel'] = '/managea';
			
			$data['page'] = $this->load->view('managea/remove_view', $data, true);
			$data['header_url_back'] = '/managea';
			$data['header_title'] = 'Suppression';
			$data['header_url_home'] = '/dashboard';
			$this->load->view('theme/layout', $data);
		
		} else {
			$this->load->helper('url');
			redirect('');
		}
		
	}
}

/* End of file session.php */
/* Location: ./application/controllers/session.php */
 
