<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_reservas extends CI_Model {
	
	function put_comentario($comentario,$idpopstulacionitem,$id_usuario){
		
		if($comentario AND $idpopstulacionitem AND $id_usuario){			
		
			$data = array(
				'idpostulacionitem' => $idpopstulacionitem,
				'observacion' => $comentario,
				'id_usuario' => $id_usuario ,
				'fecha_creacion' => date('d-m-Y H:i:s'));
			$query = $this->db->insert('tz_reservas',$data);
		}
		
	}
	
	
	
}