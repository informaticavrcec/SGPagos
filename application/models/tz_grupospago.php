<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_grupospago extends CI_Model {
	
	function get_grupo($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  dbo.tz_GruposPagos.IDGrupo,
		  dbo.tz_PostulacionItem.IDSeccion,
		  dbo.tz_GruposPagos.IdPostulacionItem
		FROM
		  dbo.tz_GruposPagos
		  INNER JOIN dbo.tz_PostulacionItem ON (dbo.tz_GruposPagos.IdPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem)
		WHERE
		  dbo.tz_GruposPagos.IDGrupo IN (SELECT dbo.tz_GruposPagos.IDGrupo FROM dbo.tz_GruposPagos WHERE dbo.tz_GruposPagos.IdPostulacionItem = $idpostulacionitem )");
		return $query->result_array();	
	}
	
}