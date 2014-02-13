<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pago_pagare extends CI_Model {
	
	function get_pagos_pagare($idpostulacionitem){
			
		$query = $this->db->query("SELECT 
		  z.IDPagoPagare,
		  z.IDPostulacionItem,
		  z.bLegalizado,
		  z.NumPagare,
		  z.NumCuotas,
		  z.Monto,
		  z.nCuota,
		  z.MontoInteresCuota,
		  z.MontoCuota,
		  CONVERT(CHAR(10),z.FechaVencimiento,103) AS FechaVencimiento,		  
		  z.IDTipoDocumento,
		  z.IDModificador,
		  z.FechaModificacion,
		  z.CantPagos,
		  z.NroPago,
		  z.IDEmpresa,
		  z.NOC_Empresa
		FROM
		  dbo.tz_PagoPagare z
		WHERE
		  z.IDPostulacionItem IN ($idpostulacionitem)");
		return $query->result_array();
		
	}
	
	function get_pagares_general($desde,$hasta,$estado = NULL){
		//Impagos
		if($estado == 1){
			$opt = " AND (SELECT dbo.tz_IC_Boletas.NroBoleta FROM dbo.tz_IC_Boletas WHERE dbo.tz_IC_Boletas.IDPostulacionItem = dbo.tz_PagoPagare.IDPostulacionItem AND dbo.tz_IC_Boletas.idtipopago = 1442 AND dbo.tz_IC_Boletas.IDPago = dbo.tz_PagoPagare.IDPagoPagare) IS NULL ";
		}
		//pagos
		if($estado == 2){
			$opt = " AND (SELECT dbo.tz_IC_Boletas.NroBoleta FROM dbo.tz_IC_Boletas WHERE dbo.tz_IC_Boletas.IDPostulacionItem = dbo.tz_PagoPagare.IDPostulacionItem AND dbo.tz_IC_Boletas.idtipopago = 1442 AND dbo.tz_IC_Boletas.IDPago = dbo.tz_PagoPagare.IDPagoPagare) IS NOT NULL ";
		}
		
		$query = $this->db->query("SELECT 
		  dbo.tz_Postulacion.IDUsuario,
		  dbo.tz_PagoPagare.NumPagare,
		  dbo.tz_PagoPagare.NumCuotas,
		  dbo.tz_PagoPagare.Monto,
		  dbo.tz_PagoPagare.nCuota,
		  dbo.tz_PagoPagare.MontoCuota,
		  CONVERT(CHAR(10), dbo.tz_PagoPagare.FechaVencimiento, 103) AS FechaVencimiento,
		  (SELECT dbo.tz_IC_Boletas.NroBoleta FROM dbo.tz_IC_Boletas WHERE dbo.tz_IC_Boletas.IDPostulacionItem = dbo.tz_PagoPagare.IDPostulacionItem AND dbo.tz_IC_Boletas.idtipopago = 1442 AND dbo.tz_IC_Boletas.IDPago = dbo.tz_PagoPagare.IDPagoPagare) AS nroboleta,
		  dbo.tz_PostulacionItem.bEstado,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaInicio, 103) AS FechaInicio,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaTermino, 103) AS FechaTermino,
		  dbo.tz_Cursos.Nombre_Curso,
		  dbo.tz_PostulacionItem.IDPostulacionItem,
		  dbo.tz_PagoPagare.IDPagoPagare,
		  dbo.tz_EstadoPostulante.Nombre AS estado
		FROM
		  dbo.tz_PagoPagare
		  INNER JOIN dbo.tz_PostulacionItem ON (dbo.tz_PagoPagare.IDPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  LEFT OUTER JOIN dbo.tz_EstadoPostulante ON (dbo.tz_PostulacionItem.bEstado = dbo.tz_EstadoPostulante.IDEstado)
		WHERE
		  DATEADD(dd, 0, DATEDIFF(dd, 0, dbo.tz_Secciones.FechaInicio)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, dbo.tz_Secciones.FechaInicio)) <= '$hasta' AND 		  
		  dbo.tz_PostulacionItem.bEstado IN (3,12) $opt");
		return $query->result_array();
			
		
	}
	
	function get_datos_porid($idpostulacionitem,$id_pago){
		$query = $this->db->query("SELECT 
		  z.IDPagoPagare,
		  z.MontoCuota,
		  z.IDPostulacionItem,
		  (SELECT x.NroBoleta FROM dbo.tz_IC_Boletas x WHERE x.IDPago = $id_pago AND x.idtipopago = 1442) AS NroBoleta,
		  (SELECT CONVERT(CHAR(10),x.Fecha,103) FROM dbo.tz_IC_Boletas x WHERE x.IDPago = $id_pago AND x.idtipopago = 1442) AS Fecha
		FROM
		  dbo.tz_PagoPagare z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem AND 
		  z.IDPagoPagare = $id_pago");
		return $query->row();
	}

	
	function get_porrut($id_usuario){
		
		$query = $this->db->query("SELECT 
		  dbo.tz_Postulacion.IDUsuario,
		  dbo.tz_PagoPagare.NumPagare,
		  dbo.tz_PagoPagare.NumCuotas,
		  dbo.tz_PagoPagare.Monto,
		  dbo.tz_PagoPagare.nCuota,
		  dbo.tz_PagoPagare.MontoCuota,
		  CONVERT(CHAR(10),dbo.tz_PagoPagare.FechaVencimiento,103) AS FechaVencimiento,
		  (SELECT dbo.tz_IC_Boletas.NroBoleta FROM dbo.tz_IC_Boletas WHERE dbo.tz_IC_Boletas.IDPostulacionItem = dbo.tz_PagoPagare.IDPostulacionItem AND dbo.tz_IC_Boletas.idtipopago = 1442 AND dbo.tz_IC_Boletas.IDPago = dbo.tz_PagoPagare.IDPagoPagare) AS nroboleta,
		  dbo.tz_PostulacionItem.bEstado,
		  CONVERT(CHAR(10),dbo.tz_Secciones.FechaInicio,103) AS FechaInicio,
		  CONVERT(CHAR(10),dbo.tz_Secciones.FechaTermino,103) AS FechaTermino,
		  dbo.tz_Cursos.Nombre_Curso,
		  dbo.tz_PostulacionItem.IDPostulacionItem,
		  dbo.tz_PagoPagare.IDPagoPagare,
		  dbo.tz_EstadoPostulante.Nombre AS estado
		FROM
		  dbo.tz_PagoPagare
		  INNER JOIN dbo.tz_PostulacionItem ON (dbo.tz_PagoPagare.IDPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  LEFT JOIN dbo.tz_EstadoPostulante ON (dbo.tz_PostulacionItem.bEstado = dbo.tz_EstadoPostulante.IDEstado)
		WHERE
		  dbo.tz_Postulacion.IDUsuario = $id_usuario  AND
		  dbo.tz_PostulacionItem.bEstado IN(3,12)");
		return $query->result_array();			
		
	}
	
	
	function update_pago_pagare($idpostulacionitem,$legalizado = 'NULL', $numero_pagare = NULL, $num_cuotas, $monto_total,$monto_cuota,$cuota,
	$vcto,$tipo_docto,$cantidad_pagos,$id_usuario,$interes,$id_pago = NULL ){
	
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem  AND 
		  z.IDTipoPago = 1442");
		$resultado = count($query->result_array());
		
		if(!$legalizado){
			$legalizado = 'NULL';	
		}
		
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
			  IDTipoPago = 1442");
			
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
			  1442,
			  $id_usuario,
			  GETDATE(),
			  NULL,
			  NULL)");
			
		}
		
		if(is_numeric($id_pago)){
	
			$query = $this->db->query("SELECT 		
			  dbo.tz_PagoPagare.IDPostulacionItem			 
			FROM
			  dbo.tz_PagoPagare
			WHERE
			  dbo.tz_PagoPagare.IDPostulacionItem = $idpostulacionitem AND
			  dbo.tz_PagoPagare.IDPagoPagare = $id_pago ");			
			
			if(count($query->result_array()) > 0){						
				
				$query = $this->db->query("UPDATE 
				  dbo.tz_PagoPagare
				SET
				  bLegalizado = $legalizado,
				  NumPagare = ".$this->db->escape($numero_pagare).",
				  NumCuotas = $cantidad_pagos,
				  Monto = $monto_total,
				  nCuota = $cuota,
				  MontoInteresCuota = $interes,
				  MontoCuota = $monto_cuota,
				  FechaVencimiento = ".$this->db->escape($vcto).",
				  IDTipoDocumento = $tipo_docto,
				  IDModificador = $id_usuario,
				  FechaModificacion = GETDATE(),
				  CantPagos = $cantidad_pagos,
				  NroPago = $cuota,
				  IDEmpresa = NULL,
				  NOC_Empresa = NULL
				WHERE
				  dbo.tz_PagoPagare.IDPagoPagare = $id_pago AND 
				  dbo.tz_PagoPagare.IDPostulacionItem = $idpostulacionitem ");
				
			}else{
				
				$resultado = $this->db->query("INSERT INTO
				  dbo.tz_PagoPagare(
				  bLegalizado,
				  NumPagare,
				  NumCuotas,
				  Monto,
				  nCuota,
				  MontoInteresCuota,
				  MontoCuota,
				  FechaVencimiento,
				  IDTipoDocumento,
				  IDModificador,
				  FechaModificacion,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa,
				  IDPostulacionItem)
				VALUES(
				  $legalizado,
				  ".$this->db->escape($numero_pagare).",
				  $cantidad_pagos,
				  $monto_total,
				  $cuota,
				  $interes,
				  $monto_cuota,
				  ".$this->db->escape($vcto).",
				  $tipo_docto,
				  $id_usuario,
				  GETDATE(),
				  $cantidad_pagos,
				  $cuota,
				  NULL,
				  NULL,
				  $idpostulacionitem)");	
				
			}
		}else{
			
			$sql = "INSERT INTO
				  dbo.tz_PagoPagare(
				  bLegalizado,
				  NumPagare,
				  NumCuotas,
				  Monto,
				  nCuota,
				  MontoInteresCuota,
				  MontoCuota,
				  FechaVencimiento,
				  IDTipoDocumento,
				  IDModificador,
				  FechaModificacion,
				  CantPagos,
				  NroPago,
				  IDEmpresa,
				  NOC_Empresa,
				  IDPostulacionItem)
				VALUES(
				  $legalizado,
				  ".$this->db->escape($numero_pagare).",
				  $cantidad_pagos,
				  $monto_total,
				  $cuota,
				  $interes,
				  $monto_cuota,
				  ".$this->db->escape($vcto).",
				  $tipo_docto,
				  $id_usuario,
				  GETDATE(),
				  $cantidad_pagos,
				  $cuota,
				  NULL,
				  NULL,
				  $idpostulacionitem)";			
			$resultado = $this->db->query($sql);	
			
		}			
		return $resultado;	
		
	}	
		
	
	
}