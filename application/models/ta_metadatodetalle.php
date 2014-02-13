<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_metadatodetalle extends CI_Model {
	
	function get_tarjetas(){
		
		$query = $this->db->query("SELECT 
		  dbo.ta_MetadatoDetalle.IDMetadatoDetalle,
		  dbo.ta_MetadatoDetalle.nmbre
		FROM
		  dbo.ta_MetadatoDetalle
		WHERE
		  dbo.ta_MetadatoDetalle.IDMetadato = 145
		ORDER BY
		  dbo.ta_MetadatoDetalle.nmbre");
		return $query->result_array();	
	}
	
	public function get_tipos_pago(){
		
		$query = $this->db->query("SELECT 		
		  dbo.ta_MetadatoDetalle.nmbre,
		  dbo.ta_MetadatoDetalle.IDMetadatoDetalle
		FROM
		  dbo.ta_MetadatoDetalle
		WHERE
		  dbo.ta_MetadatoDetalle.IDMetadato = 143 AND
		  dbo.ta_MetadatoDetalle.IDMetadatoDetalle NOT IN(3807,3987,3863,3864)
		ORDER BY
		  dbo.ta_MetadatoDetalle.nmbre");
		return $query->result_array(); 	
		
	}
	
	function get_bancos(){
		$query = $this->db->query("SELECT 
		  dbo.ta_MetadatoDetalle.IDMetadatoDetalle,
		  dbo.ta_MetadatoDetalle.dscrn,
		  dbo.ta_MetadatoDetalle.IDMetadato
		FROM
		  dbo.ta_MetadatoDetalle
		WHERE
		  dbo.ta_MetadatoDetalle.IDMetadato = 146 AND 
		  dbo.ta_MetadatoDetalle.dscrn <> ''
		ORDER BY
		  dbo.ta_MetadatoDetalle.dscrn ");
		return $query->result_array();	
	}
}