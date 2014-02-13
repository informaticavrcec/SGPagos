<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pagoporfacturar extends CI_Model {
	
	function get_pagos_porfacturar_empresa($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  z.IDPagoPorFacturar,
		  z.IDPostulacionItem,
		  z.Monto,
		  z.IDTipoDocumento,
		  z.IDModificador,
		  z.FechaModificacion,
		  z.Descripcion,
		  z.bPagado,
		  z.NumFactura,
		  CONVERT(CHAR(10),z.FechaPago,103) AS FechaPago,
		  z.IDEmpresa,
		  z.NOC_Empresa,
		  z.NOC_Otic,
		  z.IDOTIC,
		  z.nrofactura
		FROM
		  dbo.tz_pagoPorFacturar z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem AND
		z.IDEmpresa IS NOT NULL ");
		return $query->row();		
		
	}
	
	function get_pagos_porfacturar_otic($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  z.IDPagoPorFacturar,
		  z.IDPostulacionItem,
		  z.Monto,
		  z.IDTipoDocumento,
		  z.IDModificador,
		  z.FechaModificacion,
		  z.Descripcion,
		  z.bPagado,
		  z.NumFactura,
		  CONVERT(CHAR(10),z.FechaPago,103) AS FechaPago,
		  z.IDEmpresa,
		  z.NOC_Empresa,
		  z.NOC_Otic,
		  z.IDOTIC,
		  z.nrofactura
		FROM
		  dbo.tz_pagoPorFacturar z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem AND
		z.IDOTIC IS NOT NULL ");
		return $query->row();		
		
	}
	
	function get_valida_deuda($idpostulacionitem){
		$query = $this->db->query("SELECT dbo.fn_finalizar_pagoporfacturar($idpostulacionitem) AS resp ");
		return $query->row();	
	}
	
	function update_pago_porfacturar_empresa($idpostulacionitem,$monto,$descripcion,$num_fact,$fecha_pago,$id_empresa,$noc_empresa,$id_pago = NULL){
		
		$id_usuario = $this->session->userdata('id_usuario');	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 3811");
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
			  IDTipoPago = 3811");
			
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
			  3811,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 
			  dbo.tz_pagoPorFacturar.IDPostulacionItem,
			  dbo.tz_pagoPorFacturar.IDPagoPorFacturar
			FROM
			  dbo.tz_pagoPorFacturar
			WHERE
			  dbo.tz_pagoPorFacturar.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_pagoPorFacturar.IDPagoPorFacturar = $id_pago");			
			
			if(count($query->result_array()) > 0){
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_pagoPorFacturar
				SET				
				  Monto = $monto,
				  IDTipoDocumento = 1,
				  IDModificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  Descripcion = ".$this->db->escape($descripcion).",
				  bPagado = NULL,
				  NumFactura = ".$this->db->escape($num_fact).",
				  FechaPago = ".$this->db->escape($fecha_pago).",
				  IDEmpresa = ".$this->db->escape($id_empresa).",
				  NOC_Empresa = ".$this->db->escape($noc_empresa).",
				  NOC_Otic = NULL,
				  IDOTIC = NULL,
				  nrofactura = NULL
				WHERE
				  dbo.tz_pagoPorFacturar.IDPostulacionItem = $idpostulacionitem AND 
				  dbo.tz_pagoPorFacturar.IDPagoPorFacturar = $id_pago");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_pagoPorFacturar(
				  IDPostulacionItem,
				  Monto,
				  IDTipoDocumento,
				  IDModificador,
				  FechaModificacion,
				  Descripcion,
				  bPagado,
				  NumFactura,
				  FechaPago,
				  IDEmpresa,
				  NOC_Empresa,
				  NOC_Otic,
				  IDOTIC,
				  nrofactura)
				VALUES(			
				  $idpostulacionitem,
				  $monto,
				  1,
				  $id_usuario,
				  GETDATE(),
				  ".$this->db->escape($descripcion).",
				  NULL,
				  ".$this->db->escape($num_fact).",
				  ".$this->db->escape($fecha_pago).",
				  $id_empresa,
				  ".$this->db->escape($noc_empresa).",
				  NULL,
				  NULL,
				  NULL)");	
				
			}
		}else{
			
			$sql = "INSERT INTO
				  dbo.tz_pagoPorFacturar(
				  IDPostulacionItem,
				  Monto,
				  IDTipoDocumento,
				  IDModificador,
				  FechaModificacion,
				  Descripcion,
				  bPagado,
				  NumFactura,
				  FechaPago,
				  IDEmpresa,
				  NOC_Empresa,
				  NOC_Otic,
				  IDOTIC,
				  nrofactura)
				VALUES(			
				  $idpostulacionitem,
				  $monto,
				  1,
				  $id_usuario,
				  GETDATE(),
				  ".$this->db->escape($descripcion).",
				  NULL,
				  ".$this->db->escape($num_fact).",
				  ".$this->db->escape($fecha_pago).",
				  $id_empresa,
				  ".$this->db->escape($noc_empresa).",
				  NULL,
				  NULL,
				  NULL)";			
			$resultado = $this->db->query($sql);
							
		}			
		return $resultado;			
	}
	
	function update_pago_porfacturar_otic($idpostulacionitem,$monto,$descripcion,$num_fact,$fecha_pago,$id_empresa,$noc_empresa,$id_pago = NULL){
		
		$id_usuario = $this->session->userdata('id_usuario');	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 3811");
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
			  IDTipoPago = 3811");
			
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
			  3811,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 
			  dbo.tz_pagoPorFacturar.IDPostulacionItem,
			  dbo.tz_pagoPorFacturar.IDPagoPorFacturar
			FROM
			  dbo.tz_pagoPorFacturar
			WHERE
			  dbo.tz_pagoPorFacturar.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_pagoPorFacturar.IDPagoPorFacturar = $id_pago");			
			
			if(count($query->result_array()) > 0){
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_pagoPorFacturar
				SET				
				  Monto = $monto,
				  IDTipoDocumento = 1,
				  IDModificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  Descripcion = ".$this->db->escape($descripcion).",
				  bPagado = NULL,
				  NumFactura = ".$this->db->escape($num_fact).",
				  FechaPago = ".$this->db->escape($fecha_pago).",
				  IDEmpresa = NULL,
				  NOC_Empresa = NULL,
				  NOC_Otic = ".$this->db->escape($noc_empresa).",
				  IDOTIC = ".$this->db->escape($id_empresa).",
				  nrofactura = NULL
				WHERE
				  dbo.tz_pagoPorFacturar.IDPostulacionItem = $idpostulacionitem AND 
				  dbo.tz_pagoPorFacturar.IDPagoPorFacturar = $id_pago");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_pagoPorFacturar(
				  IDPostulacionItem,
				  Monto,
				  IDTipoDocumento,
				  IDModificador,
				  FechaModificacion,
				  Descripcion,
				  bPagado,
				  NumFactura,
				  FechaPago,
				  IDEmpresa,
				  NOC_Empresa,
				  NOC_Otic,
				  IDOTIC,
				  nrofactura)
				VALUES(			
				  $idpostulacionitem,
				  $monto,
				  1,
				  $id_usuario,
				  GETDATE(),
				  ".$this->db->escape($descripcion).",
				  NULL,
				  ".$this->db->escape($num_fact).",
				  ".$this->db->escape($fecha_pago).",
				  NULL,
				  NULL,
				  ".$this->db->escape($noc_empresa).",
				  $id_empresa,
				  NULL)");	
				
			}
		}else{
			
			$sql = "INSERT INTO
				  dbo.tz_pagoPorFacturar(
				  IDPostulacionItem,
				  Monto,
				  IDTipoDocumento,
				  IDModificador,
				  FechaModificacion,
				  Descripcion,
				  bPagado,
				  NumFactura,
				  FechaPago,
				  IDEmpresa,
				  NOC_Empresa,
				  NOC_Otic,
				  IDOTIC,
				  nrofactura)
				VALUES(			
				  $idpostulacionitem,
				  $monto,
				  1,
				  $id_usuario,
				  GETDATE(),
				  ".$this->db->escape($descripcion).",
				  NULL,
				  ".$this->db->escape($num_fact).",
				  ".$this->db->escape($fecha_pago).",
				  NULL,
				  NULL,
				  ".$this->db->escape($noc_empresa).",
				  $id_empresa,
				  NULL)";			
			$resultado = $this->db->query($sql);
							
		}			
		return $resultado;			
	}	
}