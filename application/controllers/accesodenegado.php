<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accesodenegado extends MY_Controller {
	
	function index(){
		$data['menuusuario'] = self::menuusuario();
		$data['page_title'] = $this->config->item('page_title').'Acceso denegado';
		$this->load->view('accesodenegado',$data);
	}
	
}