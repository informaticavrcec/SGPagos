<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_empresasregistradas extends CI_Model {
	
	function get_empresas(){		
		
		$query = $this->db->query("SELECT 
		  z.IDEmpRegistrada,
		  z.Nombre,
		  z.Descripcion,
		  z.RSocial,
		  z.RUT,
		  z.Direccion,
		  z.Comuna,
		  z.Pais,
		  z.Contacto,
		  z.Cargo,
		  z.email,
		  z.Giro,
		  z.Fono1,
		  z.Fono2,
		  z.Fax,
		  z.DireccionEnvioFactura,
		  z.idEjecutivo,
		  z.Region,
		  z.matriculas,
		  z.observaciones,
		  z.ticket,
		  z.ContactoFacturacion,
		  z.CorreoContactoFact,
		  z.FonoContactoFact,
		  z.publica,
		  z.fechacreacion,
		  z.fechamodificacion,
		  z.idconvenio,
		  z.num_empleados,
		  z.web,
		  z.change,
		  z.creador,
		  z.modificador,
		  z.contacto_cobranza,
		  z.correo_cobranza,
		  z.direccion_cobranza,
		  z.fono_cobranza
		FROM
		  dbo.tz_EmpresaRegistrada z
		ORDER BY
		  z.RSocial");		
		return $query->result_array();
		 	
	}
	
}