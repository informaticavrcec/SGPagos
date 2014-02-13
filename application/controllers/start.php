<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start extends CI_Controller {
	
	function index(){
		
		$this->load->helper(array('url'));
		if($this->session->userdata('logged_in') == TRUE){
			
			redirect('/menu');
			
			
		}else{
			redirect('/login');	
		}
	}
	
}