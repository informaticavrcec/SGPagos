<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		self::index();	
	}
	
	function index(){
		
		$this->load->helper(array('url'));						
		if($this->session->userdata('logged_in') !== TRUE){			
			redirect('/start');			
		}	
	}
	
	public function menuusuario(){
		
		$this->load->helper(array('url'));

		$this->load->model('Tz_permisos');
		$padres = $this->Tz_permisos->get_modulos_padres();
		foreach($padres as $value){
			if(!$value['url']){
				$value['url'] = '#';	
			}			
			$hijos = $this->Tz_permisos->get_modulos_hijos($value['id_modulo']);			
			if(count($hijos) >= 1){			
				$modulohijo = '<ul>';
				foreach($hijos as $valor){					
					$modulohijo .= '<li><a href="'.$valor['url'].'">'.$valor['nombre'].'</a></li>';					
				}
				$modulohijo .= '</ul>';
			}
			
			$data['menupadre'][] = '<li>|<a href="'.$value['url'].'">'.$value['nombre'].'</a>'.$modulohijo.'</li>';
			unset($modulohijo);	
			
		}
			
		return $this->load->view('menu/usuario',$data,TRUE);	
		
		
	}	
	
}