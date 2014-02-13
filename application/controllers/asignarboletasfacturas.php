<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asignarboletasfacturas extends MY_Controller {	

	function index(){
		
		$this->load->helper(array('url','form'));
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(3) != TRUE){
			redirect('/accesodenegado');
		}
		
		$this->load->library(array('form_validation'));	
		$this->form_validation->set_rules('rut','rut','trim|required');		
		$data['flash_error'] = $this->session->flashdata('error');
		$rut = $this->input->post('rut');
		
		if($this->form_validation->run() == TRUE){
						
			$this->load->model('Ta_usrio');
			$metadata = $this->Ta_usrio->get_usrionombres_porrut($rut);			
			
			$data['nombres'] = $metadata[0]['nombres'];
			$data['apellidos'] = $metadata[0]['apellidos'];
			$data['id_usrio'] = $metadata[0]['idt_usrio'];
			
			if(is_numeric($data['id_usrio'])){				
				
				$this->load->model('Tz_postulacionitem');
				$this->load->model('Tz_postulacionitem_tipodepago');
				$listado = $this->Tz_postulacionitem->get_actividades_asignarboleta($data['id_usrio']);
				$c = 0 ;
				foreach($listado as $value){				
					
					$suma = 0;
					$resp = $this->Tz_postulacionitem_tipodepago->get_pagos_boletas($value['IDPostulacionItem'],FALSE,TRUE);
					
					foreach($resp as $valor){
						$suma += $valor['c'];							
					}					
					
					if($suma > 0 AND $value['NroBoleta'] == ''){
						$mensaje1 = '<a href="/asignarboletasfacturas/boleta/'.$value['IDPostulacionItem'].'" class="popup_short2 blue" >boleta</a>';	
					}else{
						$mensaje1 = '<a href="/asignarboletasfacturas/boleta/'.$value['IDPostulacionItem'].'" class="popup_short2 blue">'.$value['NroBoleta'].'</a>';
					}
					
					$suma = 0;
					$resp = $this->Tz_postulacionitem_tipodepago->get_pagos_factura($value['IDPostulacionItem'],TRUE);
					foreach($resp as $valor2){
						$suma += $valor2['c'];							
					}
					
					if($suma > 0 AND $value['NroFactura'] == ''){
						$mensaje2 = '<a href="/asignarboletasfacturas/factura/'.$value['IDPostulacionItem'].'" class="popup_short2 blue" >factura</a>';	
					}else{
						$mensaje2 = '<a href="/asignarboletasfacturas/factura/'.$value['IDPostulacionItem'].'" class="popup_short2 blue" >'.$value['NroFactura'].'</a>';
					}					
					
					$data['actividades'][$c]['Nombre_Curso'] = $value['Nombre_Curso'];
					$data['actividades'][$c]['IDSeccion'] = $value['IDSeccion'];
					$data['actividades'][$c]['ValorNuevo'] = $value['ValorNuevo'];
					$data['actividades'][$c]['estado'] = $value['estado'];
					$data['actividades'][$c]['FechaInicio'] = $value['FechaInicio'];
					$data['actividades'][$c]['FechaTermino'] = $value['FechaTermino'];
					$data['actividades'][$c]['boleta'] = $mensaje1;
					$data['actividades'][$c]['factura'] = $mensaje2;
					$c++;
				}
				
				//$data['actividades'] = $matriculadas;	
			}
			
		}
		
		$data['page_title'] = $this->config->item('page_title').'Asignar Boletas / Facturas';	
		$data['menuusuario'] = self::menuusuario();
		$this->load->view('asignarboletasfacturas/index',$data);
		
	}
	
	function boleta($idpostulacionitem){
		
		$this->load->helper(array('url','form'));
		//Permisos modulo
		$this->load->model(array('Tz_permisos','Tz_postulacionitem_tipodepago','Tz_icboletas'));
		if($this->Tz_permisos->get_permiso_modulo(3) != TRUE){
			redirect('/accesodenegado');
		}
		
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('numero_boleta','numero boleta','trim|required');
		$resp = $this->Tz_postulacionitem_tipodepago->get_pagos_boletas($idpostulacionitem,FALSE,TRUE);
		
		
		if($this->form_validation->run() == TRUE){
			$tipos = $this->Tz_postulacionitem_tipodepago->get_formaspago_porpostulacionitem($idpostulacionitem);
			foreach($tipos as $value){
				
				$this->Tz_icboletas->update_boleta($idpostulacionitem,
				$value['IDTipoPago'],
				$this->input->post('numero_boleta'),
				$this->session->userdata('id_usuario'),
				$this->input->post('fecha'));
			}
			$data['message'] = 'Numero boleta asignado con exito.';	
		}			
		
		$bol = $this->Tz_icboletas->get_numero_boleta($idpostulacionitem);
		foreach($resp as $value){
			$suma_boletas += $value['c'];	
		}
		
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
		
		 
		$data['numero_boleta'] = $bol->NroBoleta;
		$data['fecha_boleta'] = $bol->Fecha;
		$data['total_boletas'] = $suma_boletas;		
		$data['idpostulacionitem'] = $idpostulacionitem;
		$this->load->view('asignarboletasfacturas/boleta',$data);
		
	}
	
	function factura($idpostulacionitem){
		
		$this->load->helper(array('url','form'));
		//Permisos modulo
		$this->load->model(array('Tz_permisos','Tz_postulacionitem_tipodepago','Tz_icfacturas'));
		if($this->Tz_permisos->get_permiso_modulo(3) != TRUE){
			redirect('/accesodenegado');
		}
		
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('numero_factura','numero factura','trim|required');
		$resp = $this->Tz_postulacionitem_tipodepago->get_pagos_factura($idpostulacionitem,TRUE);
		
		
		if($this->form_validation->run() == TRUE){
			$tipos = $this->Tz_postulacionitem_tipodepago->get_formaspago_porpostulacionitem($idpostulacionitem);
			foreach($tipos as $value){
				
				$this->Tz_icfacturas->update_factura($idpostulacionitem,
				$value['IDTipoPago'],$this->input->post('numero_factura'),
				$this->session->userdata('id_usuario'));
			}
			$data['message'] = 'Numero factura asignado con exito.';	
		}		
		
		foreach($resp as $value){
			$suma_facturas += $value['c'];	
		}
		$bol = $this->Tz_icfacturas->get_numero_factura($idpostulacionitem);
		$data['numero_factura'] = $bol->NroFactura;
		$data['total_facturas'] = $suma_facturas;		
		$data['idpostulacionitem'] = $idpostulacionitem;
		$this->load->view('asignarboletasfacturas/factura',$data);
		
	}
	
	
}
