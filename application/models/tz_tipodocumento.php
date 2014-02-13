<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_tipodocumento extends CI_Model {
	
	function get_tipo_documentos(){
		$query = $this->db->query("");
		return $query->result_array();	
	}
	
	
}