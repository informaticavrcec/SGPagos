<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_postulacionitem_tipodepago extends CI_Model {
	
	function delete_pagos($idpostulacionitem){
				
		$query = $this->db->query("EXEC p_pagos_eliminar $idpostulacionitem ");
		return TRUE;	
	}
	
	
	function get_procesapago($idpostulacionitem){
		$query = $this->db->query("SELECT dbo.fn_finalizar_pago($idpostulacionitem) AS resp ");
		return $query->row();	
	}
	
	function get_matricula_alumno($idpostulacionitem){
		
		$id_usuario = $this->session->userdata('id_usuario');
		$query = $this->db->query("EXEC p_alumno_matricular $idpostulacionitem , $id_usuario ");
		return TRUE;	
	}
	
		function get_pagos_factura($idpostulacionitem,$porfacturar = FALSE){
			
			if($porfacturar == TRUE){				
				$opt = " UNION (SELECT 
				  'Por Facturar' AS tipo,
				  SUM(z.Monto) AS c
				FROM
				  dbo.tz_pagoPorFacturar z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem )";	
			}
				$sql = "(SELECT 
				  'Cheques' AS tipo,
				  SUM(z.Monto) AS c
				FROM
				  dbo.tz_PagoCheque z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem)
				  UNION
				  (SELECT 
				  'Tarjeta debito' AS tipo,
				  SUM(z.Monto) AS c
				FROM
				  dbo.tz_PagoDebito z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem)
				  UNION
					(SELECT 
				  'Deposito' AS tipo,
				  SUM(z.Monto) AS c
				FROM
				  dbo.tz_PagoDeposito z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem)
					UNION
					(SELECT 
				  'Efectivo' AS tipo,
				  SUM(z.Monto) AS c
				FROM
				  dbo.tz_PagoEfectivo z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem)
				  UNION
					 (SELECT 
				  'Tarjeta Credito' AS tipo,
				  SUM(z.Monto) AS c
				FROM
				  dbo.tz_PagoTarjeta z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem)
					UNION
					 (SELECT 
				  'Transferencia' AS tipo,
				  SUM(z.Monto) AS c
				FROM
				  dbo.tz_PagoTransferencia z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem)  
					  UNION
					 (SELECT 
				  'Vale Vista' AS tipo,
				  SUM(z.Monto) AS c
				FROM
				  dbo.tz_PagoValeVista z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem)
						UNION
					 (SELECT 
				  'WEBPAY' AS tipo,
				  SUM(z.Monto) AS c
				FROM
				  dbo.tz_PagoWebPay z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem)
				  UNION
					 (SELECT 
				  'Pagaré' AS tipo,
				  SUM(z.MontoCuota) AS c
				FROM
				  dbo.tz_PagoPagare z
				WHERE
				  z.IDTipoDocumento = 1 AND 
				  z.IDPostulacionItem = $idpostulacionitem)
				  $opt
				  ORDER BY 
		  tipo";
		
		$query = $this->db->query($sql);
		return $query->result_array();
		
	}
	
	function get_pagos_boletas($idpostulacionitem,$porfacturar = FALSE,$sin_pagare = FALSE){
		
		if($sin_pagare == FALSE){
			$opt1 = "UNION
					 (SELECT 
				  'Pagaré' AS tipo,
				  SUM(z.MontoCuota) AS c
				FROM
				  dbo.tz_PagoPagare z
				WHERE
				  z.IDTipoDocumento = 0 AND 
				  z.IDPostulacionItem = $idpostulacionitem)";	
		}
		
		$query = $this->db->query("(SELECT 
		  'Cheques' AS tipo,
		  SUM(z.Monto) AS c
		FROM
		  dbo.tz_PagoCheque z
		WHERE
		  z.IDTipoDocumento = 0 AND 
		  z.IDPostulacionItem = $idpostulacionitem)
		  UNION
		  (SELECT 
		  'Tarjeta debito' AS tipo,
		  SUM(z.Monto) AS c
		FROM
		  dbo.tz_PagoDebito z
		WHERE
		  z.IDTipoDocumento = 0 AND 
		  z.IDPostulacionItem = $idpostulacionitem)
		  UNION
			(SELECT 
		  'Deposito' AS tipo,
		  SUM(z.Monto) AS c
		FROM
		  dbo.tz_PagoDeposito z
		WHERE
		  z.IDTipoDocumento = 0 AND 
		  z.IDPostulacionItem = $idpostulacionitem)
			UNION
			(SELECT 
		  'Efectivo' AS tipo,
		  SUM(z.Monto) AS c
		FROM
		  dbo.tz_PagoEfectivo z
		WHERE
		  z.IDTipoDocumento = 0 AND 
		  z.IDPostulacionItem = $idpostulacionitem)
		  UNION
			 (SELECT 
		  'Tarjeta Credito' AS tipo,
		  SUM(z.Monto) AS c
		FROM
		  dbo.tz_PagoTarjeta z
		WHERE
		  z.IDTipoDocumento = 0 AND 
		  z.IDPostulacionItem = $idpostulacionitem)
			UNION
			 (SELECT 
		  'Transferencia' AS tipo,
		  SUM(z.Monto) AS c
		FROM
		  dbo.tz_PagoTransferencia z
		WHERE
		  z.IDTipoDocumento = 0 AND 
		  z.IDPostulacionItem = $idpostulacionitem)  
			  UNION
			 (SELECT 
		  'Vale Vista' AS tipo,
		  SUM(z.Monto) AS c
		FROM
		  dbo.tz_PagoValeVista z
		WHERE
		  z.IDTipoDocumento = 0 AND 
		  z.IDPostulacionItem = $idpostulacionitem)
				UNION
			 (SELECT 
		  'WEBPAY' AS tipo,
		  SUM(z.Monto) AS c
		FROM
		  dbo.tz_PagoWebPay z
		WHERE
		  z.IDTipoDocumento = 0 AND 
		  z.IDPostulacionItem = $idpostulacionitem)
		  $opt1
		  ORDER BY 
  tipo");
		return $query->result_array();
		
	}
		

	public function eliminar_pago($tipo_pago,$idpago,$obl = FALSE,$idpostulacionitem){
	
		if(is_numeric($idpago) AND is_numeric($tipo_pago) AND is_numeric($idpostulacionitem)){
			
			switch($tipo_pago){
				case 1439 : 
				$table = "DELETE 
				FROM
				  dbo.tz_PagoCheque
				WHERE
				  dbo.tz_PagoCheque.IDPagoCheque = $idpago ";
				$table2 = "SELECT 
				  dbo.tz_PagoCheque.IDPagoCheque
				FROM
				  dbo.tz_PagoCheque
				WHERE
				  dbo.tz_PagoCheque.IDPostulacionItem IN ($idpostulacionitem) AND
				  dbo.tz_PagoCheque.TipoPagoCheque = 1 ";
				break;
				//WEBPAy
				case 3987 : 
				$table = "DELETE 
				FROM
				  dbo.tz_PagoWebPay
				WHERE
				  dbo.tz_PagoWebPay.IDPagoWebPay = $idpago ";
				$table2 = "SELECT 
				  dbo.tz_PagoWebPay.IDPagoWebPay
				FROM
				  dbo.tz_PagoWebPay
				WHERE
				  dbo.tz_PagoWebPay.IDPostulacionItem IN ($idpostulacionitem) ";
				break;
				//Efectivo
				case 1438 : $table = "DELETE 
				FROM
				  dbo.tz_PagoEfectivo
				WHERE
				  dbo.tz_PagoEfectivo.IDPagoEfectivo = $idpago";
				$table2 = "SELECT 
				  z.IDPostulacionItem
				FROM
				  dbo.tz_PagoEfectivo z
				WHERE
				  z.IDPostulacionItem IN ($idpostulacionitem) ";
				break;				
				//Cheque fecha
				case 1440 : 
				$table = "DELETE 
				FROM
				  dbo.tz_PagoCheque
				WHERE
				  dbo.tz_PagoCheque.IDPagoCheque = $idpago ";
				$table2 = "SELECT 
				  dbo.tz_PagoCheque.IDPagoCheque
				FROM
				  dbo.tz_PagoCheque
				WHERE
				  dbo.tz_PagoCheque.IDPostulacionItem IN ($idpostulacionitem) AND
				  dbo.tz_PagoCheque.TipoPagoCheque = 2 ";
				break;
				//Deposito
				case 3860 : 
				$table = "DELETE 
				FROM
				  dbo.tz_PagoDeposito
				WHERE
				  dbo.tz_PagoDeposito.IDPagoDeposito = $idpago";
				$table2 = "SELECT 
				  dbo.tz_PagoDeposito.IDPostulacionItem
				FROM
				  dbo.tz_PagoDeposito
				WHERE
				  dbo.tz_PagoDeposito.IDPostulacionItem IN ($idpostulacionitem)  ";
				break;
				//transferencia
				case 3862 : 
				$table = "DELETE 
				FROM
				  dbo.tz_PagoTransferencia
				WHERE			
				  dbo.tz_PagoTransferencia.IDPagoTransferencia = $idpago ";
				$table2 = "SELECT 
				  dbo.tz_PagoTransferencia.IDPostulacionItem
				FROM
				  dbo.tz_PagoTransferencia
				WHERE
				  dbo.tz_PagoTransferencia.IDPostulacionItem IN ($idpostulacionitem)";
				break;
				//Vale vista
				case 3861 : 
				$table = "DELETE 
				FROM
				  dbo.tz_PagoValeVista
				WHERE
				  dbo.tz_PagoValeVista.IDPagoValeVista = $idpago ";
				$table2 = "SELECT 
				  dbo.tz_PagoValeVista.NumeroValeVista
				FROM
				  dbo.tz_PagoValeVista
				WHERE
				  dbo.tz_PagoValeVista.IDPostulacionItem IN ($idpostulacionitem) ";
				break;
				//Tarjeta debito
				case 3805 : 
				$table = "DELETE 
				FROM
				  dbo.tz_PagoDebito
				WHERE
				  dbo.tz_PagoDebito.IDPagoDebito = $idpago ";
				$table2 = "SELECT 
				  dbo.tz_PagoDebito.IDPostulacionItem
				FROM
				  dbo.tz_PagoDebito
				WHERE
				  dbo.tz_PagoDebito.IDPostulacionItem IN ($idpostulacionitem)";
				break;
				//Tarjeta credito
				case 1441 : 
				$table = "DELETE 
				FROM
				  dbo.tz_PagoTarjeta
				WHERE
				  dbo.tz_PagoTarjeta.IDPagoTarjeta = $idpago ";
				$table2 = "SELECT 
				  dbo.tz_PagoTarjeta.IDPostulacionItem
				FROM
				  dbo.tz_PagoTarjeta
				WHERE
				  dbo.tz_PagoTarjeta.IDPostulacionItem IN ($idpostulacionitem)";
				break;
				//Tarjeta credito
				case 3811 : 
				$table = "DELETE 
				FROM
				  dbo.tz_pagoPorFacturar
				WHERE
				  dbo.tz_pagoPorFacturar.IDPagoPorFacturar = $idpago ";
				$table2 = "SELECT 
				  dbo.tz_pagoPorFacturar.IDPostulacionItem,
				  dbo.tz_pagoPorFacturar.IDPagoPorFacturar
				FROM
				  dbo.tz_pagoPorFacturar
				WHERE
				  dbo.tz_pagoPorFacturar.IDPostulacionItem  IN ($idpostulacionitem)";
				break;
				//Tarjeta credito
				case 1442 : 
				$table = "DELETE 
				FROM
				  dbo.tz_PagoPagare
				WHERE
				  dbo.tz_PagoPagare.IDPagoPagare = $idpago ";
				$table2 = "SELECT 
				  dbo.tz_PagoPagare.IDPostulacionItem,
				  dbo.tz_PagoPagare.IDPagoPagare
				FROM
				  dbo.tz_PagoPagare
				WHERE
				  dbo.tz_PagoPagare.IDPostulacionItem  IN ($idpostulacionitem)";
				break;
				
			}			
			
			$query = $this->db->query($table);
			
			$query2 = $this->db->query($table2);
			if(count($query2->result_array()) < 1){
				$sql = "DELETE 
				FROM
				  dbo.tz_PostulacionItem_TipoPago
				WHERE
				  dbo.tz_PostulacionItem_TipoPago.IDPostulacionItem IN ($idpostulacionitem) AND 
				  dbo.tz_PostulacionItem_TipoPago.IDTipoPago = $tipo_pago ";
				$this->db->query($sql);
			}
			
			/*$this->db->query("DELETE 
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ");*/
			//Factura  
			/*$this->db->query("DELETE 
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ");*/
				
			
		}
		
		return FALSE;
			
	}
	
	public function get_formaspago_porpostulacionitem($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  z.IDPostulacionItem,
		  a.nmbre AS nombre_pago,
		  z.IDPostulacionItemTipoPago,
		  z.IDTipoPago,
		  b.NombreApellido AS creador,
		  z.FechaCreacion
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		  INNER JOIN dbo.ta_MetadatoDetalle a ON (z.IDTipoPago = a.IDMetadatoDetalle)
		  LEFT OUTER JOIN dbo.vUsuarioAll b ON (z.IDCreador = b.idt_usrio)
		WHERE
		  z.IDPostulacionItem IN ($idpostulacionitem)
		ORDER BY 
		  nombre_pago ");
		return $query->result_array();
	}
	
	public function get_formaspago_porpostulacionitem_boleta($idpostulacionitem,$nro_boleta){
		
		$sql = "SELECT DISTINCT
		  z.IDPostulacionItem,
		  a.nmbre AS nombre_pago,
		  z.IDPostulacionItemTipoPago,
		  z.IDTipoPago,
		  b.NombreApellido AS creador,		
		  dbo.tz_IC_Boletas.NroBoleta
		FROM
		  dbo.tz_PostulacionItem_TipoPago z
		  INNER JOIN dbo.ta_MetadatoDetalle a ON (z.IDTipoPago = a.IDMetadatoDetalle)
		  LEFT OUTER JOIN dbo.vUsuarioAll b ON (z.IDCreador = b.idt_usrio)
		  INNER JOIN dbo.tz_IC_Boletas ON (z.IDPostulacionItem = dbo.tz_IC_Boletas.IDPostulacionItem)
		  AND (z.IDTipoPago = dbo.tz_IC_Boletas.idtipopago)
		WHERE
		  z.IDPostulacionItem IN ($idpostulacionitem) AND 
		  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND
		  dbo.tz_IC_Boletas.NroBoleta = '$nro_boleta'
		ORDER BY
		  nombre_pago";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function get_detalle_boleta($idpostulacionitem){
		$query = $this->db->query("SELECT 
		  z.NroBoleta,
		  SUM(z.MontoBoleta) AS MontoBoleta,
		  CONVERT(CHAR(10), z.Fecha, 103) AS fecha,
		  dbo.vUsuarioAll.NombreApellido
		FROM
		  dbo.tz_IC_Boletas z
		  LEFT JOIN dbo.vUsuarioAll ON (z.IDCreador = dbo.vUsuarioAll.idt_usrio)
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem
		GROUP BY
		  z.NroBoleta,
		  CONVERT(CHAR(10), z.Fecha, 103),
		  dbo.vUsuarioAll.NombreApellido");
		return $query->result_array();	
	}
	
	public function get_detalle_factura($idpostulacionitem){
		$query = $this->db->query("SELECT 
		  a.NroFactura,		
		  CONVERT(CHAR(10), a.Fecha, 103) AS fecha,
		  a.MontoFactura,
		  a.IDPago,
		  a.Cuota,
		  a.IDTipoPago,
		  a.IDEmpresa,
		  b.NombreApellido,
		  c.RSocial,
		  c.RUT
		FROM
		  dbo.tz_IC_Facturas a
		  LEFT JOIN dbo.vUsuarioAll b ON (a.IDCreador = b.idt_usrio)
		  LEFT JOIN dbo.tz_EmpresaRegistrada c ON (a.IDEmpresa = c.IDEmpRegistrada)
		WHERE
		  a.IDPostulacionItem = $idpostulacionitem ");
		return $query->result_array();	
	}
	
	public function get_detalle_delpago($idpostulacionitem,$idtipopago,$numero_boleta = ''){
		
		if($numero_boleta != ''){
			$opt = " AND
			  z.IDPagoPagare = (SELECT 
			  dbo.tz_IC_Boletas.IDPago
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.NroBoleta = '$numero_boleta' AND 
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND
			  dbo.tz_IC_Boletas.idtipopago = 1442)  ";
		}
		
		switch($idtipopago){
			//Tarjetad e credito
			case 1441 : $sql = "SELECT 
			  z.IDPagoTarjeta AS id,
			  dbo.vUsuarioAll.NombreApellido AS creador,
			  z.IDPagoTarjeta,
			  z.IDPostulacionItem,
			  z.TipoPagoTarjeta,
			  z.IDTarjeta,			
			  'Nro. Tarj. : ' + z.NumTarjeta4 + '<strong>/</strong> Nro. Trans.' + z.NumTransaccion + ' <strong>/</strong> Banco : ' + dbo.ta_MetadatoDetalle.nmbre AS detalle,	
			  z.NumTarjeta,				
			  z.Monto,			
			  z.IDTipoDocumento,			 
			  z.FechaModificacion AS creado,
			  z.CantPagos,
			  z.NroPago,
			  z.IDEmpresa,
			  z.NOC_Empresa,
			  dbo.tz_TipoDocumento.Nombre AS documento,
			  dbo.ta_MetadatoDetalle.nmbre AS banco
			FROM
			  dbo.tz_PagoTarjeta z
			  LEFT OUTER JOIN dbo.vUsuarioAll ON (z.IDModificador = dbo.vUsuarioAll.idt_usrio)
			  INNER JOIN dbo.tz_TipoDocumento ON (z.IDTipoDocumento = dbo.tz_TipoDocumento.IDTipoDocumento)
			  INNER JOIN dbo.ta_MetadatoDetalle ON (z.Banco = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem ";
			break;
			//Debito
			case 3805 : $sql = "SELECT
			  z.IDPagoDebito AS id,			  
			  z.Monto,
			  z.FechaTransaccion,
			  CAST(z.NroPago AS VARCHAR(100)) + ' / ' + CAST(z.CantPagos AS VARCHAR(100)) +' <b>/</b> Bco. Emisor : ' + dbo.ta_MetadatoDetalle.nmbre + ' <b>/</b> Cod. Trans. : ' + z.CodigoTransaccion + ' <b>/</b> 4 Ult. digitos : ' + CAST( z.Cuatroultimosdigitos AS VARCHAR(100) ) AS detalle,			  	
			  z.FechaModificacion AS creado,
			  z.IDTipoDocumento,			  
			  z.IDEmpresa,
			  z.NOC_Empresa,
			  a.NombreApellido AS creador,
			  b.Nombre AS documento
			FROM
			  dbo.tz_PagoDebito z
			  INNER JOIN dbo.vUsuarioAll a ON (z.IDMOdificador = a.idt_usrio)
			  INNER JOIN dbo.tz_TipoDocumento b ON (z.IDTipoDocumento = b.IDTipoDocumento)
			  INNER JOIN dbo.ta_MetadatoDetalle ON (z.BancoEmisor = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem ";
			break;
			//Efectivo
			case 1438 : $sql = "SELECT
			  z.IDPagoEfectivo AS id,
			  a.Nombre AS documento,			  
			  z.Monto,
			  z.IDTipoDocumento,			  
  			  z.IDFechaModificacion AS creado,
			  'Dinero en efectivo' AS detalle,			  
			  z.IDEmpresa,
			  z.NOC_Empresa,
			  dbo.vUsuarioAll.NombreApellido AS creador
			FROM
			  dbo.tz_PagoEfectivo z
			  INNER JOIN dbo.tz_TipoDocumento a ON (z.IDTipoDocumento = a.IDTipoDocumento)
			  INNER JOIN dbo.vUsuarioAll ON (z.IDModificador = dbo.vUsuarioAll.idt_usrio)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem ";
			break;
			//Transferencia
			case 3862 : $sql = "SELECT 
			  z.IDPagoTransferencia AS id,
			  a.NombreApellido AS creador,
			  b.Nombre AS documento,
			  'Nro. pago : ' + CAST(z.NroPago AS VARCHAR(100)) + ' <b>/</b> Cant. doctos : ' + CAST(z.CantPagos AS VARCHAR(100) ) + ' <b>/</b> Nro. Transf : ' + z.NumeroTransferencia + ' <strong>/</strong> Banco : ' + dbo.ta_MetadatoDetalle.nmbre AS detalle,
			  z.Monto,
			  z.IDBanco,
			  z.FechaModificacion AS creado,
			  z.IDTipoDocumento,
			  z.IDEmpresa,
			  z.NOC_Empresa
			FROM
			  dbo.tz_PagoTransferencia z
			  INNER JOIN dbo.vUsuarioAll a ON (z.IDModificador = a.idt_usrio)
			  INNER JOIN dbo.tz_TipoDocumento b ON (z.IDTipoDocumento = b.IDTipoDocumento)
			  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (z.IDBanco = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem ";
			break;
			//Cheque al dia
			case 1439 : $sql = "SELECT
			  z.IDPagoCheque AS id,
			  a.Nombre AS documento,
			  b.NombreApellido AS creador,
			  z.TipoPagoCheque,
			  z.Banco,
			  'Nro. serie : ' + CAST(z.NSerie AS VARCHAR(50)) + ' <b>/</b> Vcto. : ' + CONVERT(CHAR(10),z.Fecha,103) AS detalle,			  
			  z.Monto,
			  z.Fecha,
			  z.NumCheques,
			  z.IDTipoDocumento,
			  z.nCheque,
			  z.FechaModificacion AS creado,
			  z.CantPagos,
			  z.NroPago,
			  z.IDEmpresa,
			  z.NOC_Empresa,
			  z.rut_girador,
			  z.Nombre_Girador,
			  z.email_girador,
			  z.telefono_girador
			FROM
			  dbo.tz_PagoCheque z
			  INNER JOIN dbo.tz_TipoDocumento a ON (z.IDTipoDocumento = a.IDTipoDocumento)
			  INNER JOIN dbo.vUsuarioAll b ON (z.IDModificador = b.idt_usrio)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.TipoPagoCheque =  1
			ORDER BY
			  z.Fecha";
			break;
			//Cheqye a fecha
			case 1440 : $sql = "SELECT 
			  z.IDPagoCheque AS id,
			  a.Nombre AS documento,
			  b.NombreApellido AS creador,
			  z.TipoPagoCheque,
			  z.Banco,
			  dbo.ta_MetadatoDetalle.nmbre + ' Serie : ' + CAST(z.NSerie AS VARCHAR(50)) + ' <b>/</b> Vcto. : ' + CONVERT(CHAR(10),z.Fecha,103) AS detalle,		  
			  z.Monto,			 
			  z.NumCheques,
			  z.IDTipoDocumento,
			  z.nCheque,
			  z.FechaModificacion AS creado,
			  z.CantPagos,
			  z.NroPago,
			  z.IDEmpresa,
			  z.NOC_Empresa,			  
			  z.email_girador,
			  z.telefono_girador
			FROM
			  dbo.tz_PagoCheque z
			  INNER JOIN dbo.tz_TipoDocumento a ON (z.IDTipoDocumento = a.IDTipoDocumento)
			  INNER JOIN dbo.vUsuarioAll b ON (z.IDModificador = b.idt_usrio)
			  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (z.Banco = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.TipoPagoCheque =  2
			ORDER BY
			  z.Fecha";
			break;
			//Por facturar
			case 3811 : $sql = "SELECT 
			  z.IDPagoPorFacturar AS id,
			  a.Nombre AS documento,
			  b.NombreApellido AS creador,
			  z.Monto,
			  z.IDTipoDocumento,			 
			  z.FechaModificacion AS creado,
			  z.Descripcion,
			  z.bPagado,
			  z.NumFactura,
			  z.FechaPago,			  
			  z.NOC_Empresa,
			  z.NOC_Otic,
			  z.IDOTIC,
			  z.nrofactura,
			  c.RUT + ' ' + c.RSocial + d.RUT + ' ' +  d.Nombre AS detalle			    
			FROM
			  dbo.tz_pagoPorFacturar z
			  INNER JOIN dbo.tz_TipoDocumento a ON (z.IDTipoDocumento = a.IDTipoDocumento)
			  INNER JOIN dbo.vUsuarioAll b ON (z.IDModificador = b.idt_usrio)
			  LEFT OUTER JOIN dbo.tz_EmpresaRegistrada c ON (z.IDEmpresa = c.IDEmpRegistrada)
			  LEFT OUTER JOIN dbo.tz_Otic d ON (z.IDOTIC = d.IDOtic)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem ";
			break;
			//Deposito
			case 3860 : $sql = "SELECT
			  z.IDPagoDeposito AS id,
			  a.Nombre AS documento,
			  b.NombreApellido AS creador,
			  CAST(z.NroPago AS VARCHAR(10)) + ' <strong>/</strong> ' + CAST(z.CantPagos AS VARCHAR(100)) + ' <b>/</b> Fecha dpto : ' + CONVERT(CHAR,z.FechaDeposito,103) + ' <b>/</b> Nro. Comp. : ' + CAST(z.NumeroComprobante AS VARCHAR(100)) AS detalle,
			  z.Monto,			  			  
			  z.FechaModificacion AS creado,
			  z.IDTipoDocumento,			  
			  z.NroPago,
			  z.IDEmpresa,
			  z.NOC_Empresa
			FROM
			  dbo.tz_PagoDeposito z
			  INNER JOIN dbo.vUsuarioAll b ON (z.IDModificador = b.idt_usrio)
			  INNER JOIN dbo.tz_TipoDocumento a ON (z.IDTipoDocumento = a.IDTipoDocumento)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem ";
			break;
			//Webapy
			case 3987 : $sql = "SELECT
			  z.IDPagoWebPay AS id, 
			  z.Monto,
			  'Nro Trans. : ' + z.NumeroTransaccion AS detalle,
			  z.UltimosDigitosTarjeta,
			  z.NumeroCuotas,
			  z.TasaInteresMax,
			  z.HoraTransaccion,
			  z.FechaTransaccion AS creado,
			  z.FechaContable,
			  z.IDTipoDocumento,
			  z.FechaModificacion,
			  z.OrdenCompra,
			  z.IDSesion,
			  z.CodigoAutorizacion,
			  z.Respuesta,
			  z.Mac,
			  z.IDEmpresa,
			  z.NOC_Empresa,
			  a.NombreApellido AS creador,
			  b.Nombre AS documento
			FROM
			  dbo.tz_PagoWebPay z
			  LEFT OUTER JOIN dbo.vUsuarioAll a ON (z.IDModificador = a.idt_usrio)
			  INNER JOIN dbo.tz_TipoDocumento b ON (z.IDTipoDocumento = b.IDTipoDocumento)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem ";
			break;
			case 3861 : $sql = "SELECT
			  dbo.tz_PagoValeVista.IDPagoValeVista AS id, 
			  CAST(dbo.tz_PagoValeVista.NroPago AS VARCHAR(10)) + ' / ' + CAST(dbo.tz_PagoValeVista.CantPagos AS VARCHAR(10)) + ' <strong>/</strong> Nro. : ' + dbo.tz_PagoValeVista.NumeroValeVista + ' - ' + CONVERT(CHAR(10),dbo.tz_PagoValeVista.FechaValeVista,103) AS detalle,
			  dbo.tz_PagoValeVista.Monto,
			  CONVERT(CHAR(10),dbo.tz_PagoValeVista.FechaValeVista,103) AS FechaValeVista ,
			  dbo.tz_PagoValeVista.IDBanco,
			  dbo.tz_PagoValeVista.IDModificador,
			  dbo.tz_PagoValeVista.FechaModificacion AS creado,
			  dbo.tz_PagoValeVista.IDTipoDocumento,
			  dbo.tz_PagoValeVista.CantPagos,
			  dbo.tz_PagoValeVista.NroPago,
			  dbo.tz_PagoValeVista.IDEmpresa,
			  dbo.tz_PagoValeVista.NOC_Empresa,
			  dbo.tz_PagoValeVista.IDPostulacionItem,
			  dbo.tz_TipoDocumento.Nombre AS documento,
			  dbo.vUsuarioAll.NombreApellido AS creador,
			  dbo.ta_MetadatoDetalle.nmbre AS banco
			FROM
			  dbo.tz_PagoValeVista
			  INNER JOIN dbo.tz_TipoDocumento ON (dbo.tz_PagoValeVista.IDTipoDocumento = dbo.tz_TipoDocumento.IDTipoDocumento)
			  LEFT OUTER JOIN dbo.vUsuarioAll ON (dbo.tz_PagoValeVista.IDModificador = dbo.vUsuarioAll.idt_usrio)
			  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_PagoValeVista.IDBanco = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
			WHERE
			  dbo.tz_PagoValeVista.IDPostulacionItem = $idpostulacionitem ";
			break;
			//Pagare
			case 1442 : $sql = "SELECT 
			  z.IDPagoPagare AS id,
			  z.IDPostulacionItem,
			  z.bLegalizado,
			  CAST( z.nCuota AS CHAR(10)) + ' / ' + CAST( z.NumCuotas AS CHAR(10)) + ' <strong>/</strong> Nro. pagare: ' + z.NumPagare + ' / Vcto.: ' + CONVERT(CHAR(10),z.FechaVencimiento,103) AS detalle,
			  z.NumCuotas,
			  z.Monto AS montototal ,
			  z.nCuota,
			  z.MontoInteresCuota,
			  z.MontoCuota AS Monto,
			  CONVERT(CHAR(10),z.FechaVencimiento,103) AS FechaVencimiento ,			 
			  z.IDTipoDocumento,
			  z.IDModificador,
			  z.FechaModificacion AS creado,
			  z.CantPagos,
			  z.NroPago,
			  z.IDEmpresa,
			  z.NOC_Empresa,
			  dbo.tz_TipoDocumento.Nombre AS documento,
			  dbo.vUsuarioAll.NombreApellido AS creador
			FROM
			  dbo.tz_PagoPagare z
			  INNER JOIN dbo.tz_TipoDocumento ON (z.IDTipoDocumento = dbo.tz_TipoDocumento.IDTipoDocumento)
			  LEFT OUTER JOIN dbo.vUsuarioAll ON (z.IDModificador = dbo.vUsuarioAll.idt_usrio)
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem $opt";
			break;
			
		}
		
		$query = $this->db->query($sql);
		return $query->result_array();	
		
	}
	
	public function get_metadetallepago($idpostulacionitem,$likearray = TRUE){
		
		if(is_array($idpostulacionitem)){
			$idpostulacionitem = implode(',',$idpostulacionitem);
		}		
		
		$query = $this->db->query("SELECT 
		  z.ValorNuevo,
		  dbo.tz_Cursos.Nombre_Curso,
		  CONVERT(CHAR(10), a.FechaInicio, 105) AS FechaInicio,
		  CONVERT(CHAR(10), a.FechaTermino, 105) AS FechaTermino,
		  dbo.ta_usrio.usrio AS rut,
		  dbo.vUsuarioAll.NombreApellido,
		  z.bEstado,
		  CASE a.es_admision 
		  WHEN 1 THEN '(Cuota admision)' END AS solo_cuota,
		  dbo.tz_Aplicacion.Nombre AS programa,
		  z.IDPostulacionItem,
		  z.IDSeccion
		FROM
		  dbo.tz_PostulacionItem z
		  INNER JOIN dbo.tz_Secciones a ON (z.IDSeccion = a.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (a.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.tz_Postulacion ON (z.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.ta_usrio ON (dbo.tz_Postulacion.IDUsuario = dbo.ta_usrio.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll ON (dbo.ta_usrio.idt_usrio = dbo.vUsuarioAll.idt_usrio)
		  INNER JOIN dbo.tz_Aplicacion ON (dbo.tz_Cursos.IDAplicacion = dbo.tz_Aplicacion.IDAplicacion)
		WHERE
		  z.IDPostulacionItem IN ($idpostulacionitem)
		ORDER BY
		  CONVERT(FLOAT,a.FechaInicio ) DESC ");
		if($likearray == TRUE){
			return $query->result_array();
		}else{
			return $query->row();
		}
		
	}
	
	
	
	
}