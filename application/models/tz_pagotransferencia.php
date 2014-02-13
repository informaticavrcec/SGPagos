<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pagotransferencia extends CI_Model {
	
	function get_pagos_transferencia($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  dbo.tz_PagoTransferencia.IDPagoTransferencia,
		  dbo.tz_PagoTransferencia.IDPostulacionItem,
		  dbo.tz_PagoTransferencia.NumeroTransferencia,
		  dbo.tz_PagoTransferencia.Monto,
		  dbo.tz_PagoTransferencia.IDBanco,
		  dbo.tz_PagoTransferencia.IDModificador,
		  dbo.tz_PagoTransferencia.FechaModificacion,
		  dbo.tz_PagoTransferencia.IDTipoDocumento,
		  dbo.tz_PagoTransferencia.CantPagos,
		  dbo.tz_PagoTransferencia.NroPago,
		  dbo.tz_PagoTransferencia.IDEmpresa,
		  dbo.tz_PagoTransferencia.NOC_Empresa
		FROM
		  dbo.tz_PagoTransferencia
		WHERE
		  dbo.tz_PagoTransferencia.IDPostulacionItem = $idpostulacionitem ");
		return $query->result_array();		
		
	}
	
	function update_pago_transferencia($idpostulacionitem,$monto,$tipo_documento,
	$numero_transferencia,$cantidad_pago,$numero_pago,$banco,$id_pago = NULL){
		
		$id_usuario = $this->session->userdata('id_usuario');
	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 3862");
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
			  IDTipoPago = 3862");
			
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
			  3862,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 
			  dbo.tz_PagoTransferencia.IDPostulacionItem,
			  dbo.tz_PagoTransferencia.IDPagoTransferencia
			FROM
			  dbo.tz_PagoTransferencia
			WHERE
			  dbo.tz_PagoTransferencia.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_PagoTransferencia.IDPagoTransferencia = $id_pago ");			
			
			if(count($query->result_array()) > 0){
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_PagoTransferencia
				SET
				  NumeroTransferencia = ".$this->db->escape($numero_transferencia).",
				  Monto = $monto,
				  IDBanco = $banco,
				  IDModificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  IDTipoDocumento = $tipo_documento,
				  CantPagos = $cantidad_pago,
				  NroPago = $numero_pago,
				  IDEmpresa = NULL,
				  NOC_Empresa = NULL
				WHERE
				  dbo.tz_PagoTransferencia.IDPostulacionItem = $idpostulacionitem AND 
				  dbo.tz_PagoTransferencia.IDPagoTransferencia = $id_pago ");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_PagoTransferencia(
				  NumeroTransferencia,
				  Monto,
				  IDBanco,
				  IDModificador,
				  FechaModificacion,
				  IDTipoDocumento,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa,
				  IDPostulacionItem)
				VALUES(
				  ".$this->db->escape($numero_transferencia).",
				  $monto,
				  $banco,
				  $id_usuario,
				  GETDATE(),
				  $tipo_documento,
				  $cantidad_pago,
				  $numero_pago,
				  NULL,
				  NULL,
				  $idpostulacionitem)");	
				
			}
		}else{
			
			$sql = "INSERT INTO
			  dbo.tz_PagoTransferencia(
			  NumeroTransferencia,
			  Monto,
			  IDBanco,
			  IDModificador,
			  FechaModificacion,
			  IDTipoDocumento,
			  CantPagos,
			  NroPago,
			  IDEmpresa,
			  NOC_Empresa,
			  IDPostulacionItem)
			VALUES(
			  ".$this->db->escape($numero_transferencia).",
			  $monto,
			  $banco,
			  $id_usuario,
			  GETDATE(),
			  $tipo_documento,
			  $cantidad_pago,
			  $numero_pago,
			  NULL,
			  NULL,
			  $idpostulacionitem)";			
			$resultado = $this->db->query($sql);
							
		}			
		return $resultado;			
	}	
}