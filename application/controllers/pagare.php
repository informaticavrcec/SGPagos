<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagare extends MY_Controller {	

	function index(){
		
		$this->load->helper(array('url','form'));	
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(9) != TRUE){
			redirect('/accesodenegado');
		}
		
		$data['menuusuario'] = self::menuusuario();
		$this->load->model('Tz_permisos');
		$data['page_title'] = $this->config->item('page_title').'Pagares';
		$data['modulos'] = $this->Tz_permisos->get_modulos_por_nombre('pagare');				
		$this->load->view('pagares/index',$data);
		
	}
	
	function asignarboleta($idpostulacionitem,$id_pago){
		
		$this->load->helper(array('url','form'));	
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(8) != TRUE){
			redirect('/accesodenegado');
		}
		
		if(!is_numeric($idpostulacionitem) OR !is_numeric($id_pago)){
			redirect('/moduloincorrecto');
		}
		
		$this->load->library(array('form_validation'));		
		$this->form_validation->set_rules('numero_boleta','numero boleta','trim|required|numeric');
		$this->form_validation->set_rules('monto_boleta','monto boleta','trim|required|numeric');
		
		$allow = array(117503,192283,68996);		
		if(in_array($this->session->userdata('id_usuario'),$allow)){
			$data['datepicker'] = "
			$('#calendar').DatePicker({
				format:'d/m/Y',
				date: $(this).val(),
				current:$('#calendar').val() ,
				starts: 1,
				position: 'left',
				onBeforeShow: function(){			
					$('#calendar').DatePickerSetDate($('#calendar').val(), true);
				},
				onChange: function(formated, dates){
					$('#calendar').val(formated);			
				}
			});";
		}
		
		if($this->form_validation->run() == TRUE){
			
			$this->load->model('Tz_icboletas');
			$data['message'] = $this->Tz_icboletas->update_boleta_pagare($idpostulacionitem,
			$id_pago,
			$this->input->post('numero_boleta'),
			$this->session->userdata('id_usuario'),
			$this->input->post('monto_boleta'),
			$this->input->post('fecha'));
			
		}	
		
		$this->load->model('Tz_pago_pagare');
		$data['pagare'] = $this->Tz_pago_pagare->get_datos_porid($idpostulacionitem,$id_pago);
		
		$data['id_pago'] = $id_pago;
		$data['idpostulacionitem'] = $idpostulacionitem;
		$data['page_title'] = $this->config->item('page_title').'Asignar boleta';	
		$this->load->view('pagares/boleta',$data);	
		
	}
	
	function informe(){
		
		$this->load->helper(array('url','form'));	
		$this->load->model(array('Tz_permisos','Tz_pago_pagare'));
		if($this->Tz_permisos->get_permiso_modulo(13) != TRUE){
			redirect('/accesodenegado');
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('desde','desde','trim|required');
		$this->form_validation->set_rules('hasta','hasta','trim|required');
		if($this->form_validation->run() == TRUE){
			
			$data['pagare'] = $this->Tz_pago_pagare->get_pagares_general($this->input->post('desde'),
			$this->input->post('hasta'),
			$this->input->post('estado'));
			
		}
		
		
		$data['page_title'] = $this->config->item('page_title').'Informe pagares';
		$data['menuusuario'] = self::menuusuario();
		$this->load->view('informes/pagaregeneral',$data);
		
	}
	
	function poralumno(){
		
		$this->load->helper(array('url','form'));	
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(8) != TRUE){
			redirect('/accesodenegado');
		}		
		
		$this->load->helper(array('url','form'));		
		$this->load->library(array('form_validation'));
		
		$this->form_validation->set_rules('rut','rut','trim|required');
		$rut = $this->input->post('rut');
		if($this->form_validation->run() == TRUE){
			
			$this->load->model('Ta_usrio');
			$metadata = $this->Ta_usrio->get_usrionombres_porrut($rut);			
			
			$data['nombres'] = $metadata[0]['nombres'];
			$data['apellidos'] = $metadata[0]['apellidos'];
			$data['id_usrio'] = $metadata[0]['idt_usrio'];
			
			if(is_numeric($data['id_usrio'])){
				
				$this->load->model('Tz_pago_pagare');
				$data['pagare'] = $this->Tz_pago_pagare->get_porrut($data['id_usrio']);				
			}				
			
		}		
		
		$data['page_title'] = $this->config->item('page_title').'Registrar pago';
		$data['menuusuario'] = self::menuusuario();
		$this->load->view('pagares/alumno',$data);
		
		
		
	}



}




