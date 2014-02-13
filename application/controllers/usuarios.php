<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrador extends MY_Controller {
	
	function index(){
			
	}
	
	function usuarios(){
		$this->load->helper(array('url','form'));
		$this->load->model('Tz_permisos');
		
		if($this->Tz_permisos->get_permiso_modulo(2) == TRUE){
			$data['page_title'] = $this->config->item('page_title').'Administrador de usuarios';
			echo "admin";
			
		}else{
			$data['page_title'] = $this->config->item('page_title').'Acceso denegado';
			echo "denegado";
		}
		
	}
	
}