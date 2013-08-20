<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Profiler extends CI_Profiler
{  
	function __construct() {
		$this->_available_sections = array(
										'session_data',
										'benchmarks',
										'get',
										'memory_usage',
										'post',
										'uri_string',
										'controller_info',
										'queries',
										'http_headers',
										'config'
										);

		parent::__construct();
	}
	
    function run()
    {
        return parent::run();
    }
    
    
    /**
	 * Compile session userdata
	 *
	 * @return 	string
	 */
	private function _compile_session_data()
	{
		if ( ! isset($this->CI->session))
		{
			return;
		}

		$output = '<fieldset id="ci_profiler_csession" style="border:1px solid #000;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
		$output .= '<legend style="color:#000;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_session_data').'&nbsp;&nbsp;(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'ci_profiler_session_data\').style;s.display=s.display==\'block\'?\'\':\'block\';this.innerHTML=this.innerHTML==\''.$this->CI->lang->line('profiler_section_show').'\'?\''.$this->CI->lang->line('profiler_section_hide').'\':\''.$this->CI->lang->line('profiler_section_show').'\';">'.$this->CI->lang->line('profiler_section_show').'</span>)</legend>';
		$output .= "<table style='width:100%;display:none' id='ci_profiler_session_data'>";

		foreach ($this->CI->session->all_userdata() as $key => $val)
		{
			if (is_array($val) OR is_object($val))
			{
				$val = print_r($val, TRUE);
			}

			$output .= "<tr><td style='padding:5px; vertical-align: top;color:#900;background-color:#ddd;'>".$key."&nbsp;&nbsp;</td><td style='padding:5px; color:#000;background-color:#ddd;'>".htmlspecialchars($val)."</td></tr>\n";
		}

		$output .= '</table>';
		$output .= "</fieldset>";
		return $output;
	}

}
