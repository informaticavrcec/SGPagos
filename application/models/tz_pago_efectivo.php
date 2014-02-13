<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pago_efectivo extends CI_Model {
	
	
	
	function update_pago_efectivo($idpostulacionitem,$monto,$id_usuario,$tipo_documento){
		
		$monto = ereg_replace("[^A-Za-z0-9]", "", $monto);
				
		$query = $this->db->query("SELECT 
		  z.IDPagoEfectivo
		FROM
		  dbo.tz_PagoEfectivo z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem ");
			
		if($query->num_rows() > 0){		
			
			$resultado = $this->db->query("UPDATE 
			  dbo.tz_PagoEfectivo
			SET			 
			  Monto = $monto,
			  IDTipoDocumento = $tipo_documento,
			  IDModificador = $id_usuario,
			  IDFechaModificacion = GETDATE(),
			  CantPagos = 1,
			  NroPago = 1,
			  IDEmpresa = NULL,
			  NOC_Empresa = NULL
			WHERE
			  IDPostulacionItem = $idpostulacionitem ");	
		}else{
						
			$resultado = $this->db->query("INSERT INTO
			  dbo.tz_PostulacionItem_TipoPago(
			  IDPostulacionItem,
			  IDTipoPago,
			  IDCreador,
			  FechaCreacion, 			
			  IDEmpresa,
			  IDOtic)
			VALUES(
			  $idpostulacionitem,
			  1438,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			if($resultado > 0){
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_PagoEfectivo(
				  IDPostulacionItem,
				  Monto,
				  IDTipoDocumento,
				  IDModificador,
				  IDFechaModificacion,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa)
				VALUES(
				  $idpostulacionitem,
				  $monto,
				  $tipo_documento,
				  $id_usuario,
				  GETDATE(),
				  1,
				  1,
				  NULL,
				  NULL)");					
			}
			
		}
		
		return $resultado;	
		
	}	
	
}