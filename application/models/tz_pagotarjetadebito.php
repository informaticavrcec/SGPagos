<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pagotarjetadebito extends CI_Model {
	
	function get_pagos_tarjetadebito($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  dbo.tz_PagoDebito.IDPagoDebito,
		  dbo.tz_PagoDebito.IDPostulacionItem,
		  dbo.tz_PagoDebito.BancoEmisor,
		  dbo.tz_PagoDebito.Monto,
		  CONVERT(CHAR(10),dbo.tz_PagoDebito.FechaTransaccion,103) AS FechaTransaccion,
		  dbo.tz_PagoDebito.CodigoTransaccion,
		  dbo.tz_PagoDebito.Cuatroultimosdigitos,
		  dbo.tz_PagoDebito.IDMOdificador,
		  dbo.tz_PagoDebito.FechaModificacion,
		  dbo.tz_PagoDebito.IDTipoDocumento,
		  dbo.tz_PagoDebito.CantPagos,
		  dbo.tz_PagoDebito.NroPago,
		  dbo.tz_PagoDebito.IDEmpresa,
		  dbo.tz_PagoDebito.NOC_Empresa
		FROM
		  dbo.tz_PagoDebito
		WHERE
		  dbo.tz_PagoDebito.IDPostulacionItem = $idpostulacionitem ");
		return $query->result_array();		
		
	}
	
	function update_pago_tarjetadebito($idpostulacionitem,$banco,$monto,$fecha,$codigo,$ultimos,$tipo_docto,$cant_pag,$nro_pago,$id_pago = NULL){
		
		$id_usuario = $this->session->userdata('id_usuario');
	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 3805");
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
			  IDTipoPago = 3805");
			
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
			  3805,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 
			  dbo.tz_PagoDebito.IDPagoDebito
			FROM
			  dbo.tz_PagoDebito
			WHERE
			  dbo.tz_PagoDebito.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_PagoDebito.IDPagoDebito = $id_pago ");			
			
			if(count($query->result_array()) > 0){
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_PagoDebito
				SET
				  BancoEmisor = $banco,
				  Monto = $monto,
				  FechaTransaccion = ".$this->db->escape($fecha).",
				  CodigoTransaccion = ".$this->db->escape($codigo).",
				  Cuatroultimosdigitos = ".$this->db->escape($ultimos).",
				  IDMOdificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  IDTipoDocumento = $tipo_docto,
				  CantPagos = $cant_pag,
				  NroPago = $nro_pago,
				  IDEmpresa = NULL,
				  NOC_Empresa = NULL
				WHERE
				  dbo.tz_PagoDebito.IDPostulacionItem = $idpostulacionitem AND 
				  dbo.tz_PagoDebito.IDPagoDebito = $id_pago ");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_PagoDebito(
				  IDPostulacionItem,
				  BancoEmisor,
				  Monto,
				  FechaTransaccion,
				  CodigoTransaccion,
				  Cuatroultimosdigitos,
				  IDMOdificador,
				  FechaModificacion,
				  IDTipoDocumento,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa)
				VALUES(
				  $idpostulacionitem,
				  $banco,
				  $monto,
				  ".$this->db->escape($fecha).",
				  ".$this->db->escape($codigo).",
				  ".$this->db->escape($ultimos).",
				  $id_usuario,
				  GETDATE(),
				  $tipo_docto,
				  $cant_pag,
				  $nro_pago,
				  NULL,
				  NULL)");	
				
			}
		}else{
			
			$sql = "INSERT INTO
			  dbo.tz_PagoDebito(
			  IDPostulacionItem,
			  BancoEmisor,
			  Monto,
			  FechaTransaccion,
			  CodigoTransaccion,
			  Cuatroultimosdigitos,
			  IDMOdificador,
			  FechaModificacion,
			  IDTipoDocumento,
			  CantPagos,
			  NroPago,
			  IDEmpresa,
			  NOC_Empresa)
			VALUES(
			  $idpostulacionitem,
			  $banco,
			  $monto,
			  ".$this->db->escape($fecha).",
			  ".$this->db->escape($codigo).",
			  ".$this->db->escape($ultimos).",
			  $id_usuario,
			  GETDATE(),
			  $tipo_docto,
			  $cant_pag,
			  $nro_pago,
			  NULL,
			  NULL)";			
			$resultado = $this->db->query($sql);
							
		}			
		return $resultado;			
	}	
}