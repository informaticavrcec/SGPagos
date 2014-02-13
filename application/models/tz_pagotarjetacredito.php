<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pagotarjetacredito extends CI_Model {
	
	function get_pagos_tarjetacredito($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  dbo.tz_PagoTarjeta.IDPagoTarjeta,
		  dbo.tz_PagoTarjeta.IDPostulacionItem,
		  dbo.tz_PagoTarjeta.TipoPagoTarjeta,
		  dbo.tz_PagoTarjeta.IDTarjeta,
		  dbo.tz_PagoTarjeta.Banco,
		  dbo.tz_PagoTarjeta.NumTarjeta1,
		  dbo.tz_PagoTarjeta.NumTarjeta2,
		  dbo.tz_PagoTarjeta.NumTarjeta3,
		  dbo.tz_PagoTarjeta.NumTarjeta4,
		  dbo.tz_PagoTarjeta.NumTarjeta,
		  dbo.tz_PagoTarjeta.VencimientoMM,
		  dbo.tz_PagoTarjeta.VencimientoAA,
		  dbo.tz_PagoTarjeta.Vencimiento,
		  dbo.tz_PagoTarjeta.Monto,
		  dbo.tz_PagoTarjeta.NumTransaccion,
		  dbo.tz_PagoTarjeta.IDTipoDocumento,
		  dbo.tz_PagoTarjeta.IDModificador,
		  dbo.tz_PagoTarjeta.FechaModificacion,
		  dbo.tz_PagoTarjeta.CantPagos,
		  dbo.tz_PagoTarjeta.NroPago,
		  dbo.tz_PagoTarjeta.IDEmpresa,
		  dbo.tz_PagoTarjeta.NOC_Empresa
		FROM
		  dbo.tz_PagoTarjeta
		WHERE
		  dbo.tz_PagoTarjeta.IDPostulacionItem = $idpostulacionitem ");
		return $query->result_array();		
		
	}
	
	function update_pago_tarjetacredito($idpostulacionitem,$id_tarjeta,$banco,$uno,$dos,$tres,$cuatro,$mes,
	$anio,$monto,$num_trans,$tipo_docto,$cant_pag,$num_pago,$id_pago = NULL){
		
		$id_usuario = $this->session->userdata('id_usuario');
	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 1441");
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
			  IDTipoPago = 1441");
			
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
			  1441,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 
			  dbo.tz_PagoTarjeta.IDPagoTarjeta,
			  dbo.tz_PagoTarjeta.IDPostulacionItem
			FROM
			  dbo.tz_PagoTarjeta
			WHERE
			  dbo.tz_PagoTarjeta.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_PagoTarjeta.IDPagoTarjeta = $id_pago");			
			
			if(count($query->result_array()) > 0){
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_PagoTarjeta
				SET
				  TipoPagoTarjeta = 1,
				  IDTarjeta = $id_tarjeta,
				  Banco = $banco,
				  NumTarjeta1 = ".$this->db->escape($uno).",
				  NumTarjeta2 = ".$this->db->escape($dos).",
				  NumTarjeta3 = ".$this->db->escape($tres).",
				  NumTarjeta4 = ".$this->db->escape($cuatro).",
				  NumTarjeta = NULL,
				  VencimientoMM = ".$this->db->escape($mes).",
				  VencimientoAA = ".$this->db->escape($anio).",
				  Vencimiento = NULL,
				  Monto = $monto,
				  NumTransaccion = ".$this->db->escape($num_trans).",
				  IDTipoDocumento = $tipo_docto,
				  IDModificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  CantPagos = $cant_pag,
				  NroPago = $num_pago,
				  IDEmpresa = NULL,
				  NOC_Empresa = NULL
				WHERE
				  dbo.tz_PagoTarjeta.IDPostulacionItem = $idpostulacionitem AND 
				  dbo.tz_PagoTarjeta.IDPagoTarjeta = $id_pago ");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_PagoTarjeta(
				  IDPostulacionItem,
				  TipoPagoTarjeta,
				  IDTarjeta,
				  Banco,
				  NumTarjeta1,
				  NumTarjeta2,
				  NumTarjeta3,
				  NumTarjeta4,
				  NumTarjeta,
				  VencimientoMM,
				  VencimientoAA,
				  Vencimiento,
				  Monto,
				  NumTransaccion,
				  IDTipoDocumento,
				  IDModificador,
				  FechaModificacion,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa)
				VALUES(
				  $idpostulacionitem,
				  1,
				  $id_tarjeta,
				  $banco,
				  ".$this->db->escape($uno).",
				  ".$this->db->escape($dos).",
				  ".$this->db->escape($tres).",
				  ".$this->db->escape($cuatro).",
				  NULL,
				  ".$this->db->escape($mes).",
				  ".$this->db->escape($anio).",
				  NULL,
				  $monto,
				  ".$this->db->escape($num_trans).",
				  $tipo_docto,
				  $id_usuario,
				  GETDATE(),
				  $cant_pag,
				  $num_pago,
				  NULL,
				  NULL)");	
				
			}
		}else{
			
			$sql = "INSERT INTO
				  dbo.tz_PagoTarjeta(
				  IDPostulacionItem,
				  TipoPagoTarjeta,
				  IDTarjeta,
				  Banco,
				  NumTarjeta1,
				  NumTarjeta2,
				  NumTarjeta3,
				  NumTarjeta4,
				  NumTarjeta,
				  VencimientoMM,
				  VencimientoAA,
				  Vencimiento,
				  Monto,
				  NumTransaccion,
				  IDTipoDocumento,
				  IDModificador,
				  FechaModificacion,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa)
				VALUES(
				  $idpostulacionitem,
				  1,
				  $id_tarjeta,
				  $banco,
				  ".$this->db->escape($uno).",
				  ".$this->db->escape($dos).",
				  ".$this->db->escape($tres).",
				  ".$this->db->escape($cuatro).",
				  NULL,
				  ".$this->db->escape($mes).",
				  ".$this->db->escape($anio).",
				  NULL,
				  $monto,
				  ".$this->db->escape($num_trans).",
				  $tipo_docto,
				  $id_usuario,
				  GETDATE(),
				  $cant_pag,
				  $num_pago,
				  NULL,
				  NULL)";			
			$resultado = $this->db->query($sql);
							
		}			
		return $resultado;			
	}	
}