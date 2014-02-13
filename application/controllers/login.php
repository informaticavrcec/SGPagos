<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {	

	function index(){
		
		$this->load->helper(array('url','form'));
		$this->load->library(array('form_validation'));
		
		
		$this->form_validation->set_rules('usuario','usuario','trim|required');
		$this->form_validation->set_rules('password','password','trim|required');
		
		if($this->form_validation->run() == TRUE){
			$this->load->model('Tz_permisos');
			$respuesta = $this->Tz_permisos->get_credenciales($this->input->post('usuario'),$this->input->post('password'));
			if($respuesta != FALSE){
				$datos = array(
					'rut' => $respuesta->rut,
					'id_usuario' => $respuesta->id_usuario,
					'nombres'=> $respuesta->nombres,
					'apellidos' => $respuesta->apellidos,
					'logged_in' => TRUE 
				);
				if($this->input->post('password') === $respuesta->pass){
					
					if($this->Tz_permisos->get_tiene_acceso($respuesta->id_usuario) == TRUE){
						$this->session->set_userdata($datos);
						redirect('/menu');
					}else{
						$data['error'] = 'Ud no tiene permisos para ingresar.';
					}
				}else{
					$data['error'] = 'Los datos no corresponden y/o verifique bloqueo de mayusculas.';
				}
			}else{
				$data['error'] = 'UD. no tiene acceso y/o los datos ingresados no corresponden.';
			}
		}
		
		$data['page_title'] = $this->config->item('page_title').'Login';
		$this->load->view('login',$data);
		
	}
	
	



}




