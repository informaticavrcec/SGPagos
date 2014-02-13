<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_Controller {
	
	function index(){
		
		$this->session->sess_destroy();
		redirect('/login');		
	}	
	
}