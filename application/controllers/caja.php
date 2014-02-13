<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Caja extends MY_Controller{	

	public function index(){	
		
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(4) != TRUE){
			redirect('/accesodenegado');
		}		
		
		$this->load->helper(array('url','form'));
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
				$actividades = $this->Tz_postulacionitem->get_actapagar_porid($data['id_usrio']);
				$otras = $this->Tz_postulacionitem->get_otrasact_porid($data['id_usrio']);
				$postulando = $this->Tz_postulacionitem->get_act_postulando_porid($data['id_usrio']);
				$data['actividades_apagar'] = $actividades;
				$data['otras_actividades'] = $otras;
				$data['postulando'] = $postulando;	
			}
			
		}
		
		$data['menuusuario'] = self::menuusuario();
		$data['page_title'] = $this->config->item('page_title').'Sistema caja'; 
		$this->load->view('caja/index',$data);	
		
	}
	
	function descuentolibre($idpostulacionitem,$id_seccion){
		
		$this->load->helper(array('url','form'));		
		$this->load->library(array('form_validation'));
	
		$this->form_validation->set_rules('monto','monto','trim|required|greater_than[0]|is_natural_no_zero|callback_onlynumbers');
		$this->form_validation->set_rules('motivo','motivo','trim|required');
			
		if($this->form_validation->run() == TRUE){
			
			$this->load->model('Sgp_descuentos');
			
			$respuesta = $this->Sgp_descuentos->update_descuentos_libre(
			$idpostulacionitem,
			$this->input->post('tipo'),
			$this->input->post('monto'),
			$this->session->userdata('id_usuario'),
			$this->input->post('motivo'));
			
			$this->session->set_flashdata('error_desc',$respuesta);
		}else{
			$this->session->set_flashdata('error_desc',validation_errors('<li>','</li>'));		
		}
		
		redirect('/caja/descuento/'.$idpostulacionitem.'/'.$id_seccion);
		
	}
	
	function descuento($idpostulacionitem,$id_seccion){
		
		$this->load->helper(array('url','form'));
		$this->load->model('Sgp_descuentos');		
		if($this->input->server('REQUEST_METHOD') == 'POST'){
								
			$data['error'] = $this->Sgp_descuentos->update_descuentos($idpostulacionitem,$this->input->post('descuento'),$id_seccion,$this->session->userdata('id_usuario'));
			
		}
		
		$data['descuentos'] = $this->Sgp_descuentos->get_descuentos($idpostulacionitem);
		$data['descuentos_libres'] = $this->Sgp_descuentos->get_descuentos_libres($idpostulacionitem);
		$data['descuentos_seccion'] = $this->Sgp_descuentos->get_descuentos_seccion($idpostulacionitem,$id_seccion);
		$data['idpostulacionitem'] = $idpostulacionitem;
		$data['id_seccion'] = $id_seccion;	
		
		$data['page_title'] = $this->config->item('page_title').'Descuentos'; 
		$this->load->view('caja/descuento',$data);	
			
	}
	
	function informe(){
		
		$this->load->helper(array('url','form'));		
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(7) != TRUE){
			redirect('/accesodenegado');
		}
		
		$this->load->library(array('form_validation'));
		$this->load->model(array('Ta_metadatodetalle','Tz_icboletas','Tz_icfacturas'));
		$data['formas_pago'] = $this->Ta_metadatodetalle->get_tipos_pago();
		
		//$this->form_validation->set_rules('cajero','cajero','trim|required');
		$this->form_validation->set_rules('pago','informe','trim|required');
		$this->form_validation->set_rules('tipo_documento','documento','trim|required');
		
		
		$data['seleccion'] = $this->input->post('cajero');
		$data['seleccion2'] = $this->input->post('pago');
		$data['seleccion3'] = $this->input->post('tipo_documento');
		$data['cajeros'] = $this->Tz_permisos->get_usuarios_modulo(4);
		
		$data['menuusuario'] = self::menuusuario();
		$data['page_title'] = $this->config->item('page_title').'Informe de caja'; 
		
		
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
		
		
		if($this->form_validation->run() == TRUE){
			
			$idpago = $this->input->post('pago');
			$desde = $this->input->post('desde');
			$hasta = $this->input->post('hasta');
			if($this->input->post('tipo_documento') == '0'){
				switch($idpago){
					
					case 1438 :
					$data['efectivo'] = $this->Tz_icboletas->informe_efectivo($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/efectivo',$data);
					break;
					case 1439 :
					$data['dia'] = $this->Tz_icboletas->informe_dia($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/dia',$data);
					break;
					case 1440 :
					$data['fecha'] = $this->Tz_icboletas->informe_fecha($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/fecha',$data);
					break;
					case 3860 :
					$data['deposito'] = $this->Tz_icboletas->informe_deposito($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/deposito',$data);
					break;
					case 1441 :
					$data['tarjeta'] = $this->Tz_icboletas->informe_tarjeta($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/tarjeta',$data);
					break;
					case 3805 :
					$data['debito'] = $this->Tz_icboletas->informe_debito($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/debito',$data);
					break;
					case 10000 :
					$data['general'] = $this->Tz_icboletas->informe_general($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/general',$data);
					break;
					case 3862 :
					$data['transferencia'] = $this->Tz_icboletas->informe_transferencia($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/transferencia',$data);
					break;
					case 3861 :
					$data['valevista'] = $this->Tz_icboletas->informe_valevista($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/valevista',$data);
					break;
					case 1442 :
					$data['pagare'] = $this->Tz_icboletas->informe_pagare($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/pagare',$data);
					break;
					default:
					$this->load->view('informes/cierrecaja',$data);
					break;
				}
			}
			if($this->input->post('tipo_documento') == 1){
				
				switch($idpago){
					
					case 1438 :
					$data['efectivo'] = $this->Tz_icfacturas->informe_efectivo($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/efectivofactura',$data);
					break;
					case 1439 :
					$data['dia'] = $this->Tz_icfacturas->informe_dia($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/diafactura',$data);
					break;
					case 1440 :
					$data['fecha'] = $this->Tz_icfacturas->informe_fecha($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/fechafactura',$data);
					break;
					case 3860 :
					$data['deposito'] = $this->Tz_icfacturas->informe_deposito($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/depositofactura',$data);
					break;
					case 1441 :
					$data['tarjeta'] = $this->Tz_icfacturas->informe_tarjeta($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/tarjetafactura',$data);
					break;
					case 3805 :
					$data['debito'] = $this->Tz_icfacturas->informe_debito($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/debitofactura',$data);
					break;
					case 10000 :
					$data['general'] = $this->Tz_icfacturas->informe_general($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/generalfactura',$data);
					break;
					case 3862 :
					$data['transferencia'] = $this->Tz_icfacturas->informe_transferencia($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/transferenciafactura',$data);
					break;
					case 3861 :
					$data['valevista'] = $this->Tz_icfacturas->informe_valevista($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/valevistafactura',$data);
					break;
					case 1442 :
					$data['pagare'] = $this->Tz_icfacturas->informe_pagare($this->input->post('cajero'),$desde,$hasta);
					$this->load->view('informes/cierrecaja',$data);
					$this->load->view('informes/pagarefactura',$data);
					break;
					default:
					$this->load->view('informes/cierrecaja',$data);
					break;
				}
				
			}
			
			
		}else{
			$this->load->view('informes/cierrecaja',$data);	
		}
			
		
	}
	
	function imprimirboleta($idpostulacionitem,$numero_boleta = NULL){
		
		$this->load->helper(array('url','form'));
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(4) != TRUE){
			redirect('/accesodenegado');
		}
		
		  $this->load->model(array('Tz_icboletas','Tz_postulacionitem_tipodepago'));
		  $data['boleta'] = $this->Tz_icboletas->get_imprimir($idpostulacionitem,$numero_boleta);
		  $res = $this->Tz_postulacionitem_tipodepago->get_formaspago_porpostulacionitem_boleta($idpostulacionitem,$numero_boleta);
		
		  foreach($res as $value){		
			  
			$data['tiene_pago'] = 's';
			$data['pagos_padre'][$x]['nombre_pago'] = $value['nombre_pago'];
			$data['pagos_padre'][$x]['FechaCreacion'] = $value['FechaCreacion'];
			$data['pagos_padre'][$x]['creador'] = $value['creador'];
			$data['pagos_padre'][$x]['IDPostulacionItem'] = $value['IDPostulacionItem'];
			$data['pagos_padre'][$x]['IDTipoPago'] = $value['IDTipoPago'];
			
			if($value['IDTipoPago']){
						
				$res2 = $this->Tz_postulacionitem_tipodepago->get_detalle_delpago($idpostulacionitem,$value['IDTipoPago'],$numero_boleta);		
				$data['pagos_padre'][$x]['detalle'] = $res2;
				
				$sum = 0;			
				foreach($res2 as $suma){							
					$sum += $suma['Monto'];	
				}				
				$data['pagos_padre'][$x]['suma_total'] = $sum;
				$data['totales'] += $sum;
				
			}
			$x++;
			
		  }
		  
		  $data['page_title'] = $this->config->item('page_title').'Boleta No. '.$numero_boleta; 
		  $this->load->view('caja/imprimirboleta',$data);
		
	}
	
	function documento($idpostulacionitem){
		
		$this->load->helper(array('url','form'));
		$this->load->library(array('form_validation'));
		$id = base64_decode($idpostulacionitem);
		
		if(!is_numeric($id)){
			$this->session->set_flashdata('error','Acceso directo no permitido, los datos no son validos');	
			redirect('/caja');
		}
		
		//$this->Tz_postulacionitem_tipodepago->get_matricula_alumno(implode(',',$this->input->post('a_pagar')));						
		//$this->session->set_flashdata('error','Actividad matriculada con exito');
		//$this->input->post('rut');
		$this->load->model('Tz_postulacionitem');
		$estado = $this->Tz_postulacionitem->get_estado_alumno($id);
		$estado_final = $estado->estado;
		
		$data['idpostulacionitem'] = $idpostulacionitem;
		//$estado_final = 5;
		
		if($estado_final == 3 OR $estado_final == 12){
			
			$this->load->model('Tz_postulacionitem_tipodepago');			
			$data['boletas'] = $this->Tz_postulacionitem_tipodepago->get_pagos_boletas($id);
			$data['facturas'] = $this->Tz_postulacionitem_tipodepago->get_pagos_factura($id);
			
			foreach($data['facturas'] as $value){
				$data['suma_facturas'] += $value['c'];	
			}
			
			foreach($data['boletas'] as $value){
				$data['suma_boletas'] += $value['c'];	
			}
			
			if($data['suma_boletas'] > 0 ){
				$this->form_validation->set_rules('numero_boleta','numero de boleta','trim|required');
			}
			
			if($data['suma_facturas'] > 0 ){
				$this->form_validation->set_rules('numero_boleta','numero de boleta','trim|required');
			}	
			
			$data['menuusuario'] = self::menuusuario();
			$data['page_title'] = $this->config->item('page_title').'Sistema caja'; 			
			$this->load->view('caja/documento',$data);	
		}else{
			
			$this->session->set_flashdata('error','No puede ingresar numero(s) de documento(s) si el alumno no esta matriculado.');
			redirect('/caja/cancelar/'.$idpostulacionitem);
		}			
		
	}
	
	function cancelarpago(){
		//Eliminar pagos hechos antes de matricular alumno
		$idpostulacionitem = $this->input->post('a_pagar');		
		$this->load->model('Tz_postulacionitem_tipodepago');
		$this->Tz_postulacionitem_tipodepago->delete_pagos($idpostulacionitem);
		redirect('/caja');
	}
	
	function procesapago(){
		$this->load->helper(array('url','form'));
		$this->load->library(array('form_validation'));
		
		$this->load->model('Tz_postulacionitem_tipodepago');
		$idpostulacionitem = implode(',',$this->input->post('a_pagar'));
		
		if($idpostulacionitem){
			$respuesta = $this->Tz_postulacionitem_tipodepago->get_procesapago(implode(',',$this->input->post('a_pagar')));			
		
			if($respuesta->resp == 2){
				
				$this->Tz_postulacionitem_tipodepago->get_matricula_alumno($idpostulacionitem);						
				$this->session->set_flashdata('error','Actividad matriculada con exito.');
				redirect('/caja/documento/'.base64_encode(implode(',',$this->input->post('a_pagar'))));					
			}
			
			if($respuesta->resp == 3){
									
				$this->session->set_flashdata('error','Deuda es mayor a lo abonado.');
				redirect('/caja/cancelar/'.base64_encode(implode(',',$this->input->post('a_pagar'))));					
			}
			
			if($respuesta->resp == 4){
								
				$this->session->set_flashdata('error','Deuda es menor a lo abonado.');
				redirect('/caja/cancelar/'.base64_encode(implode(',',$this->input->post('a_pagar'))));					
			}
			
		}else{
			$this->session->set_flashdata('error','Debe indicar un alumno y una actividad');
			redirect('/caja');
		}
		
		
		
	}
	
	public function eliminarpago($tipo_pago,$idpago,$idpostulacionitem){	
	
		$this->load->helper(array('url'));
		$this->load->model('Tz_postulacionitem_tipodepago');
		$this->Tz_postulacionitem_tipodepago->eliminar_pago($tipo_pago,$idpago,FALSE,base64_decode($idpostulacionitem));
		redirect('/caja/cancelar/'.$idpostulacionitem);
		
	}
	
	public function comentario($idpostulacionitem = NULL){		
	
		$this->load->helper(array('url','form'));
		$this->load->model('Tz_postulacionitem');
		$this->load->library(array('form_validation'));
		
		$this->form_validation->set_rules('comentario','comentario','trim|required');
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$idpostulacionitem = $this->input->post('idpostulacionitem');	
		}	
		
		if($this->form_validation->run() == TRUE){
			$this->load->model('Tz_reservas');
			$this->Tz_reservas->put_comentario($this->input->post('comentario'),$idpostulacionitem,$this->session->userdata('id_usuario'));
		}
		
		$comment = $this->Tz_postulacionitem->get_comentario($idpostulacionitem);
		
		$data['idpostulacionitem'] = $idpostulacionitem;
		$data['comentarios'] = $comment;
		$this->load->view('caja/comentarios',$data);		
	}
	
	public function cancelar($idpostulacionitem = NULL){
		
		$this->load->helper(array('form','url'));
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(4) != TRUE){
			redirect('/accesodenegado');
		}			
		
		if($idpostulacionitem){
			$a_pagar = explode(',',base64_decode($idpostulacionitem));
		}else{
			$a_pagar = $this->input->post('a_pagar');
		}
		
		foreach($a_pagar as $value){
			if(!is_numeric($value)){
				redirect('/administrador/pagos');	
			}
		}		
					
		$data['a_pagar'] = $a_pagar;									
		
		if($a_pagar){
			
			$this->load->model('Tz_postulacionitem_tipodepago');	
			$this->load->model('Ta_metadatodetalle');
			
			$data['formas_pago'] = $this->Ta_metadatodetalle->get_tipos_pago();
				
			$datos_seccion = $this->Tz_postulacionitem_tipodepago->get_metadetallepago($a_pagar);					
			
			$c = 0;
			foreach($datos_seccion as $valor){	
							
				if($valor['bEstado'] == 3 OR $valor['bEstado'] == 12 OR $valor['bEstado'] == 9 ){
					$data['flash_error'] .= '<li>La actividad '.$valor['Nombre_Curso'].' no puede ser cancelada, favor verifique su estado</li>';					
					$c++;	
				}else{
					$a_pagar_new[] = $valor['IDPostulacionItem'];	
				}
			}
			
			if($data['flash_error']){
				$data['flash_error'] = '<div style="text-align:left">Tiene los siguientes errores :<br /><br /><ul>'.$data['flash_error'].'</ul></div>';	
			}				
			
			unset($a_pagar);
			$a_pagar = $a_pagar_new;
			if(count($a_pagar) > 0){
				
				$formas = $this->Tz_postulacionitem_tipodepago->get_formaspago_porpostulacionitem(implode(',',$a_pagar));
				$this->load->model('Tz_postulacionitem_tipodepago');			
				
				$c = 0;
				foreach($formas as $value){
					
					$det = $this->Tz_postulacionitem_tipodepago->get_detalle_delpago(implode(',',$a_pagar),$value['IDTipoPago']);
					
					$data['pagos'][$c]['nombre_pago'] = $value['nombre_pago'];
					$data['pagos'][$c]['creador'] = $value['creador'];
					$data['pagos'][$c]['IDTipoPago'] = $value['IDTipoPago'];
					$data['pagos'][$c]['detalle'] = $det;
					$sum = 0 ;
					foreach($det as $valor){						
						$sum += $valor['Monto'];
					}
					$data['pagos'][$c]['suma_tipo'] = $sum;
					$c++;
				}
				
				$datos_seccion = $this->Tz_postulacionitem_tipodepago->get_metadetallepago($a_pagar);
			}else{
				$datos_seccion = array();	
			}
			
		
		}else{						
			$data['flash_error'] = 'No ha seleccionado ninguna actividad.';
		}
		
		$data['seccion'] = $datos_seccion;
		
		foreach($datos_seccion as $valor){
			$data['total_arecaudar'] += $valor['ValorNuevo'];	
		}		
		$data['menuusuario'] = self::menuusuario();
		$data['page_title'] = $this->config->item('page_title').'Detalle pago';
		$this->load->view('caja/cancelando',$data);		
	
	}	
	
	public function pago($tipopago,$idpostulacionitem){
		
		$this->load->helper(array('url','form'));
		
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(4) != TRUE){
			redirect('/accesodenegado');
		}
		
		$data['idpostulacionitem'] = $idpostulacionitem;
		$data['tipopago'] = $tipopago;
		$data['rango'] = 1;	
		
		switch($tipopago){
			case 1438 : 
				$page = 'pago_efectivo';
				$this->load->model('Tz_postulacionitem_tipodepago');
				$res = $this->Tz_postulacionitem_tipodepago->get_detalle_delpago(base64_decode($idpostulacionitem),1438);
				$data['monto'] = $res[0]['Monto'];
				$data['tipo_documento'] = $res[0]['IDTipoDocumento'];
				$title = 'Pago efectivo';
				break;
			case 1439 : 
				
				$allow = array(117503,192283,68996);		
				if(in_array($this->session->userdata('id_usuario'),$allow)){
					$data['datepicker1'] = "
					$('#calendar1').DatePicker({
						format:'d/m/Y',
						date: $(this).val(),
						current:$('#calendar1').val() ,
						starts: 1,
						position: 'left',
						onBeforeShow: function(){			
							$('#calendar1').DatePickerSetDate($('#calendar1').val(), true);
						},
						onChange: function(formated, dates){
							$('#calendar1').val(formated);			
						}
					});";
					
					$data['datepicker2'] = "
					$('#calendar2').DatePicker({
						format:'d/m/Y',
						date: $(this).val(),
						current:$('#calendar2').val() ,
						starts: 1,
						position: 'left',
						onBeforeShow: function(){			
							$('#calendar2').DatePickerSetDate($('#calendar2').val(), true);
						},
						onChange: function(formated, dates){
							$('#calendar2').val(formated);			
						}
					});";
				}
				
				$page = 'pago_cheque_dia';
				$title = 'Pago cheque día';
				$this->load->model('Tz_pago_cheque');
				$data['pagados'] = $this->Tz_pago_cheque->get_pagos_cheque_dia(base64_decode($idpostulacionitem));
				if(count($data['pagados']) < 1){
					$data['rango'] = 1;	
				}else{
					$data['rango'] = count($data['pagados']);	
				}				
				
				$this->load->model('Ta_metadatodetalle');
				$data['bancos'] = $this->Ta_metadatodetalle->get_bancos();				
				break;
			case 1440 : 
				$page = 'pago_cheque_fecha';				
				$title = 'Pago cheque a fecha';
				$this->load->model('Tz_pago_cheque');
				$data['pagados'] = $this->Tz_pago_cheque->get_pagos_cheque_fecha(base64_decode($idpostulacionitem));
				if(count($data['pagados']) < 1){
					$data['rango'] = 1;	
				}else{
					$data['rango'] = count($data['pagados']);	
				}				
				
				$this->load->model('Ta_metadatodetalle');
				$data['bancos'] = $this->Ta_metadatodetalle->get_bancos();				
				break;
			case 3860 :
				$page = 'pago_deposito';
				$title = 'Deposito';
				$this->load->model('Tz_pagodeposito');
				$data['pagados'] = $this->Tz_pagodeposito->get_pagos_deposito(base64_decode($idpostulacionitem));
				if(count($data['pagados']) < 1){
					$data['rango'] = 1;	
				}else{
					$data['rango'] = count($data['pagados']);	
				}		
				break;
			case 3862 :
				$page = 'pago_transferencia';
				$title = 'Transferencia';
				$this->load->model('Tz_pagotransferencia');
				$data['pagados'] = $this->Tz_pagotransferencia->get_pagos_transferencia(base64_decode($idpostulacionitem));
				if(count($data['pagados']) < 1){
					$data['rango'] = 1;	
				}else{
					$data['rango'] = count($data['pagados']);	
				}
				$this->load->model('Ta_metadatodetalle');
				$data['bancos'] = $this->Ta_metadatodetalle->get_bancos();			
				break;
			case 3861 :
				$page = 'pago_valevista';
				$title = 'Vale Vista';
				$this->load->model('Tz_pagovalevista');
				$data['pagados'] = $this->Tz_pagovalevista->get_pagos_valevista(base64_decode($idpostulacionitem));
				if(count($data['pagados']) < 1){
					$data['rango'] = 1;	
				}else{
					$data['rango'] = count($data['pagados']);	
				}
				$this->load->model('Ta_metadatodetalle');
				$data['bancos'] = $this->Ta_metadatodetalle->get_bancos();			
				break;
			case 3805 :
				$page = 'pago_tarjeta_debito';
				$title = 'Tarjeta Debito';
				$this->load->model('Tz_pagotarjetadebito');
				$data['pagados'] = $this->Tz_pagotarjetadebito->get_pagos_tarjetadebito(base64_decode($idpostulacionitem));
				if(count($data['pagados']) < 1){
					$data['rango'] = 1;	
				}else{
					$data['rango'] = count($data['pagados']);	
				}
				$this->load->model('Ta_metadatodetalle');
				$data['bancos'] = $this->Ta_metadatodetalle->get_bancos();			
				break;
			case 1441 :
				$page = 'pago_tarjeta_credito';
				$title = 'Tarjeta Credito';
				$this->load->model('Tz_pagotarjetacredito');
				$data['pagados'] = $this->Tz_pagotarjetacredito->get_pagos_tarjetacredito(base64_decode($idpostulacionitem));
				if(count($data['pagados']) < 1){
					$data['rango'] = 1;	
				}else{
					$data['rango'] = count($data['pagados']);	
				}
				$this->load->model('Ta_metadatodetalle');
				$data['tarjetas'] = $this->Ta_metadatodetalle->get_tarjetas();			
				$data['bancos'] = $this->Ta_metadatodetalle->get_bancos();			
				break;
			case 3811:
			    //$idpostulacionitem = base64_encode(306075);
				$page = 'pago_porfacturar';
				$title = 'Pago por facturar Credito';
				$this->load->model('Tz_pagoporfacturar');
				$data['facturar_empresa'] = $this->Tz_pagoporfacturar->get_pagos_porfacturar_empresa(base64_decode($idpostulacionitem));
				$data['facturar_otic'] = $this->Tz_pagoporfacturar->get_pagos_porfacturar_otic(base64_decode($idpostulacionitem));
				if($data['facturar_otic']->IDOTIC != '' ){
					$data['tiene_otic'] = 's';	
				}
				$this->load->model('Tz_otic');
				$data['otics'] = $this->Tz_otic->get_otics();			
				$this->load->model('Tz_empresasregistradas');
				$data['empresas'] = $this->Tz_empresasregistradas->get_empresas();			
				break;
			case 1442:
			    //$idpostulacionitem = base64_encode(306075);
				$page = 'pago_pagare';
				$title = 'Pago pagaré';
				$this->load->model('Tz_pago_pagare');
				$data['pagados'] = $this->Tz_pago_pagare->get_pagos_pagare(base64_decode($idpostulacionitem));
				if(count($data['pagados']) < 1){
					$data['rango'] = 1;	
				}else{
					$data['rango'] = count($data['pagados']);	
				}						
				break;	
		}
		
		$data['page_title'] = $this->config->item('page_title').$title;
		$this->load->view('caja/'.$page,$data);
	}
	
	public function cantidaddoctos($tipopago,$idpostulacionitem){
		$this->load->helper(array('form','url'));
		$data['rango'] = $this->input->post('cant');
		$data['tipo_documento'] = NULL;
		$data['flash_error'] = NULL;
		$data['pagados'] = array();
		
		$this->load->model('Ta_metadatodetalle');
		$data['bancos'] = $this->Ta_metadatodetalle->get_bancos();
		switch($tipopago){
			//cheque dia 
			case 1439 :
				$allow = array(117503,192283,68996);		
				if(in_array($this->session->userdata('id_usuario'),$allow)){
					$data['datepicker1'] = "
					$('#calendar1').DatePicker({
						format:'d/m/Y',
						date: $(this).val(),
						current:$('#calendar1').val() ,
						starts: 1,
						position: 'right',
						onBeforeShow: function(){			
							$('#calendar1').DatePickerSetDate($('#calendar1').val(), true);
						},
						onChange: function(formated, dates){
							$('#calendar1').val(formated);			
						}
					});";
					
					$data['datepicker2'] = "
					$('#calendar2').DatePicker({
						format:'d/m/Y',
						date: $(this).val(),
						current:$('#calendar2').val() ,
						starts: 1,
						position: 'right',
						onBeforeShow: function(){			
							$('#calendar2').DatePickerSetDate($('#calendar2').val(), true);
						},
						onChange: function(formated, dates){
							$('#calendar2').val(formated);			
						}
					});";
				}
				$page = 'pago_cheque_dia';
				$title = 'Cheques al día';
				$this->load->model('Tz_pago_cheque');
				$data['pagados'] = $this->Tz_pago_cheque->get_pagos_cheque_dia(base64_decode($idpostulacionitem));		
				break;
			case 1440 :
				$page = 'pago_cheque_fecha';
				$title = 'Cheques a fecha';
				$this->load->model('Tz_pago_cheque');
				$data['pagados'] = $this->Tz_pago_cheque->get_pagos_cheque_fecha(base64_decode($idpostulacionitem));		
				break;
			case 3860 :
				$page = 'pago_deposito';
				$title = 'Deposito';
				$this->load->model('Tz_pagodeposito');
				$data['pagados'] = $this->Tz_pagodeposito->get_pagos_deposito(base64_decode($idpostulacionitem));		
				break;
			case 3862 :
				$page = 'pago_transferencia';
				$title = 'Transferencia';
				$this->load->model('Tz_pagotransferencia');
				$data['pagados'] = $this->Tz_pagotransferencia->get_pagos_transferencia(base64_decode($idpostulacionitem));		
				break;
			case 3861 :
				$page = 'pago_valevista';
				$title = 'Vale Vista';
				$this->load->model('Tz_pagovalevista');
				$data['pagados'] = $this->Tz_pagovalevista->get_pagos_valevista(base64_decode($idpostulacionitem));		
				break;
			case 3805 :
				$page = 'pago_tarjeta_debito';
				$title = 'Tarjeta Debito';
				$this->load->model('Tz_pagotarjetadebito');
				$data['pagados'] = $this->Tz_pagotarjetadebito->get_pagos_tarjetadebito(base64_decode($idpostulacionitem));		
				break;
			case 1441 :
				$page = 'pago_tarjeta_credito';
				$title = 'Tarjeta Credito';
				$this->load->model('Ta_metadatodetalle');
				$data['tarjetas'] = $this->Ta_metadatodetalle->get_tarjetas();	
				$this->load->model('Tz_pagotarjetacredito');
				$data['pagados'] = $this->Tz_pagotarjetacredito->get_pagos_tarjetacredito(base64_decode($idpostulacionitem));		
				break;
			//pagare 
			case 1442 :
				$page = 'pago_pagare';
				$title = 'Pagaré';
				$this->load->model('Tz_pago_pagare');
				$data['pagados'] = $this->Tz_pago_pagare->get_pagos_pagare(base64_decode($idpostulacionitem));		
				break;		
			
		}		
			
		$data['idpostulacionitem'] = $idpostulacionitem;		
		$data['page_title'] = $this->config->item('page_title').$title;
		$this->load->view('caja/'.$page,$data);
			
		
	}
	
	public function registrarpago($tipopago,$idpostulacionitem){		
	
		$this->load->helper(array('form','url'));
		$this->load->library(array('form_validation'));
		$data['tipo_documento'] = $this->input->post('tipo_documento');	
		$data['rango'] = $this->input->post('cant');	
		
		$this->load->model('Tz_postulacionitem');
		$resp = $this->Tz_postulacionitem->get_estado_alumno(base64_decode($idpostulacionitem));
		$estado = $resp->bEstado;
		
		if($estado == 3 OR $estado == 12 OR $estado == 9){
			$data['flash_error'] = 'Alumno '.$resp->estado.', no puede procesar este pago.';
		}else{
		
			switch($tipopago){
				//Efectivo
				case 1438 :							
				$this->form_validation->set_rules('monto','monto','trim|required|callback_onlynumbers|numeric');
				$this->form_validation->set_rules('tipo_documento','tipo documento','trim|required');
				break;
				//Cheque dia 
				case 1439 :			
				$this->form_validation->set_rules('monto[]','monto','trim|required|callback_onlynumbers|numeric|greater_than[0]');					
				break;
				//cheque fecha	
				case 1440 :			
				$this->form_validation->set_rules('monto[]','monto','trim|required|callback_onlynumbers|numeric|greater_than[0]');
				$this->form_validation->set_rules('rut_girador[]','rut girador','trim|required|callback_formatorut|min_length[2]');
				//$this->form_validation->set_rules('email_girador[]','email girador','trim|callback_lowercase|valid_emails');
				$this->form_validation->set_rules('serie[]','serie','trim|callback_onlynumbers');
				$this->form_validation->set_rules('nombre_girador[]','nombre girador','trim|callback_upperwords');					
				break;				
				case 3860 :			
				$this->form_validation->set_rules('monto[]','monto','trim|required|callback_onlynumbers|numeric|greater_than[0]');									
				break;
				case 3862 :			
				$this->form_validation->set_rules('monto[]','monto','trim|required|callback_onlynumbers|numeric|greater_than[0]');									
				break;
				case 3861 :			
				$this->form_validation->set_rules('monto[]','monto','trim|required|callback_onlynumbers|numeric|greater_than[0]');									
				break;
				case 3805 :			
				$this->form_validation->set_rules('monto[]','monto','trim|required|callback_onlynumbers|numeric|greater_than[0]');
				$this->form_validation->set_rules('digitos[]','4 Ult. digitos','trim|required|callback_onlynumbers|numeric|exact_length[4]');									
				break;				
				case 1441 :			
				$this->form_validation->set_rules('monto[]','monto','trim|required|callback_onlynumbers|numeric|greater_than[0]');			
				$this->form_validation->set_rules('cuatro[]','Nro tarjeta 4','trim|required|numeric|exact_length[4]');										
				break;
				case 3811 :
				$this->form_validation->set_rules('empresa','empresa','trim|required');	
				if($this->input->post('empresa')){
					$this->form_validation->set_rules('monto_empresa','monto empresa','trim|callback_onlynumbers|numeric|greater_than[0]');										
				}				
				if($this->input->post('conotic') == 's'){
					$this->form_validation->set_rules('otic','otic','trim|required');	
					$this->form_validation->set_rules('monto_otic','monto OTIC','trim|callback_onlynumbers|numeric|greater_than[0]');										
				}
				break;
				case 1442 :
				$this->form_validation->set_rules('numero_pagare[]','numero pagare','trim|required');
				$this->form_validation->set_rules('interes[]','interes','trim|numeric|callback_onlynumbers');
				$this->form_validation->set_rules('fecha[]','fecha vcto','trim|required');
				$this->form_validation->set_rules('monto[]','monto','trim|numeric|callback_onlynumbers|greater_than[0]');
				break;
			}			
			
			if($this->form_validation->run() == TRUE){	
				
				if(is_numeric($tipopago)){
					
					switch($tipopago){
						//Efectivo
						case 1438 :						
						$this->load->model('Tz_pago_efectivo');					
						$this->Tz_pago_efectivo->update_pago_efectivo(
							base64_decode($idpostulacionitem),
							$this->input->post('monto'),
							$this->session->userdata('id_usuario'),
							$this->input->post('tipo_documento')
						);
						break;
						//Cheque dia 
						case 1439 :
						$this->load->model('Tz_pago_cheque');
						$monto = $this->input->post('monto');
						$ctacte = $this->input->post('ctacte');
						$serie = $this->input->post('serie');
						$fecha = $this->input->post('fecha');
						$banco = $this->input->post('banco');
						$idpago = $this->input->post('id_pago');
						$tipo_docto = $this->input->post('tipo_documento');
						$cantidad_cheques = $this->input->post('cant_cheques');
						
						foreach($tipo_docto as $key => $value){					
							
							$this->Tz_pago_cheque->update_pago_cheque_dia(
								base64_decode($idpostulacionitem),
								$monto[$key],
								$this->session->userdata('id_usuario'),
								$tipo_docto[$key],
								$banco[$key],
								$ctacte[$key],
								$serie[$key],
								$fecha[$key],
								$cantidad_cheques,
								$idpago[$key]
							);								
						}												
						break;
						//Cheque fecha 
						case 1440 :
						$this->load->model('Tz_pago_cheque');
						$monto = $this->input->post('monto');
						$ctacte = $this->input->post('ctacte');
						$serie = $this->input->post('serie');
						$fecha = $this->input->post('fecha');
						$banco = $this->input->post('banco');
						$idpago = $this->input->post('id_pago');
						$tipo_docto = $this->input->post('tipo_documento');
						$cantidad_cheques = $this->input->post('cant_cheques');
						
						$rut_gir = $this->input->post('rut_girador');
						$nom_gir = $this->input->post('nombre_girador');
						$email_gir = $this->input->post('email_girador');
						$tel_gir = $this->input->post('telefono_girador');
						
						foreach($tipo_docto as $key => $value){					
							
							$this->Tz_pago_cheque->update_pago_cheque_fecha(
								base64_decode($idpostulacionitem),
								($key + 1 ),
								$monto[$key],
								$this->session->userdata('id_usuario'),
								$tipo_docto[$key],
								$banco[$key],
								$ctacte[$key],
								$serie[$key],
								$fecha[$key],
								$cantidad_cheques,
								$idpago[$key],
								$nom_gir[$key],
								$email_gir[$key],
								$tel_gir[$key],
								$rut_gir[$key]
							);								
						}												
						break;
						case 3860 :
						$this->load->model('Tz_pagodeposito');
						$monto = $this->input->post('monto');
						$comprobante = $this->input->post('comprobante');					
						$fecha = $this->input->post('fecha');					
						$idpago = $this->input->post('id_pago');
						$tipo_docto = $this->input->post('tipo_documento');
						$cantidad_documentos = $this->input->post('cant');
						
						foreach($tipo_docto as $key => $value){								
							
							$this->Tz_pagodeposito->update_pago_deposito(
								base64_decode($idpostulacionitem),
								$monto[$key],
								$tipo_docto[$key],
								$comprobante[$key],
								$fecha[$key],
								$cantidad_documentos,
								($key + 1),
								$idpago[$key]
							);								
						}												
						break;
						case 3862 :
						$this->load->model('Tz_pagotransferencia');
						$monto = $this->input->post('monto');
						$comprobante = $this->input->post('comprobante');					
						$fecha = $this->input->post('fecha');					
						$idpago = $this->input->post('id_pago');
						$banco = $this->input->post('banco');
						$tipo_docto = $this->input->post('tipo_documento');
						$cantidad_documentos = $this->input->post('cant');
						
						foreach($tipo_docto as $key => $value){								
							
							$this->Tz_pagotransferencia->update_pago_transferencia(
								base64_decode($idpostulacionitem),
								$monto[$key],
								$tipo_docto[$key],
								$comprobante[$key],							
								$cantidad_documentos,
								($key + 1),
								$banco[$key],
								$idpago[$key]
							);								
						}												
						break;
						//Vale vista 
						case 3861 :
						$this->load->model('Tz_pagovalevista');
						$monto = $this->input->post('monto');
						$comprobante = $this->input->post('comprobante');					
						$fecha = $this->input->post('fecha');
						$banco = $this->input->post('banco');
						$idpago = $this->input->post('id_pago');
						$tipo_docto = $this->input->post('tipo_documento');
						$cantidad_cheques = $this->input->post('cant');
						
						foreach($tipo_docto as $key => $value){					
							
							$this->Tz_pagovalevista->update_pago_valevista(
								base64_decode($idpostulacionitem),
								$monto[$key],
								$tipo_docto[$key],
								$comprobante[$key],
								$cantidad_cheques,
								($key +  1),
								$banco[$key],
								$idpago[$key],
								$fecha[$key]								
							);								
						}												
						break;
						//Tarjeta de debito 
						case 3805 :
						$this->load->model('Tz_pagotarjetadebito');
						$monto = $this->input->post('monto');
						$codigo = $this->input->post('codigo');					
						$fecha = $this->input->post('fecha');
						$digitos = $this->input->post('digitos');
						$banco = $this->input->post('banco');
						$idpago = $this->input->post('id_pago');
						$tipo_docto = $this->input->post('tipo_documento');
						$cantidad_cheques = $this->input->post('cant');
						
						foreach($tipo_docto as $key => $value){					
							
							$this->Tz_pagotarjetadebito->update_pago_tarjetadebito(
								base64_decode($idpostulacionitem),
								$banco[$key],
								$monto[$key],							
								$fecha[$key],
								$codigo[$key],
								$digitos[$key],
								$tipo_docto[$key],
								$cantidad_cheques,
								($key +  1),
								$idpago[$key]								
							);								
						}												
						break;
						//Tarjeta Credito 
						case 1441 :
						$this->load->model('Tz_pagotarjetacredito');
						$monto = $this->input->post('monto');						
						$cantidad_cheques = $this->input->post('cant');
						$tarjeta = $this->input->post('tarjeta');
						$uno = $this->input->post('uno');
						$dos = $this->input->post('dos');
						$tres = $this->input->post('tres');
						$cuatro = $this->input->post('cuatro');
						$mes = $this->input->post('mes');
						$anio = $this->input->post('anio');
						$banco = $this->input->post('banco');
						$transaccion = $this->input->post('transaccion');
						$tipo_documento = $this->input->post('tipo_documento');
						$idpago = $this->input->post('id_pago');
						
						foreach($tipo_documento as $key => $value){					
						
							$this->Tz_pagotarjetacredito->update_pago_tarjetacredito(
								base64_decode($idpostulacionitem),
								$tarjeta[$key],
								$banco[$key],							
								$uno[$key],
								$dos[$key],
								$tres[$key],
								$cuatro[$key],
								$mes[$key],
								$anio[$key],
								$monto[$key],
								$transaccion[$key],
								$tipo_documento[$key],
								$cantidad_cheques,
								($key +  1),
								$idpago[$key]								
							);								
						}												
						break;
						case 3811:
							$this->load->model('Tz_pagoporfacturar');
							$resp = $this->Tz_pagoporfacturar->get_valida_deuda(base64_decode($idpostulacionitem));
							$resp = explode('-',$resp->resp);
							
							$monto_empresa = $this->input->post('monto_empresa');							
							$descripcion_empresa = $this->input->post('descripcion_empresa');
							$factura_empresa = $this->input->post('factura_empresa');
							$fecha_empresa = $this->input->post('fecha_empresa');
							$empresa = $this->input->post('empresa');
							$orden_empresa = $this->input->post('orden_empresa');
							$id_empresa = $this->input->post('id_empresa');						
							
						
							$monto_otic = $this->input->post('monto_otic');
							$descripcion_otic = $this->input->post('descripcion_otic');
							$factura_otic = $this->input->post('factura_otic');
							$fecha_otic = $this->input->post('fecha_otic');
							$otic = $this->input->post('otic');
							$orden_otic = $this->input->post('orden_otic');
							$id_otic = $this->input->post('id_otic');
							
							$suma_montos = $monto_empresa + $monto_otic;
							
							$pagado = $resp[0];
							$deuda = $resp[1];
							
							if(($suma_montos + $pagado) > $deuda){
								$this->session->set_flashdata('error','Los montos abonados superan la deuda, favor verificar montos');
							}
							
							if(($suma_montos + $pagado) == $deuda){
								$this->Tz_pagoporfacturar->update_pago_porfacturar_empresa(base64_decode($idpostulacionitem),$monto_empresa,
								$descripcion_empresa,$factura_empresa,$fecha_empresa,$empresa,$orden_empresa,$id_empresa);
								
								if($this->input->post('conotic') == 's'){
									$this->Tz_pagoporfacturar->update_pago_porfacturar_otic(base64_decode($idpostulacionitem),$monto_otic,
									$descripcion_otic,$factura_otic,$fecha_otic,$otic,$orden_otic,$id_otic);									
								}
								
							}	
							break;
						case 1442 :
						$this->load->model('Tz_pago_pagare');
						
						$tipo_documento = $this->input->post('tipo_documento');						
						$legalizado = $this->input->post('legalizado');
						$numero_pagare = $this->input->post('numero_pagare');
						$interes = $this->input->post('interes');
						$fecha = $this->input->post('fecha');
						$monto = $this->input->post('monto');
						$id_pago = $this->input->post('id_pago');
						$cantidad_pagares = $this->input->post('cant');
						
						$monto_total = array_sum($monto);
						
						foreach($tipo_documento as $key => $value){											
						
							$this->Tz_pago_pagare->update_pago_pagare(	base64_decode($idpostulacionitem),
								$legalizado[$key],
								$numero_pagare[$key],
								$cantidad_pagares,
								$monto_total,
								$monto[$key],
								($key + 1),
								$fecha[$key],
								$tipo_documento[$key],
								$cantidad_pagares,
								$this->session->userdata('id_usuario'),
								$interes[$key],
								$id_pago[$key]	);								
						}												
						break;
						
					}
				
				}
			}else{
				$this->session->set_flashdata('error',validation_errors('<ul><li>','</li></ul>'));				
			}
		}			
		
		redirect('/caja/pago/'.$tipopago.'/'.$idpostulacionitem);	
			
	}
	
	public function detallepago($idpostulacionitem){
		
		$this->load->helper(array('url','form'));
		
		if(!is_numeric($idpostulacionitem)){
			redirect('/moduloincorrecto');
		}
				
		$data['idpostulacionitem'] = $idpostulacionitem;
		$this->load->model(array('Tz_postulacionitem_tipodepago','Tz_grupospago'));
		$res = $this->Tz_postulacionitem_tipodepago->get_formaspago_porpostulacionitem($idpostulacionitem);
		$grupos = $this->Tz_grupospago->get_grupo($idpostulacionitem);
		if(count($grupos) > 0){
			
			foreach($grupos as $value){
				$detalle = $this->Tz_postulacionitem_tipodepago->get_metadetallepago($value['IdPostulacionItem'],FALSE);				
				$data['meta_datos'][] = array(
				'rut' => $detalle->rut,
				'NombreApellido' => $detalle->NombreApellido,
				'Nombre_Curso' => $detalle->Nombre_Curso,
				'ValorNuevo' => $detalle->ValorNuevo,
				'solo_cuota' => $detalle->solo_cuota,
				'IDSeccion' => $detalle->IDSeccion);
				unset($detalle);
			}
		}else{
			$detalle = $this->Tz_postulacionitem_tipodepago->get_metadetallepago($idpostulacionitem,FALSE);				
			$data['meta_datos'][] = array(
			'rut' => $detalle->rut,
			'NombreApellido' => $detalle->NombreApellido,
			'Nombre_Curso' => $detalle->Nombre_Curso,
			'ValorNuevo' => $detalle->ValorNuevo,
			'solo_cuota' => $detalle->solo_cuota,
			'IDSeccion' => $detalle->IDSeccion);
			unset($detalle);
		}
		$boleta = $this->Tz_postulacionitem_tipodepago->get_detalle_boleta($idpostulacionitem);
		$factura = $this->Tz_postulacionitem_tipodepago->get_detalle_factura($idpostulacionitem);
		
		$data['boleta'] = $boleta;
		$data['factura'] = $factura;	
		
		$x = 0;
		$data['tiene_pago'] = 'n';
		$data['totales'] = 0;		
		
		foreach($res as $value){
			$data['tiene_pago'] = 's';
			$data['pagos_padre'][$x]['nombre_pago'] = $value['nombre_pago'];
			$data['pagos_padre'][$x]['FechaCreacion'] = $value['FechaCreacion'];
			$data['pagos_padre'][$x]['creador'] = $value['creador'];
			$data['pagos_padre'][$x]['IDPostulacionItem'] = $value['IDPostulacionItem'];
			$data['pagos_padre'][$x]['IDTipoPago'] = $value['IDTipoPago'];
			
			if($value['IDTipoPago']){			
			
				$res2 = $this->Tz_postulacionitem_tipodepago->get_detalle_delpago($idpostulacionitem,$value['IDTipoPago']);		
				$data['pagos_padre'][$x]['detalle'] = $res2;
				
				$sum = 0;			
				foreach($res2 as $suma){							
					$sum += $suma['Monto'];	
				}				
				$data['pagos_padre'][$x]['suma_total'] = $sum;
				$data['totales'] += $sum;
				
			}
			$x++;
		}		
		
		$data['page_title'] = $this->config->item('page_title').'Detalle del pago'; 	
		$this->load->view('caja/detallepago',$data);	
		
	}
	
	function onlynumbers($str){
		return filter_var($str, FILTER_SANITIZE_NUMBER_INT); 
	}
	
	function formatorut($str){
		
		$none = array('-','.');
		$str = str_replace($none,'',$str);
		$rut = substr($str,0,strlen($str) - 1);
		$guion = substr(filter_var($str,FILTER_SANITIZE_NUMBER_INT),strlen($str) - 1,1);
		return number_format($rut,0,',','.').'-'.$guion;			
	}
	
	function upperwords($str){
		return ucwords(strtolower($str));
	}
	
	function lowercase($str){
		return strtolower($str);
	}
	
}
