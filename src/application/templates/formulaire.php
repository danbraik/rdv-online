<a data-role="button" href="<?php echo $url_back; ?>">Retour</a>

<style>
	.error {
		color:red;
	}
</style>

<?php echo form_open($url_form_post, 'data-ajax="false"'); ?>
	<div class='error'>
		<?php echo $error; ?>
	</div>
	
	<div>
		<div class='error'>
			<?php echo form_error('name'); ?>
		</div>
		<div data-role="fieldcontain">
			<label class="label" for='name'>Label</label><br/>
			<input	type='text'
					name='name'
					autocomplete='on'
					value='<?php echo form_prep(set_value('name')); ?>'
					required
					/>
		</div>
	</div>
<?php
	echo form_submit('submit', 'Valider', 'data-theme="b"');
	echo form_close();
?>


<?php
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'lang:name',
			'trim|required|xss_clean');
		
		$validated = false;
		$data['error'] = '';
		
		if($this->form_validation->run() !== FALSE) {
			
			if (...) {
				
			}
			
			if (!$validated)
				$data['error'] = '';
		}
		
		if (!$validated) {
			$data['header_title'] = '';
			$data['url_form_post'] = '';
			
			$this->load->helper('form');
			$data['page'] = $this->load->view('*/*_view', $data, true);
			$this->load->view('theme/layout', $data);
		} else {
			$data['url_back'] = '/boss';
			
			$data['page'] = $this->load->view('*/*_view', $data, true);
			$this->load->view('theme/layout', $data);
			// ** OR **
			redirect('/');
		}
