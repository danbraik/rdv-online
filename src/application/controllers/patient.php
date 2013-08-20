<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Controller of patients
 * A patient can take a rendez-vous in a slot
 * 
 */ 
class Patient extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('patients_model', 'patients');
	}
	
	public function index()
	{
		
	}
	
	public function wiz_create() {
		$this->load->helper('form');
		
		$data['create_error'] = '';
		$data['header_title'] = 'Ajouter membre';
		$data['post_url'] = 'patient/create';
		$data['page'] = $this->load->view('patient/wiz_create_view', $data, true);
		
		$this->load->view('theme/layout', $data);
	}
	
	
	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
				
		$this->form_validation->set_rules('name', 'lang:name',
			'trim|required|xss_clean');
		$this->form_validation->set_rules('fname', 'lang:fname',
			'trim|required|xss_clean');
		$this->form_validation->set_rules('phone', 'lang:phone',
			'trim|required|numeric|min_length[10]|xss_clean');
		
		$validated = false;
		$data['create_error'] = '';
		
		if($this->form_validation->run() !== FALSE) {		
			$id_account = $this->session->userdata('user.id');
			if ($id_account > 0) {
				if ($this->patients->add($this->input->post('name'),
										 $this->input->post('fname'),
										 $this->input->post('phone'),
										 $id_account) > 0) {
					$validated = true;
				}
			} 
			
			if (!$validated)
				$data['create_error'] = 'Erreur lors de la création, veuillez vous reconnectez !';
		}
		
		if (!$validated) {			
			$data['header_title'] = 'Ajouter membre';
			$data['post_url'] = '/patient/create';
			$data['page'] = $this->load->view('patient/wiz_create_view', $data, true);
			
			$this->load->view('theme/layout', $data);
		} else {
			$data['create_error'] = '';
			$data['header_title'] = 'Ajouter membre';
			$data['post_url'] = '/patient/wiz_other/'.$name.'/'.$phone;
			$data['page'] = $this->load->view('patient/wiz_create_other_view', $data, true);
			
			$this->load->view('theme/layout', $data);
		}
		
		
	}
	
	public function wiz_other($name = '', $phone = '') {
		$this->load->helper('form');
		$data['create_error'] = '';
		$data['header_title'] = 'Ajouter membre';
		$data['post_url'] = '/patient/create';
		$data['name'] = $name;
		$data['phone'] = $phone;
		$data['page'] = $this->load->view('patient/wiz_create_other_form_view', $data, true);
		
		$this->load->view('theme/layout', $data);
	}
	
	
	
	
	public function show($id) {
		if (! is_numeric($id))
			show_error('L\'identifiant doit être numérique.');
		
		$patient = $this->patients->getById($id);
		
		if ($patient === false)
			show_error('L\'identifiant est inexistant.');
		
		if ($patient[$this->patients->gfIdAccount()] 
			!= $this->session->userdata('user.id'))
			show_error('Vous n\'avez pas le droit de visualiser ce patient.');
		
		
		$data['header_title'] = 'Membre';
		$data['patient'] = $patient;
		$data['page'] = $this->load->view('patient/show_one_view', $data, true);
		
		$this->load->view('theme/layout', $data);
		
	}
	
}

/* End of file session.php */
/* Location: ./application/controllers/session.php */
 
