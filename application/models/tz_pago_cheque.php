<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pago_cheque extends CI_Model {
	
	function get_pagos_cheque_dia($idpostulacionitem){
			
		$query = $this->db->query("SELECT 
		  dbo.tz_PagoCheque.IDPagoCheque,
		  dbo.tz_PagoCheque.IDPostulacionItem,
		  dbo.tz_PagoCheque.TipoPagoCheque,
		  dbo.tz_PagoCheque.Banco,
		  dbo.tz_PagoCheque.CuentaCorriente,
		  dbo.tz_PagoCheque.NSerie,
		  dbo.tz_PagoCheque.Monto,
		  CONVERT(CHAR(10),dbo.tz_PagoCheque.Fecha,103) AS Fecha,
		  dbo.tz_PagoCheque.NumCheques,
		  dbo.tz_PagoCheque.IDTipoDocumento,
		  dbo.tz_PagoCheque.nCheque,
		  dbo.tz_PagoCheque.IDModificador,
		  dbo.tz_PagoCheque.FechaModificacion,
		  dbo.tz_PagoCheque.CantPagos,
		  dbo.tz_PagoCheque.NroPago,
		  dbo.tz_PagoCheque.IDEmpresa,
		  dbo.tz_PagoCheque.NOC_Empresa,
		  dbo.tz_PagoCheque.rut_girador,
		  dbo.tz_PagoCheque.Nombre_Girador,
		  dbo.tz_PagoCheque.email_girador,
		  dbo.tz_PagoCheque.telefono_girador
		FROM
		  dbo.tz_PagoCheque
		WHERE
		  dbo.tz_PagoCheque.IDPostulacionItem IN ($idpostulacionitem) AND
		  dbo.tz_PagoCheque.TipoPagoCheque = 1");	  

		return $query->result_array();
		
	}
	
	function get_pagos_cheque_fecha($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  dbo.tz_PagoCheque.IDPagoCheque,
		  dbo.tz_PagoCheque.IDPostulacionItem,
		  dbo.tz_PagoCheque.TipoPagoCheque,
		  dbo.tz_PagoCheque.Banco,
		  dbo.tz_PagoCheque.CuentaCorriente,
		  dbo.tz_PagoCheque.NSerie,
		  dbo.tz_PagoCheque.Monto,
		  CONVERT(CHAR(10),dbo.tz_PagoCheque.Fecha,103) AS Fecha,
		  dbo.tz_PagoCheque.NumCheques,
		  dbo.tz_PagoCheque.IDTipoDocumento,
		  dbo.tz_PagoCheque.nCheque,
		  dbo.tz_PagoCheque.IDModificador,
		  dbo.tz_PagoCheque.FechaModificacion,
		  dbo.tz_PagoCheque.CantPagos,
		  dbo.tz_PagoCheque.NroPago,
		  dbo.tz_PagoCheque.IDEmpresa,
		  dbo.tz_PagoCheque.NOC_Empresa,
		  dbo.tz_PagoCheque.rut_girador,
		  dbo.tz_PagoCheque.Nombre_Girador,
		  dbo.tz_PagoCheque.email_girador,
		  dbo.tz_PagoCheque.telefono_girador
		FROM
		  dbo.tz_PagoCheque
		WHERE
		  dbo.tz_PagoCheque.IDPostulacionItem IN( $idpostulacionitem ) AND
		  dbo.tz_PagoCheque.TipoPagoCheque = 2");	  

		return $query->result_array();
		
	}
	
	function update_pago_cheque_fecha($idpostulacionitem,$nro_cheque,$monto,$id_usuario,$tipo_documento,$banco,$ctacte,$serie,
	$fecha,$cantidad_doctos,$id_pago = NULL,$nom_gir = NULL,$email_gir = NULL,$tel_gir = NULL, $rut_girador = NULL){
	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 1440");
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
			  IDTipoPago = 1440");
			
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
			  1440,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 
			  dbo.tz_PagoCheque.IDPagoCheque,
			  dbo.tz_PagoCheque.IDPostulacionItem,
			  dbo.tz_PagoCheque.TipoPagoCheque
			FROM
			  dbo.tz_PagoCheque
			WHERE
			  dbo.tz_PagoCheque.IDPostulacionItem = $idpostulacionitem AND
			  dbo.tz_PagoCheque.IDPagoCheque = $id_pago ");			
			
			if(count($query->result_array()) > 0){
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_PagoCheque
				SET					  
				  Banco = $banco,
				  CuentaCorriente = '$ctacte',
				  NSerie = '$serie',
				  Monto = $monto,
				  Fecha = ".$this->db->escape($fecha).",
				  NumCheques = $cantidad_doctos,
				  IDTipoDocumento = $tipo_documento,
				  nCheque = $nro_cheque,
				  IDModificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  CantPagos = 1,
				  NroPago = 1,
				  IDEmpresa = NULL,
				  NOC_Empresa = NULL,
				  rut_girador = ".$this->db->escape($rut_girador).",
				  Nombre_Girador = ".$this->db->escape($nom_gir).",
				  email_girador = ".$this->db->escape($email_gir).",
				  telefono_girador = ".$this->db->escape($tel_gir)."
				WHERE
				  dbo.tz_PagoCheque.IDPostulacionItem = $idpostulacionitem AND
				  dbo.tz_PagoCheque.IDPagoCheque = $id_pago ");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_PagoCheque(
				  IDPostulacionItem,
				  TipoPagoCheque,
				  Banco,
				  CuentaCorriente,
				  NSerie,
				  Monto,
				  Fecha,
				  NumCheques,
				  IDTipoDocumento,
				  nCheque,
				  IDModificador,
				  FechaModificacion,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa,
				  rut_girador,
				  Nombre_Girador,
				  email_girador,
				  telefono_girador)
				VALUES(
				  $idpostulacionitem,
				  2,
				  $banco,
				  '$ctacte',
				  '$serie',
				  $monto,
				  ".$this->db->escape($fecha).",
				  $cantidad_doctos,
				  $tipo_documento,
				  $nro_cheque,
				  $id_usuario,
				  GETDATE(),
				  1,
				  1,
				  NULL,
				  NULL,
				  ".$this->db->escape($rut_girador).",
				  ".$this->db->escape($nom_gir).",
				  ".$this->db->escape($email_gir).",
				  ".$this->db->escape($tel_gir).")");	
				
			}
		}else{
			
			$sql = "INSERT INTO
			  dbo.tz_PagoCheque(
			  IDPostulacionItem,
			  TipoPagoCheque,
			  Banco,
			  CuentaCorriente,
			  NSerie,
			  Monto,
			  Fecha,
			  NumCheques,
			  IDTipoDocumento,
			  nCheque,
			  IDModificador,
			  FechaModificacion,
			  CantPagos,
			  NroPago,
			  IDEmpresa,
			  NOC_Empresa,
			  rut_girador,
			  Nombre_Girador,
			  email_girador,
			  telefono_girador)
			VALUES(
			  $idpostulacionitem,
			  2,
			  $banco,
			  '$ctacte',
			  '$serie',
			  $monto,
			  ".$this->db->escape($fecha).",
			  $cantidad_doctos,
			  $tipo_documento,
			  $nro_cheque,
			  $id_usuario,
			  GETDATE(),
			  1,
			  1,
			  NULL,
			  NULL,
			  ".$this->db->escape($rut_girador).",
			  ".$this->db->escape($nom_gir).",
			  ".$this->db->escape($email_gir).",
			  ".$this->db->escape($tel_gir).")";
			
			$resultado = $this->db->query($sql);	
			
		}	
		
		return $resultado;	
		
	}	
	
	function update_pago_cheque_dia($idpostulacionitem,$monto,$id_usuario,$tipo_documento,$banco,$ctacte,$serie,$fecha,$cantidad_doctos,$id_pago = NULL){
		
		$none = array('.',',');
		$monto = str_replace($none,'',ltrim($monto,0));
	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 1439");
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
			  IDTipoPago = 1439");
			
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
			  1439,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 
			  dbo.tz_PagoCheque.IDPagoCheque,
			  dbo.tz_PagoCheque.IDPostulacionItem,
			  dbo.tz_PagoCheque.TipoPagoCheque
			FROM
			  dbo.tz_PagoCheque
			WHERE
			  dbo.tz_PagoCheque.IDPostulacionItem = $idpostulacionitem AND
			  dbo.tz_PagoCheque.IDPagoCheque = $id_pago ");			
			
			if(count($query->result_array()) > 0){
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_PagoCheque
				SET					  
				  Banco = $banco,
				  CuentaCorriente = '$ctacte',
				  NSerie = '$serie',
				  Monto = $monto,
				  Fecha = ".$this->db->escape($fecha).",
				  NumCheques = $cantidad_doctos,
				  IDTipoDocumento = $tipo_documento,
				  nCheque = 1,
				  IDModificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  CantPagos = 1,
				  NroPago = 1,
				  IDEmpresa = NULL,
				  NOC_Empresa = NULL
				WHERE
				  dbo.tz_PagoCheque.IDPostulacionItem = $idpostulacionitem AND
				  dbo.tz_PagoCheque.IDPagoCheque = $id_pago ");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_PagoCheque(
				  IDPostulacionItem,
				  TipoPagoCheque,
				  Banco,
				  CuentaCorriente,
				  NSerie,
				  Monto,
				  Fecha,
				  NumCheques,
				  IDTipoDocumento,
				  nCheque,
				  IDModificador,
				  FechaModificacion,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa,
				  rut_girador,
				  Nombre_Girador,
				  email_girador,
				  telefono_girador)
				VALUES(
				  $idpostulacionitem,
				  1,
				  $banco,
				  '$ctacte',
				  '$serie',
				  $monto,
				  ".$this->db->escape($fecha).",
				  $cantidad_doctos,
				  $tipo_documento,
				  1,
				  $id_usuario,
				  GETDATE(),
				  1,
				  1,
				  NULL,
				  NULL,
				  NULL,
				  NULL,
				  NULL,
				  NULL)");	
				
			}
		}else{
			
			$sql = "INSERT INTO
			  dbo.tz_PagoCheque(
			  IDPostulacionItem,
			  TipoPagoCheque,
			  Banco,
			  CuentaCorriente,
			  NSerie,
			  Monto,
			  Fecha,
			  NumCheques,
			  IDTipoDocumento,
			  nCheque,
			  IDModificador,
			  FechaModificacion,
			  CantPagos,
			  NroPago,
			  IDEmpresa,
			  NOC_Empresa,
			  rut_girador,
			  Nombre_Girador,
			  email_girador,
			  telefono_girador)
			VALUES(
			  $idpostulacionitem,
			  1,
			  $banco,
			  '$ctacte',
			  '$serie',
			  $monto,
			  ".$this->db->escape($fecha).",
			  $cantidad_doctos,
			  $tipo_documento,
			  1,
			  $id_usuario,
			  GETDATE(),
			  1,
			  1,
			  NULL,
			  NULL,
			  NULL,
			  NULL,
			  NULL,
			  NULL)";
			
			$resultado = $this->db->query($sql);	
			
		}	
		
		return $resultado;	
		
	}	
	
}