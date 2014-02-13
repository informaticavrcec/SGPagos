<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pagodeposito extends CI_Model {
	
	function get_pagos_deposito($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  dbo.tz_PagoDeposito.IDPagoDeposito,
		  dbo.tz_PagoDeposito.IDPostulacionItem,
		  dbo.tz_PagoDeposito.NumeroComprobante,
		  dbo.tz_PagoDeposito.Monto,
		  CONVERT(CHAR(10),dbo.tz_PagoDeposito.FechaDeposito,103) AS FechaDeposito,
		  dbo.tz_PagoDeposito.IDModificador,
		  dbo.tz_PagoDeposito.FechaModificacion,
		  dbo.tz_PagoDeposito.IDTipoDocumento,
		  dbo.tz_PagoDeposito.CantPagos,
		  dbo.tz_PagoDeposito.NroPago,
		  dbo.tz_PagoDeposito.IDEmpresa,
		  dbo.tz_PagoDeposito.NOC_Empresa
		FROM
		  dbo.tz_PagoDeposito
		WHERE
		  dbo.tz_PagoDeposito.IDPostulacionItem = $idpostulacionitem ");
		return $query->result_array();		
		
	}
	
	function update_pago_deposito($idpostulacionitem,$monto,$tipo_documento,$numero_comprobante,$fecha,$cantidad_pago,$numero_pago,$id_pago = NULL){
		
		$id_usuario = $this->session->userdata('id_usuario');
	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 3860");
		$resultado = count($query->result_array());	
		
		if($resultado > 0){
			
			$query = $this->db->query("UPDATE 
			  dbo.tz_PostulacionItem_TipoPago 
			SET			 						  
			  IDModificador = $id_usuario,
			  FechaModificacion = GETDATE(),
			  IDEmpresa = NULL,
			  IDOtic = NULL
			WHERE
			  IDPostulacionItem = $idpostulacionitem AND 
			  IDTipoPago = 3860");
			
		}else{	
			
			$query = $this->db->query("INSERT INTO
			  dbo.tz_PostulacionItem_TipoPago(
			  IDPostulacionItem,
			  IDTipoPago,
			  IDCreador,
			  FechaCreacion,
			  IDEmpresa,
			  IDOtic)
			VALUES(
			  $idpostulacionitem,
			  3860,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 
			  dbo.tz_PagoDeposito.IDPagoDeposito,
			  dbo.tz_PagoDeposito.IDPostulacionItem
			FROM
			  dbo.tz_PagoDeposito
			WHERE
			  dbo.tz_PagoDeposito.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_PagoDeposito.IDPagoDeposito = $id_pago ");			
			
			if(count($query->result_array()) > 0){
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_PagoDeposito
				SET				
				  NumeroComprobante = ".$this->db->escape($numero_comprobante).",
				  Monto = $monto,
				  FechaDeposito = ".$this->db->escape($fecha).",
				  IDModificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  IDTipoDocumento = $tipo_documento,
				  CantPagos = $cantidad_pago,
				  NroPago = $numero_pago,
				  IDEmpresa = NULL,
				  NOC_Empresa = NULL
				WHERE
				  dbo.tz_PagoDeposito.IDPagoDeposito = $id_pago AND
				  dbo.tz_PagoDeposito.IDPostulacionItem = $idpostulacionitem ");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_PagoDeposito(
				  IDPostulacionItem,
				  NumeroComprobante,
				  Monto,
				  FechaDeposito,
				  IDModificador,
				  FechaModificacion,
				  IDTipoDocumento,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa)
				VALUES(
				  $idpostulacionitem,
				  ".$this->db->escape($numero_comprobante).",
				  $monto,
				  ".$this->db->escape($fecha).",
				  $id_usuario,
				  GETDATE(),
				  $tipo_documento,
				  $cantidad_pago,
				  $numero_pago,
				  NULL,
				  NULL)");	
				
			}
		}else{
			
			$sql = "INSERT INTO
			  dbo.tz_PagoDeposito(
			  IDPostulacionItem,
			  NumeroComprobante,
			  Monto,
			  FechaDeposito,
			  IDModificador,
			  FechaModificacion,
			  IDTipoDocumento,
			  CantPagos,
			  NroPago,
			  IDEmpresa,
			  NOC_Empresa)
			VALUES(
			  $idpostulacionitem,
			  ".$this->db->escape($numero_comprobante).",
			  $monto,
			  ".$this->db->escape($fecha).",
			  $id_usuario,
			  GETDATE(),
			  $tipo_documento,
			  $cantidad_pago,
			  $numero_pago,
			  NULL,
			  NULL)";			
			$resultado = $this->db->query($sql);
							
		}			
		return $resultado;			
	}	
}