<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_icfacturas extends CI_Model {
	
	function informe_general($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 		 
		  z.NroFactura,
		  CONVERT(CHAR(10),z.Fecha,103) AS Fecha,		 
		  SUM(z.MontoFactura) AS MontoFactura,
		  b.NombreApellido AS asistente,
		  d.usrio AS rut_alumno,
		  dbo.vUsuarioAll.NombreApellido AS nombre_alumno,
		  dbo.tz_Secciones.Codigo AS codigo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  dbo.tz_Aplicacion.Nombre AS programa,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto
		FROM
		  dbo.tz_IC_Facturas z
		  INNER JOIN dbo.tz_PostulacionItem a ON (z.IDPostulacionItem = a.IDPostulacionItem)
		  INNER JOIN dbo.vUsuarioAll b ON (z.IDCreador = b.idt_usrio)
		  INNER JOIN dbo.tz_Postulacion c ON (a.IDPostulacion = c.IDPostulacion)
		  INNER JOIN dbo.ta_usrio d ON (c.IDUsuario = d.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll ON (c.IDUsuario = dbo.vUsuarioAll.idt_usrio)
		  INNER JOIN dbo.tz_Secciones ON (a.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.tz_Aplicacion ON (c.IDAplicacion = dbo.tz_Aplicacion.IDAplicacion)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.IDTipoProyecto = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		  INNER JOIN dbo.ta_MetadatoDetalle j ON (z.idtipopago = j.IDMetadatoDetalle)
		WHERE
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' $opt
		GROUP BY		
		  z.NroFactura,
		  CONVERT(CHAR(10),z.Fecha,103),
		  b.NombreApellido,
		  d.usrio,
		  dbo.vUsuarioAll.NombreApellido,
		  dbo.tz_Secciones.Codigo,
		  dbo.tz_Cursos.Nombre_Curso,
		  dbo.tz_Aplicacion.Nombre,
		  dbo.ta_MetadatoDetalle.nmbre
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	function informe_deposito($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), z.Fecha, 103) AS Fecha,
		  z.NroFactura,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoFactura,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  a.NumeroComprobante,
		  CONVERT(CHAR(10), a.FechaDeposito, 103) AS FechaDeposito
		FROM
		  dbo.tz_IC_Facturas z
		  INNER JOIN dbo.tz_PagoDeposito a ON (z.IDPago = a.IDPagoDeposito)
		  INNER JOIN dbo.tz_PostulacionItem b ON (a.IDPostulacionItem = b.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion c ON (b.IDPostulacion = c.IDPostulacion)
		  INNER JOIN dbo.ta_usrio d ON (c.IDUsuario = d.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll e ON (c.IDUsuario = e.idt_usrio)
		  INNER JOIN dbo.tz_Secciones f ON (b.IDSeccion = f.IDSeccion)
		  INNER JOIN dbo.tz_Cursos g ON (f.IDCurso = g.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle h ON (g.IDTipoProyecto = h.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Aplicacion i ON (g.IDAplicacion = i.IDAplicacion)
		  INNER JOIN dbo.vUsuarioAll j ON (z.IDCreador = j.idt_usrio)
		WHERE
		  z.IDTipoPago = 3860 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND
		  a.IDTipoDocumento = 1 $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	function informe_pagare($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 		 
		  z.NroFactura,
		  CONVERT(CHAR(10),z.Fecha,103) AS Fecha,		 
		  SUM(z.MontoFactura) AS MontoFactura,
		  b.NombreApellido AS asistente,
		  d.usrio AS rut_alumno,
		  dbo.vUsuarioAll.NombreApellido AS nombre_alumno,
		  dbo.tz_Secciones.Codigo AS codigo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  dbo.tz_Aplicacion.Nombre AS programa,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto
		FROM
		  dbo.tz_IC_Facturas z
		  INNER JOIN dbo.tz_PostulacionItem a ON (z.IDPostulacionItem = a.IDPostulacionItem)
		  INNER JOIN dbo.vUsuarioAll b ON (z.IDCreador = b.idt_usrio)
		  INNER JOIN dbo.tz_Postulacion c ON (a.IDPostulacion = c.IDPostulacion)
		  INNER JOIN dbo.ta_usrio d ON (c.IDUsuario = d.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll ON (c.IDUsuario = dbo.vUsuarioAll.idt_usrio)
		  INNER JOIN dbo.tz_Secciones ON (a.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.tz_Aplicacion ON (c.IDAplicacion = dbo.tz_Aplicacion.IDAplicacion)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.IDTipoProyecto = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		  INNER JOIN dbo.ta_MetadatoDetalle j ON (z.idtipopago = j.IDMetadatoDetalle)
		WHERE
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND
		  z.IDTipoPago = 1442 $opt
		GROUP BY		
		  z.NroFactura,
		  CONVERT(CHAR(10),z.Fecha,103),
		  b.NombreApellido,
		  d.usrio,
		  dbo.vUsuarioAll.NombreApellido,
		  dbo.tz_Secciones.Codigo,
		  dbo.tz_Cursos.Nombre_Curso,
		  dbo.tz_Aplicacion.Nombre,
		  dbo.ta_MetadatoDetalle.nmbre
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}	
	
	function informe_dia($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND a.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 
		  a.NroFactura,
		  CONVERT(CHAR(10),a.Fecha,103) AS Fecha ,
		  a.MontoFactura,
		  a.idtipopago,
		  dbo.ta_usrio.usrio AS rut_alumno,
		  dbo.vUsuarioAll.NombreApellido AS nombre_alumno,
		  dbo.tz_Secciones.Codigo AS codigo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto,
		  c.nmbre AS banco,
		  dbo.tz_Aplicacion.Nombre AS programa,
		  b.NombreApellido AS asistente,
		  a.IDCreador,
		  z.IDPostulacionItem,
		  z.TipoPagoCheque,
		  z.Banco,
		  z.CuentaCorriente,
		  z.NSerie,
		  z.Monto,		
		  z.NumCheques,
		  z.IDTipoDocumento,
		  z.nCheque,
		  z.IDModificador,
		  z.FechaModificacion,
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
		  INNER JOIN dbo.tz_IC_Facturas a ON (z.IDPostulacionItem = a.IDPostulacionItem)
		  INNER JOIN dbo.tz_PostulacionItem ON (z.IDPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.ta_usrio ON (dbo.tz_Postulacion.IDUsuario = dbo.ta_usrio.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll ON (dbo.ta_usrio.idt_usrio = dbo.vUsuarioAll.idt_usrio)
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.IDTipoProyecto = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle c ON (z.Banco = c.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Aplicacion ON (dbo.tz_Postulacion.IDAplicacion = dbo.tz_Aplicacion.IDAplicacion)
		  LEFT OUTER JOIN dbo.vUsuarioAll b ON (a.IDCreador = b.idt_usrio)
		WHERE
		  DATEADD(dd, 0, DATEDIFF(dd, 0, a.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, a.Fecha)) <= '$hasta' AND 
		  a.IDTipoPago = 1439 AND 
		  z.IDTipoDocumento = 0 AND
		  z.TipoPagoCheque = 1 $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	function informe_fecha($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), z.Fecha, 103) AS Fecha,
		  z.NroFactura,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoFactura,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  k.nmbre AS banco,
		  CONVERT(CHAR(10), a.Fecha, 103) AS vcto,
		  a.NSerie
		FROM
		  dbo.tz_IC_Facturas z
		  INNER JOIN dbo.tz_PagoCheque a ON (z.IDPago = a.IDPagoCheque)
		  INNER JOIN dbo.tz_PostulacionItem b ON (a.IDPostulacionItem = b.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion c ON (b.IDPostulacion = c.IDPostulacion)
		  INNER JOIN dbo.ta_usrio d ON (c.IDUsuario = d.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll e ON (c.IDUsuario = e.idt_usrio)
		  INNER JOIN dbo.tz_Secciones f ON (b.IDSeccion = f.IDSeccion)
		  INNER JOIN dbo.tz_Cursos g ON (f.IDCurso = g.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle h ON (g.IDTipoProyecto = h.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Aplicacion i ON (g.IDAplicacion = i.IDAplicacion)
		  INNER JOIN dbo.vUsuarioAll j ON (z.IDCreador = j.idt_usrio)
		  INNER JOIN dbo.ta_MetadatoDetalle k ON (a.Banco = k.IDMetadatoDetalle)
		WHERE
		  z.IDTipoPago = 1440 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	
	function informe_efectivo($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND a.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 
		  z.IDPagoEfectivo,
		  z.IDPostulacionItem,
		  z.Monto,
		  z.IDTipoDocumento,
		  z.IDModificador,
		  z.IDFechaModificacion,
		  z.CantPagos,
		  z.NroPago,
		  z.IDEmpresa,
		  z.NOC_Empresa,
		  a.NroFactura,
		  CONVERT(CHAR(10),a.Fecha,103) AS Fecha ,
		  a.MontoFactura,
		  a.idtipopago,
		  dbo.ta_usrio.usrio AS rut_alumno,
		  dbo.vUsuarioAll.NombreApellido AS nombre_alumno,
		  dbo.tz_Secciones.Codigo AS codigo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto,
		  dbo.tz_Aplicacion.Nombre AS programa,
		  b.NombreApellido AS asistente
		FROM
		  dbo.tz_PagoEfectivo z
		  INNER JOIN dbo.tz_IC_Facturas a ON (z.IDPostulacionItem = a.IDPostulacionItem)
		  INNER JOIN dbo.tz_PostulacionItem ON (z.IDPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.ta_usrio ON (dbo.tz_Postulacion.IDUsuario = dbo.ta_usrio.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll ON (dbo.ta_usrio.idt_usrio = dbo.vUsuarioAll.idt_usrio)
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.IDTipoProyecto = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Aplicacion ON (dbo.tz_Postulacion.IDAplicacion = dbo.tz_Aplicacion.IDAplicacion)
		  LEFT OUTER JOIN dbo.vUsuarioAll b ON (a.IDCreador = b.idt_usrio)
		WHERE
		  DATEADD(dd, 0, DATEDIFF(dd, 0, a.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, a.Fecha)) <= '$hasta' AND 
		  a.IDTipoPago = 1438 AND 
		  z.IDTipoDocumento = 0 $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	function informe_debito($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), z.Fecha, 103) AS Fecha,
		  z.NroFactura,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoFactura,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  dbo.ta_MetadatoDetalle.nmbre AS BancoEmisor,
		  a.CodigoTransaccion,
		  a.Cuatroultimosdigitos,
		  CONVERT(CHAR(10), a.FechaTransaccion, 103) AS FechaTransaccion		  
		FROM
		  dbo.tz_IC_Facturas z
		  INNER JOIN dbo.tz_PagoDebito a ON (z.IDPago = a.IDPagoDebito)
		  INNER JOIN dbo.tz_PostulacionItem b ON (a.IDPostulacionItem = b.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion c ON (b.IDPostulacion = c.IDPostulacion)
		  INNER JOIN dbo.ta_usrio d ON (c.IDUsuario = d.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll e ON (c.IDUsuario = e.idt_usrio)
		  INNER JOIN dbo.tz_Secciones f ON (b.IDSeccion = f.IDSeccion)
		  INNER JOIN dbo.tz_Cursos g ON (f.IDCurso = g.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle h ON (g.IDTipoProyecto = h.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Aplicacion i ON (g.IDAplicacion = i.IDAplicacion)
		  INNER JOIN dbo.vUsuarioAll j ON (z.IDCreador = j.idt_usrio)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (a.BancoEmisor = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		WHERE
		  z.IDTipoPago = 3805 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND 
		  a.IDTipoDocumento = 1 $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	function informe_valevista($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), z.Fecha, 103) AS Fecha,
		  z.NroFactura,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoFactura,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  k.nmbre AS banco,
		  a.NumeroValeVista,
		  CONVERT(CHAR(10), a.FechaValeVista, 103) AS FechaValeVista  
		FROM
		  dbo.tz_IC_Facturas z
		  INNER JOIN dbo.tz_PagoValeVista a ON (z.IDPago = a.IDPagoValeVista)
		  INNER JOIN dbo.tz_PostulacionItem b ON (a.IDPostulacionItem = b.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion c ON (b.IDPostulacion = c.IDPostulacion)
		  INNER JOIN dbo.ta_usrio d ON (c.IDUsuario = d.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll e ON (c.IDUsuario = e.idt_usrio)
		  INNER JOIN dbo.tz_Secciones f ON (b.IDSeccion = f.IDSeccion)
		  INNER JOIN dbo.tz_Cursos g ON (f.IDCurso = g.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle h ON (g.IDTipoProyecto = h.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Aplicacion i ON (g.IDAplicacion = i.IDAplicacion)
		  INNER JOIN dbo.vUsuarioAll j ON (z.IDCreador = j.idt_usrio)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle k ON (a.IDBanco = k.IDMetadatoDetalle)
		WHERE
		  z.IDTipoPago = 3861 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND 
		  a.IDTipoDocumento = 1 $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	function informe_transferencia($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), z.Fecha, 103) AS Fecha,
		  z.NroFactura,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoFactura,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  a.NumeroTransferencia,
		  k.nmbre AS banco
		FROM
		  dbo.tz_IC_Facturas z
		  INNER JOIN dbo.tz_PagoTransferencia a ON (z.IDPago = a.IDPagoTransferencia)
		  INNER JOIN dbo.tz_PostulacionItem b ON (a.IDPostulacionItem = b.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion c ON (b.IDPostulacion = c.IDPostulacion)
		  INNER JOIN dbo.ta_usrio d ON (c.IDUsuario = d.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll e ON (c.IDUsuario = e.idt_usrio)
		  INNER JOIN dbo.tz_Secciones f ON (b.IDSeccion = f.IDSeccion)
		  INNER JOIN dbo.tz_Cursos g ON (f.IDCurso = g.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle h ON (g.IDTipoProyecto = h.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Aplicacion i ON (g.IDAplicacion = i.IDAplicacion)
		  INNER JOIN dbo.vUsuarioAll j ON (z.IDCreador = j.idt_usrio)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle k ON (a.IDBanco = k.IDMetadatoDetalle)
		WHERE
		  z.IDTipoPago = 3862 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND 
		  a.IDTipoDocumento = 1 $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}	
	
	function informe_tarjeta($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), z.Fecha, 103) AS Fecha,
		  z.NroFactura,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoFactura,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  dbo.ta_MetadatoDetalle.nmbre AS tarjeta,
		  a.NumTarjeta4,
		  a.NumTransaccion
		FROM
		  dbo.tz_IC_Facturas z
		  INNER JOIN dbo.tz_PagoTarjeta a ON (z.IDPago = a.IDPagoTarjeta)
		  INNER JOIN dbo.tz_PostulacionItem b ON (a.IDPostulacionItem = b.IDPostulacionItem)
		  INNER JOIN dbo.tz_Postulacion c ON (b.IDPostulacion = c.IDPostulacion)
		  INNER JOIN dbo.ta_usrio d ON (c.IDUsuario = d.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll e ON (c.IDUsuario = e.idt_usrio)
		  INNER JOIN dbo.tz_Secciones f ON (b.IDSeccion = f.IDSeccion)
		  INNER JOIN dbo.tz_Cursos g ON (f.IDCurso = g.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle h ON (g.IDTipoProyecto = h.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Aplicacion i ON (g.IDAplicacion = i.IDAplicacion)
		  INNER JOIN dbo.vUsuarioAll j ON (z.IDCreador = j.idt_usrio)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (a.TipoPagoTarjeta = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		WHERE
		  z.IDTipoPago = 1441 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND 
		  a.IDTipoDocumento = 1 $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	function update_factura($idpostulacionitem,$tipo_pago,$numero_boleta,$id_usuario){
		
		$none = array(1442);
		if(in_array($tipo_pago,$none)){
			return FALSE;
		}	
			  
		switch($tipo_pago){
			case 1438 : $query = $this->db->query("SELECT 
			  z.IDPagoEfectivo AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoEfectivo z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 1");			
			break;
			case 1439 : $query = $this->db->query("
			SELECT 
			  z.IDPagoCheque AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoCheque z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND 
			  z.TipoPagoCheque = 1 AND
			  z.IDTipoDocumento = 1");			
			break;
			case 1440 : $query = $this->db->query("
			SELECT 
			  z.IDPagoCheque AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoCheque z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND 
			  z.TipoPagoCheque = 2 AND
			  z.IDTipoDocumento = 1");			
			break;
			case 1441 : $query = $this->db->query("SELECT 
			  z.IDPagoTarjeta AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoTarjeta z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 1");			
			break;
			case 3805 : $query = $this->db->query("SELECT 
			  z.IDPagoDebito AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoDebito z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 1");			
			break;
			case 3860 : $query = $this->db->query("SELECT 
			  z.IDPagoDeposito AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoDeposito z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 1");			
			break;
			case 3861 : $query = $this->db->query("SELECT 
			  z.IDPagoValeVista AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoValeVista z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 1");			
			break;
			case 3862 : $query = $this->db->query("SELECT 
			  z.IDPagoTransferencia AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoTransferencia z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 1");			
			break;
			case 3987 : $query = $this->db->query("SELECT 
			  z.IDPagoWebPay AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoWebPay z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 1");			
			break;
			case 3811 : $query = $this->db->query("SELECT 
			  z.IDPagoPorFacturar AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_pagoPorFacturar z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 1");			
			break;
			
		}
		
		$resp = $query->result_array();
		$c = 1;
		foreach($resp as $value ){
			
			$query = $this->db->query("SELECT 
			  dbo.tz_IC_Facturas.IDFactura
			FROM
			  dbo.tz_IC_Facturas
			WHERE
			  dbo.tz_IC_Facturas.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_IC_Facturas.IDPago = ".$value['id']." AND 
			  dbo.tz_IC_Facturas.IDTipoPago = $tipo_pago ");
			$respuesta = $query->row();
			if(is_numeric($respuesta->IDFactura)){
								
				$this->db->query("UPDATE 
				  dbo.tz_IC_Facturas
				SET
				  id_modificador = $id_usuario,
				  modificado = GETDATE(),
				  NroFactura = ".$this->db->escape($numero_boleta)."
				WHERE
				  dbo.tz_IC_Facturas.IDFactura = ".$respuesta->IDFactura);
				  
			}else{
			
				$this->db->query("INSERT INTO
				  dbo.tz_IC_Facturas(
				  IDPostulacionItem,
				  NroFactura,
				  Fecha,
				  IDCreador,
				  MontoFactura,
				  IDPago,
				  Cuota,
				  IDTipoPago,
				  IDEmpresa)
				VALUES(
				  $idpostulacionitem,
				  ".$this->db->escape($numero_boleta).",
				  GETDATE(),
				  $id_usuario,
				  ".$value['monto'].",
				  ".$value['id'].",
				  ".$value['cuota'].",
				  $tipo_pago,
				  NULL)");
			}
		}	
		
	}
	
	function get_numero_factura($idpostulacionitem){
		
		$query = $this->db->query("SELECT 
		  z.NroFactura
		FROM
		  dbo.tz_IC_Facturas z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem
		GROUP BY
		  z.NroFactura");
		return $query->row();	
		
	}	
	
}