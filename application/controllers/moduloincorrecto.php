<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Moduloincorrecto extends MY_Controller {
	
	function index(){
		$data['menuusuario'] = self::menuusuario();
		$data['page_title'] = $this->config->item('page_title').'Modulo incorrecto';
		$this->load->view('moduloincorrecto',$data);
	}
	
}