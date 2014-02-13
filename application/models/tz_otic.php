<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_otic extends CI_Model {
	
	function get_otics(){
		$query = $this->db->query("SELECT 
		  dbo.tz_Otic.IDOtic,
		  dbo.tz_Otic.Nombre,
		  dbo.tz_Otic.Descripcion,
		  dbo.tz_Otic.Contacto,
		  dbo.tz_Otic.Email,
		  dbo.tz_Otic.Telefono,
		  dbo.tz_Otic.Direccion,
		  dbo.tz_Otic.RUT,
		  dbo.tz_Otic.Giro
		FROM
		  dbo.tz_Otic
		ORDER BY
		  dbo.tz_Otic.Nombre");
		return $query->result_array();	
	}
	
}