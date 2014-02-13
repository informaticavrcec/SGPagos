<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrador extends MY_Controller {
	
	function index(){
			
	}
	
	function eliminarusuario($id_usuario){
		
		$this->load->helper(array('url','form'));
		$this->load->model(array('Tz_permisos'));		
		if($this->Tz_permisos->get_permiso_modulo(2) != TRUE){
			redirect('/accesodenegado');
		}
		
		if(is_numeric($id_usuario)){
			$this->Tz_permisos->delete_usuario($id_usuario);
		}
		$this->session->set_flashdata('error','Usuario eliminado con exito.');
		redirect('/administrador/usuarios');
		
	}
	
	function fichausuario($idt_usrio){
		
		$this->load->helper(array('url','form'));		
		$this->load->model(array('Tz_permisos','Ta_usrio'));
		if($this->Tz_permisos->get_permiso_modulo(11) != TRUE){
			redirect('/accesodenegado');
		}
		
		if(!is_numeric($idt_usrio)){
			redirect('/moduloincorrecto');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('rut','rut','trim|required');
		$this->form_validation->set_rules('datos[1]','nombres','trim|required');
		$this->form_validation->set_rules('datos[297]','apellido paterno','trim|required');
		$this->form_validation->set_rules('datos[298]','apellido materno','trim|required');
		$this->form_validation->set_rules('datos[83]','correo','trim|required|valid_email');
		$this->form_validation->set_rules('datos[81]','telefono fijo','trim|required');
		$this->form_validation->set_rules('datos[118]','telefono celular','trim|required');
		$this->form_validation->set_rules('datos[16]','password','trim|required|min_length[4]');
		$this->form_validation->set_error_delimiters('<li>','</li>');
		
		if($this->form_validation->run() == TRUE){
			
			$data['error_rut'] = $this->Ta_usrio->update_rut($this->input->post('rut'),$idt_usrio,$this->session->userdata('id_usuario'));
			
			foreach($this->input->post('datos') as $key => $value){
				if($key == 83){
					$value = strtolower($value);	
				}
				$this->Ta_usrio->update_metadata($idt_usrio,$key,$value);
				$data['message'] = 'Registros grabados con exito.';
			}
			
		}		
		
		$data['usuario'] = $this->Ta_usrio->get_usrionombres_porid($idt_usrio);
		$data['idt_usrio'] = $idt_usrio;
		$data['page_title'] = $this->config->item('page_title').'Ficha usuario SG';	
		$this->load->view('administradores/fichausuario',$data);
		
	}
	
	function usuariossg(){
		$this->load->helper(array('url','form'));		
		$this->load->model(array('Tz_permisos','Ta_usrio'));
		if($this->Tz_permisos->get_permiso_modulo(11) != TRUE){
			redirect('/accesodenegado');
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('rut','rut','trim|required');
		$this->form_validation->set_rules('nombres','nombres','trim');
		$this->form_validation->set_rules('apellidos','apellidos','trim');
		
		if($this->form_validation->run() == TRUE){
			
			$rut = trim($this->input->post('rut'));
			$nombres = trim($this->input->post('nombres'));
			$apellidos = trim($this->input->post('apellidos'));
			
			if($this->input->server('REQUEST_METHOD') == 'POST'){
				if(!$rut AND !$nombres AND !$apellidos){
					$data['error'] = 'Debe indicar o un rut y/o nombres y/o apellidos.';	
				}else{
					$data['usuarios'] = $this->Ta_usrio->buscar_usuario($rut,$nombres,$apellidos);
				}
			}
		}
		
		$data['menuusuario'] = self::menuusuario();
		$data['page_title'] = $this->config->item('page_title').'Usuarios SG';	
		$this->load->view('administradores/usuariossg',$data);
		
	}
	
	function usuarios(){
		
		$this->load->helper(array('url','form'));		
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(2) != TRUE){
			redirect('/accesodenegado');
		}
		
		$data['menuusuario'] = self::menuusuario();
		$data['usuarios'] = $this->Tz_permisos->get_usuarios_sistema();
		$data['page_title'] = $this->config->item('page_title').'Administrador de usuarios';		
		$this->load->view('administradores/usuarios',$data);
		
	}
	
	function pagos(){
		
		$this->load->helper(array('url','form'));		
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(10) != TRUE){
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
				$data['actividades'] = $this->Tz_postulacionitem->get_todas_actividades($data['id_usrio']);
			
			}
			
		}		
		
		$data['menuusuario'] = self::menuusuario();
		$data['page_title'] = $this->config->item('page_title').'Administrador pagos';
		$this->load->view('administradores/pagos',$data);
	}
	
	public function eliminarpago($tipo_pago,$idpago,$idpostulacionitem){			

		$this->load->helper(array('url'));
		$this->load->model('Tz_postulacionitem_tipodepago');
		$this->Tz_postulacionitem_tipodepago->eliminar_pago($tipo_pago,$idpago,FALSE,base64_decode($idpostulacionitem));
		redirect('/administrador/cancelar/'.$idpostulacionitem);
		
	}
	
	function cancelar($idpostulacionitem = NULL){
		
		$this->load->helper(array('url','form'));		
		$this->load->model('Tz_permisos');
		if($this->Tz_permisos->get_permiso_modulo(10) != TRUE){
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
							
				if($valor['bEstado'] != 3 AND $valor['bEstado'] != 12 ){
					//$data['flash_error'] .= '<li>La actividad '.$valor['Nombre_Curso'].' no puede ser reprocesada, favor verifique su estado</li>';					
					$c++;	
				}else{
					//$a_pagar_new[] = $valor['IDPostulacionItem'];	
				}
			}
			
			if($data['flash_error']){
				$data['flash_error'] = '<div style="text-align:left">Tiene los siguientes errores :<br /><br /><ul>'.$data['flash_error'].'</ul></div>';	
			}				
			
			//unset($a_pagar);
			//$a_pagar = $a_pagar_new;
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
		$this->load->view('administradores/cancelando',$data);
		
	}
	
	function permisos($id_usuario){
		
		$this->load->helper(array('url','form'));
		$this->load->model(array('Tz_permisos','Ta_usrio'));
		
		if(!is_numeric($id_usuario)){
			redirect('/moduloincorrecto');
		}
		
		$post = $this->input->post();			
		foreach($post as $key => $respuesta){				
			$this->Tz_permisos->update_permisos($key,$id_usuario,$respuesta);				
		}
		
		if($this->Tz_permisos->get_permiso_modulo(2) == TRUE){				
			
			$data['usuario'] = $this->Ta_usrio->get_usrionombres_porid($id_usuario);
			$padres = $this->Tz_permisos->get_modulos_up($id_usuario);
			$c = 0 ;
			foreach($padres as $value){
				
				$modulos_hijo = $this->Tz_permisos->get_modulos_subup($value['id_modulo'],$id_usuario);				
				$data['modulos'][$c]['id_usuario'] = $value['id_usuario'];
				$data['modulos'][$c]['nombre'] = $value['nombre'];
				$data['modulos'][$c]['id_modulo'] = $value['id_modulo'];
				$data['modulos'][$c]['sino'] = $value['sino'];
				$data['modulos'][$c]['hijos'] = $modulos_hijo;
				$c++;		
			}
			
			$data['otros_modulos'] = $padres = $this->Tz_permisos->get_modulos_otros($id_usuario);
			$data['id_usuario'] = $id_usuario;
			$data['page_title'] = $this->config->item('page_title').'Permisos';
			$this->load->view('administradores/permisos',$data);
			
		}else{
			
			$data['page_title'] = $this->config->item('page_title').'Acceso denegado';
			$this->load->view('accesodenegado',$data);
			
		}
		
	}
	
}