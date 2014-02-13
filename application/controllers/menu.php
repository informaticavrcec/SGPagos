<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MY_Controller {
	
	public function index(){		
		
		$data['menuusuario'] = self::menuusuario();
		$this->load->model('Tz_permisos');
		$data['page_title'] = $this->config->item('page_title').'Inicio';
		$data['modulos'] = $this->Tz_permisos->get_modulos_por_nombre('caja');				
		$this->load->view('menu_principal',$data);
		
	}
	
	
	
}