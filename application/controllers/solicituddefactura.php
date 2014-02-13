<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Solicituddefactura extends MY_Controller {
	
	function index(){
		$this->load->helper(array('url','form'));
		$this->load->library(array('form_validation'));
				
		$this->load->model('Tz_permisos');		
		if($this->Tz_permisos->get_permiso_modulo(6) != TRUE){
			redirect('/accesodenegado');
		}		
		
		$this->form_validation->set_rules('rut','rut','trim|required');
		
		
		if($this->form_validation->run() == TRUE){
			
			$this->load->model('Ta_usrio');			
			$this->load->model('Tz_postulacionitem');
			$respuesta = $this->Ta_usrio->get_usrionombres_porrut($this->input->post('rut'));
			
			$data['nombres'] = $respuesta[0]['nombres'];
			$data['apellidos'] = $respuesta[0]['apellidos'];	
			$idt_usrio = $respuesta[0]['idt_usrio'];	
				
			if($idt_usrio){
				$data['actividades_apagar'] = $this->Tz_postulacionitem->get_pagos_por_facturar($idt_usrio);
			}else{
				$data['flash_error'] = 'El rut indicado no existe';	
			}
			
		}
		
		$data['menuusuario'] = self::menuusuario();		
		$data['page_title'] = $this->config->item('page_title').'Solicitud de factura';			
		$this->load->view('solicituddefactura/index',$data);
		
		
	}
	
	
	
}