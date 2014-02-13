<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_icboletas extends CI_Model {
	
	function informe_finanzas($desde,$hasta,$matriculador = NULL){
		
		if(is_numeric($matriculador)){
			$opt = " AND z.IDCreador = $matriculador";	
		}
		
		$query = $this->db->query("SELECT 
		  z.NroBoleta,
		  CONVERT(CHAR(10),z.Fecha,105) AS Fecha,
		  SUM(z.MontoBoleta) AS MontoBoleta,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  dbo.vUsuarioAll.NombreApellido AS alumno,
		  dbo.ta_usrio.usrio AS rut,
		  CONVERT(CHAR(10),dbo.tz_Secciones.FechaInicio,105) AS FechaInicio,
		  CONVERT(CHAR(10),dbo.tz_Secciones.FechaTermino,105) AS FechaTermino,	
		  h.NombreApellido AS matriculador,
		  dbo.fn_formas_pago_texto(z.IDPostulacionItem) AS formaspago
		FROM
		  dbo.tz_IC_Boletas z
		  INNER JOIN dbo.tz_PostulacionItem ON (z.IDPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem)
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.IDTipoProyecto = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.vUsuarioAll ON (dbo.tz_Postulacion.IDUsuario = dbo.vUsuarioAll.idt_usrio)
		  INNER JOIN dbo.ta_usrio ON (dbo.tz_Postulacion.IDUsuario = dbo.ta_usrio.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll h ON (z.IDCreador = h.idt_usrio)
		WHERE
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' $opt
		GROUP BY		  		 
		  z.NroBoleta,
		  CONVERT(CHAR(10),z.Fecha,105),
		  dbo.ta_MetadatoDetalle.nmbre,
		  dbo.tz_Cursos.Nombre_Curso,
		  dbo.vUsuarioAll.NombreApellido,
		  dbo.ta_usrio.usrio,
		  dbo.tz_Secciones.FechaInicio,
		  dbo.tz_Secciones.FechaTermino,
		  h.NombreApellido,
		  z.IDPostulacionItem
		ORDER BY
		  z.NroBoleta");
		return $query->result_array();	
		
		
	}
	
	function informe_finanzas_fecha($desde,$hasta,$matriculador = NULL){
		
		if(is_numeric($matriculador)){
			$opt = " AND z.IDCreador = $matriculador";	
		}
		
		$query = $this->db->query("SELECT 
		  z.NroBoleta,
		  CONVERT(CHAR(10), z.Fecha, 105) AS Fecha,
		  SUM(z.MontoBoleta) AS MontoBoleta,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto,
		  CONVERT(CHAR(10),dbo.tz_PagoCheque.Fecha,103) AS vcto,
		  dbo.tz_PagoCheque.Nombre_Girador,
		  dbo.tz_PagoCheque.rut_girador,
		  a.nmbre AS banco,
		  dbo.tz_PagoCheque.NSerie,
		  dbo.tz_PagoCheque.telefono_girador
		FROM
		  dbo.tz_IC_Boletas z
		  INNER JOIN dbo.tz_PostulacionItem ON (z.IDPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem)
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.IDTipoProyecto = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_PagoCheque ON (z.IDPago = dbo.tz_PagoCheque.IDPagoCheque)
		  INNER JOIN dbo.ta_MetadatoDetalle a ON (dbo.tz_PagoCheque.Banco = a.IDMetadatoDetalle)
		WHERE
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND 
		  z.idtipopago = 1440 $opt
		GROUP BY
		  z.NroBoleta,
		  CONVERT(CHAR(10), z.Fecha, 105),
		  dbo.ta_MetadatoDetalle.nmbre,
		  dbo.tz_PagoCheque.Fecha,
		  dbo.tz_PagoCheque.Nombre_Girador,
		  dbo.tz_PagoCheque.rut_girador,
		  a.nmbre,
		  dbo.tz_PagoCheque.NSerie,
		  dbo.tz_PagoCheque.telefono_girador
		ORDER BY
		  z.NroBoleta");
		return $query->result_array();	
		
		
	}
	
	function update_boleta_pagare($idpostulacionitem,$id_pago,$numero_boleta,$id_usuario,$monto_boleta,$fecha_boleta){
		
		$monto_boleta = filter_var($str, FILTER_SANITIZE_NUMBER_INT);
		
		if(!is_numeric($id_pago)){			
			return FALSE;	
		}
		
		$query = $this->db->query("SELECT 
		  z.IDPago,
		  z.IDPostulacionItem
		FROM
		  dbo.tz_IC_Boletas z
		WHERE
		  z.IDPago = $id_pago AND 
		  z.IDPostulacionItem = $idpostulacionitem");
		if(count($query->result_array()) > 0){			
			$this->db->query("UPDATE 
			  dbo.tz_IC_Boletas
			SET	
			  Fecha = ".$this->db->escape($fecha_boleta).",		  
			  id_modificador = $id_usuario,
			  modificado = GETDATE(),
			  NroBoleta = ".$this->db->escape($numero_boleta)."
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_IC_Boletas.IDPago = $id_pago AND 
			  dbo.tz_IC_Boletas.idtipopago = 1442");
			return 'Boleta modificada con exito.';
		}else{
			
			$query = $this->db->query("SELECT 
			  dbo.tz_PagoPagare.nCuota,
			  dbo.tz_PagoPagare.MontoCuota,
			  dbo.tz_PagoPagare.NumCuotas,
			  dbo.tz_PagoPagare.IDPagoPagare
			FROM
			  dbo.tz_PagoPagare
			WHERE
			  dbo.tz_PagoPagare.IDPagoPagare = $id_pago ");
			$dato = $query->row();
			
			$this->db->query("INSERT INTO
			  dbo.tz_IC_Boletas(
			  IDPago,
			  IDPostulacionItem,
			  NroBoleta,
			  Fecha,
			  IDCreador,
			  MontoBoleta,
			  Cuota,
			  idtipopago,
			  IDEmpresa)
			VALUES(
			  $id_pago,
			  $idpostulacionitem,
			  ".$this->db->escape($numero_boleta).",
			  ".$this->db->escape($fecha_boleta).",
			  $id_usuario,
			  ".$dato->MontoCuota.",
			  ".$dato->nCuota.",
			  1442,
			  NULL)");
			  return 'Boleta agregada con exito.';			
		}		
	}
	
	function get_imprimir($idpostulacionitem,$nro_boleta = NULL){
		
		if($nro_boleta){
			$opt = "AND dbo.tz_IC_Boletas.NroBoleta = ".$this->db->escape($nro_boleta)." ";	
		}
		
		$this->db->query("SET LANGUAGE Spanish");
		$query = $this->db->query("		
		SELECT DISTINCT
		  z.IDSeccion,
		  dbo.tz_Cursos.Nombre_Curso,
		  CONVERT(CHAR(10),dbo.tz_Secciones.FechaInicio,105) AS FechaInicio,
		  dbo.ta_usrio.usrio AS rut,
		  dbo.vUsuarioAll.NombreApellido,	
		  CONVERT(CHAR(10),dbo.tz_Secciones.FechaTermino,105) AS FechaTermino,
		  z.IDPostulacionItem,
		  dbo.tz_IC_Boletas.NroBoleta,	
		  a.NombreApellido AS asistente,
		  DAY(dbo.tz_IC_Boletas.Fecha) AS dia_boleta,
		  DATENAME(MONTH,dbo.tz_IC_Boletas.Fecha) AS mes_boleta,
		  YEAR(dbo.tz_IC_Boletas.Fecha) AS anio_boleta,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto
		FROM
		  dbo.tz_PostulacionItem z
		  INNER JOIN dbo.tz_Postulacion ON (z.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_Secciones ON (z.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.ta_usrio ON (dbo.tz_Postulacion.IDUsuario = dbo.ta_usrio.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll ON (dbo.tz_Postulacion.IDUsuario = dbo.vUsuarioAll.idt_usrio)
		  INNER JOIN dbo.tz_IC_Boletas ON (z.IDPostulacionItem = dbo.tz_IC_Boletas.IDPostulacionItem)
		  LEFT OUTER JOIN dbo.vUsuarioAll a ON (dbo.tz_IC_Boletas.IDCreador = a.idt_usrio)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.IDTipoProyecto = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem $opt ");
		return $query->row();
		
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
		  a.NroBoleta,
		  CONVERT(CHAR(10),a.Fecha,103) AS Fecha ,
		  a.MontoBoleta,
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
		  INNER JOIN dbo.tz_IC_Boletas a ON (z.IDPostulacionItem = a.IDPostulacionItem)
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
		  a.idtipopago = 1438 AND 
		  z.IDTipoDocumento = 0 $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	function informe_general($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 		 
		  z.NroBoleta,
		  CONVERT(CHAR(10),z.Fecha,103) AS Fecha,		 
		  SUM(z.MontoBoleta) AS MontoBoleta,
		  b.NombreApellido AS asistente,
		  d.usrio AS rut_alumno,
		  dbo.vUsuarioAll.NombreApellido AS nombre_alumno,
		  dbo.tz_Secciones.Codigo AS codigo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  dbo.tz_Aplicacion.Nombre AS programa,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto
		FROM
		  dbo.tz_IC_Boletas z
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
		  z.NroBoleta,
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
	
	function informe_pagare($id_usuario = NULL,$desde,$hasta){
		
		if($id_usuario){
			$opt = " AND z.IDCreador = $id_usuario";	
		}
		
		$query = $this->db->query("SELECT 		 
		  z.NroBoleta,
		  CONVERT(CHAR(10),z.Fecha,103) AS Fecha,		 
		  SUM(z.MontoBoleta) AS MontoBoleta,
		  b.NombreApellido AS asistente,
		  d.usrio AS rut_alumno,
		  dbo.vUsuarioAll.NombreApellido AS nombre_alumno,
		  dbo.tz_Secciones.Codigo AS codigo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  dbo.tz_Aplicacion.Nombre AS programa,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto
		FROM
		  dbo.tz_IC_Boletas z
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
		  z.idtipopago = 1442 $opt
		GROUP BY		
		  z.NroBoleta,
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
		  a.NroBoleta,
		  CONVERT(CHAR(10),a.Fecha,103) AS Fecha ,
		  a.MontoBoleta,
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
		  INNER JOIN dbo.tz_IC_Boletas a ON (z.IDPostulacionItem = a.IDPostulacionItem)
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
		  a.idtipopago = 1439 AND 
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
		  z.NroBoleta,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoBoleta,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  k.nmbre AS banco,
		  CONVERT(CHAR(10), a.Fecha, 103) AS vcto,
		  a.NSerie
		FROM
		  dbo.tz_IC_Boletas z
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
		  z.idtipopago = 1440 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' $opt
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
		  z.NroBoleta,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoBoleta,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  a.NumeroComprobante,
		  CONVERT(CHAR(10), a.FechaDeposito, 103) AS FechaDeposito
		FROM
		  dbo.tz_IC_Boletas z
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
		  z.idtipopago = 3860 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND
		  a.IDTipoDocumento = 0 $opt
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
		  z.NroBoleta,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoBoleta,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  dbo.ta_MetadatoDetalle.nmbre AS tarjeta,
		  a.NumTarjeta4,
		  a.NumTransaccion
		FROM
		  dbo.tz_IC_Boletas z
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
		  z.idtipopago = 1441 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND 
		  a.IDTipoDocumento = 0 $opt
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
		  z.NroBoleta,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoBoleta,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  dbo.ta_MetadatoDetalle.nmbre AS BancoEmisor,
		  a.CodigoTransaccion,
		  a.Cuatroultimosdigitos,
		  CONVERT(CHAR(10), a.FechaTransaccion, 103) AS FechaTransaccion		  
		FROM
		  dbo.tz_IC_Boletas z
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
		  z.idtipopago = 3805 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND 
		  a.IDTipoDocumento = 0 $opt
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
		  z.NroBoleta,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoBoleta,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  a.NumeroTransferencia,
		  k.nmbre AS banco
		FROM
		  dbo.tz_IC_Boletas z
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
		  z.idtipopago = 3862 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND 
		  a.IDTipoDocumento = 0 $opt
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
		  z.NroBoleta,
		  z.idtipopago,
		  d.usrio AS rut_alumno,
		  e.NombreApellido AS nombre_alumno,
		  f.Codigo AS codigo,
		  h.nmbre AS proyecto,
		  i.Nombre AS programa,
		  z.MontoBoleta,
		  j.NombreApellido AS asistente,
		  g.Nombre_Curso AS actividad,
		  k.nmbre AS banco,
		  a.NumeroValeVista,
		  CONVERT(CHAR(10), a.FechaValeVista, 103) AS FechaValeVista  
		FROM
		  dbo.tz_IC_Boletas z
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
		  z.idtipopago = 3861 AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.Fecha)) <= '$hasta' AND 
		  a.IDTipoDocumento = 0 $opt
		ORDER BY
		  proyecto");
		return $query->result_array();
			
	}
	
	function update_boleta($idpostulacionitem,$tipo_pago,$numero_boleta,$id_usuario,$fecha_boleta){
		
		$none = array(1442,3811);
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
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoEfectivo z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 0");			
			break;
			case 1439 : $query = $this->db->query("
			SELECT 
			  z.IDPagoCheque AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoCheque z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND 
			  z.TipoPagoCheque = 1 AND
			  z.IDTipoDocumento = 0");			
			break;
			case 1440 : $query = $this->db->query("
			SELECT 
			  z.IDPagoCheque AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoCheque z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND 
			  z.TipoPagoCheque = 2 AND
			  z.IDTipoDocumento = 0");			
			break;
			case 1441 : $query = $this->db->query("SELECT 
			  z.IDPagoTarjeta AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoTarjeta z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 0");			
			break;
			case 3805 : $query = $this->db->query("SELECT 
			  z.IDPagoDebito AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoDebito z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 0");			
			break;
			case 3860 : $query = $this->db->query("SELECT 
			  z.IDPagoDeposito AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoDeposito z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 0");			
			break;
			case 3861 : $query = $this->db->query("SELECT 
			  z.IDPagoValeVista AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoValeVista z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 0");			
			break;
			case 3862 : $query = $this->db->query("SELECT 
			  z.IDPagoTransferencia AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoTransferencia z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 0");			
			break;
			case 3987 : $query = $this->db->query("SELECT 
			  z.IDPagoWebPay AS id,
			  z.Monto AS monto,
			  (SELECT 
			  COUNT(*) + 1 AS c
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
  			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ) AS cuota
			FROM
			  dbo.tz_PagoWebPay z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem AND
			  z.IDTipoDocumento = 0");			
			break;
			
		}		
		$resp = $query->result_array();
		$c = 1;
		foreach($resp as $value ){
			$query = $this->db->query("SELECT 
			  dbo.tz_IC_Boletas.IDBoletas
			FROM
			  dbo.tz_IC_Boletas
			WHERE
			  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem AND 
			  dbo.tz_IC_Boletas.IDPago = ".$value['id']." AND 
			  dbo.tz_IC_Boletas.idtipopago = $tipo_pago ");
			$respuesta = $query->row();
			if(is_numeric($respuesta->IDBoletas)){				
				$this->db->query("UPDATE 
				  dbo.tz_IC_Boletas
				SET
				  Fecha = ".$this->db->escape($fecha_boleta).",
				  id_modificador = $id_usuario,
				  modificado = GETDATE(),
				  NroBoleta = ".$this->db->escape($numero_boleta)."
				WHERE
				  dbo.tz_IC_Boletas.IDBoletas = ".$respuesta->IDBoletas);
			}else{
			
				$this->db->query("INSERT INTO
				  dbo.tz_IC_Boletas(
				  IDPostulacionItem,
				  NroBoleta,
				  Fecha,
				  IDCreador,
				  MontoBoleta,
				  IDPago,
				  Cuota,
				  idtipopago,
				  IDEmpresa)
				VALUES(
				  $idpostulacionitem,
				  ".$this->db->escape($numero_boleta).",
				  ".$this->db->escape($fecha_boleta).",
				  $id_usuario,
				  ".$value['monto'].",
				  ".$value['id'].",
				  ".$value['cuota'].",
				  $tipo_pago,
				  NULL)");
			}
		}
		return TRUE;	
		
	}
	
	function get_pagos($idpostulacionitem,$desde,$hasta,$id_usuario,$tipo_pago){
		$query = $this->db->query("");
		return $query->result_array();	
	}
	
	function get_numero_boleta($idpostulacionitem){
		
		$query = $this->db->query("SELECT TOP 1 
		  dbo.tz_IC_Boletas.NroBoleta,
		  CONVERT(CHAR(10), dbo.tz_IC_Boletas.Fecha, 103) AS Fecha
		FROM
		  dbo.tz_IC_Boletas
		WHERE
		  dbo.tz_IC_Boletas.IDPostulacionItem = $idpostulacionitem
		GROUP BY
		  dbo.tz_IC_Boletas.NroBoleta,
		  CONVERT(CHAR(10), dbo.tz_IC_Boletas.Fecha, 103)");
		return $query->row();	
		
	}	
	
}