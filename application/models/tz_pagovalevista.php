<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pagovalevista extends CI_Model {
	
	function get_pagos_valevista($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  dbo.tz_PagoValeVista.IDPagoValeVista,
		  dbo.tz_PagoValeVista.IDPostulacionItem,
		  dbo.tz_PagoValeVista.NumeroValeVista,
		  dbo.tz_PagoValeVista.Monto,
		  CONVERT(CHAR(10),dbo.tz_PagoValeVista.FechaValeVista,103) AS FechaValeVista,
		  dbo.tz_PagoValeVista.IDBanco,
		  dbo.tz_PagoValeVista.IDModificador,
		  dbo.tz_PagoValeVista.FechaModificacion,
		  dbo.tz_PagoValeVista.IDTipoDocumento,
		  dbo.tz_PagoValeVista.CantPagos,
		  dbo.tz_PagoValeVista.NroPago,
		  dbo.tz_PagoValeVista.IDEmpresa,
		  dbo.tz_PagoValeVista.NOC_Empresa
		FROM
		  dbo.tz_PagoValeVista
		WHERE
		  dbo.tz_PagoValeVista.IDPostulacionItem = $idpostulacionitem ");
		return $query->result_array();		
		
	}
	
	function update_pago_valevista($idpostulacionitem,$monto,$tipo_documento,
	$numero_valevista,$cantidad_pago,$numero_pago,$banco,$id_pago = NULL,$fecha){
		
		$id_usuario = $this->session->userdata('id_usuario');
	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 3861");
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
			  IDTipoPago = 3861");
			
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
			  3861,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 
			  dbo.tz_PagoValeVista.IDPostulacionItem,
			  dbo.tz_PagoValeVista.IDPagoValeVista
			FROM
			  dbo.tz_PagoValeVista
			WHERE
			  dbo.tz_PagoValeVista.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_PagoValeVista.IDPagoValeVista = $id_pago ");			
			
			if(count($query->result_array()) > 0){
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_PagoValeVista
				SET				  
				  NumeroValeVista = ".$this->db->escape($numero_valevista).",
				  Monto = $monto,
				  FechaValeVista = ".$this->db->escape($fecha).",
				  IDBanco = $banco,
				  IDModificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  IDTipoDocumento = $tipo_documento,
				  CantPagos = $cantidad_pago,
				  NroPago = $numero_pago,
				  IDEmpresa = NULL,
				  NOC_Empresa = NULL
				WHERE
				  dbo.tz_PagoValeVista.IDPostulacionItem = $idpostulacionitem AND 
				  dbo.tz_PagoValeVista.IDPagoValeVista = $id_pago ");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_PagoValeVista(
				  NumeroValeVista,
				  Monto,
				  FechaValeVista,
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
				  ".$this->db->escape($numero_valevista).",
				  $monto,
				  ".$this->db->escape($fecha).",
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
			  dbo.tz_PagoValeVista(
			  NumeroValeVista,
			  Monto,
			  FechaValeVista,
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
			  ".$this->db->escape($numero_valevista).",
			  $monto,
			  ".$this->db->escape($fecha).",
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