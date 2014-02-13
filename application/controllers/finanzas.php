<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finanzas extends MY_Controller {
	
	function informe(){		
		
		$this->load->helper(array('url','form'));	
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(14) != TRUE){
			redirect('/accesodenegado');
		}
		
		$this->load->library('form_validation');
		
		if(!$this->input->post('desde')){
			$data['desde'] = date('d/m/Y');	
		}else{
			$data['desde'] = $this->input->post('desde');
		}
		
		if(!$this->input->post('hasta')){
			$data['hasta'] = date('d/m/Y');	
		}else{
			$data['hasta'] = $this->input->post('hasta');
		}
		
		$this->form_validation->set_rules('informe','informe','trim|required');
		$informe = $this->input->post('informe');
		$data['informe'] = $informe;
		$data['menuusuario'] = self::menuusuario();
		$data['cajeros'] = $this->Tz_permisos->get_usuarios_modulo(4);
		$data['seleccion'] = $this->input->post('cajero');
		
		$data['page_title'] = $this->config->item('page_title').'Informe finanzas';
		$data['link'] = "/finanzas/informeexcel/".str_replace('/','-',$data['desde'])."/".str_replace('/','-',$data['hasta'])."/".$informe.'/'.$data['seleccion'];	
		$this->load->view('informes/finanzastipo',$data);		
		
		if($this->form_validation->run() == TRUE){
			
			if($informe == 'informeboletas'){
				$this->load->model('Tz_icboletas');
				$data['boletas'] = $this->Tz_icboletas->informe_finanzas($this->input->post('desde'),
				$this->input->post('hasta'),
				$this->input->post('cajero'));
				
				$this->load->view('informes/boletas',$data);	
				
			}
			
			if($informe == 'chequesafecha'){
				$this->load->model('Tz_icboletas');
				$data['fecha'] = $this->Tz_icboletas->informe_finanzas_fecha($this->input->post('desde'),
				$this->input->post('hasta'),
				$this->input->post('cajero'));
				
				$this->load->view('informes/chequesfecha',$data);	
				
			}			
		}
		
	}
	
	
	function informeexcel($desde,$hasta,$informe = NULL,$matriculador = NULL){		
		
		$this->load->helper(array('url','form'));	
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(14) != TRUE){
			redirect('/accesodenegado');
		}
		
		$desde = str_replace('-','/',$desde);
		$hasta = str_replace('-','/',$hasta);
		
		if($desde AND $hasta AND $informe){	
				
			if($informe == 'informeboletas'){
				$this->load->model('Tz_icboletas');
				$data['boletas'] = $this->Tz_icboletas->informe_finanzas($desde,
				$hasta,
				$matriculador);
				
				header('Content-type: application/vnd.ms-excel');
				header("Content-Disposition: attachment; filename=informeboletas.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				
				$this->load->view('informes/boletasexcel',$data);	
				
			}
			
			if($informe == 'chequesafecha'){
				$this->load->model('Tz_icboletas');
				$data['fecha'] = $this->Tz_icboletas->informe_finanzas_fecha($desde,
				$hasta,
				$matriculador);
				
				header('Content-type: application/vnd.ms-excel');
				header("Content-Disposition: attachment; filename=informechequesfecha.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				
				$this->load->view('informes/chequesfechaexcel',$data);	
				
			}
			
		}
		
		if(!$informe){
			redirect('/finanzas/informe');	
		}
		
	}


}




