<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webpay extends MY_Controller {	

	function index(){
		
		$this->load->helper('url');
		redirect('/menu');
		
	}
	
	function informe(){
		
		$this->load->helper(array('url','form'));
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(5) != TRUE){
			redirect('/accesodenegado');
		}	
		
		$this->load->library('form_validation');
		
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(5) != TRUE){
			redirect('/accesodenegado');
		}
		
		$this->form_validation->set_rules('desde','desde','required');
		$this->form_validation->set_rules('hasta','hasta','required');
		$data['listado_webpay'] = array(); 
		$data['proyecto'] = NULL;
			
		if($this->form_validation->run() === TRUE){
			$this->load->model('Tz_pagowebpay');
			$data['listado_webpay'] = $this->Tz_pagowebpay->get_informe_porcajero($this->input->post('desde'),$this->input->post('hasta'));
		}		
	
		$data['page_title'] = $this->config->item('page_title').'Informe WEBPAY';	
		$data['menuusuario'] = self::menuusuario();
		$this->load->view('informes/webpay',$data);
		
	}
	
	function informeexcel($desde,$hasta){
		
		$this->load->helper(array('url'));
		
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(4) != TRUE){
			redirect('/accesodenegado');
		}	
	
		$data['listado_webpay'] = array(); 
		$data['proyecto'] = NULL;
			
	
		$this->load->model('Tz_pagowebpay');
		$data['listado_webpay'] = $this->Tz_pagowebpay->get_informe_porcajero(str_replace('-','/',$desde),str_replace('-','/',$hasta));
			
	
		$data['page_title'] = $this->config->item('page_title').'Informe WEBPAY';
		
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=informe.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
				
		$this->load->view('informes/webpayexcel',$data);
		
	}



}




