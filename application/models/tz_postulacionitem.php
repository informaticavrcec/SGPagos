<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_postulacionitem extends CI_Model {
	
	public function get_estado_alumno($idpostulacionitem){
		$query = $this->db->query("SELECT 
		  z.bEstado AS estado,
		  dbo.tz_EstadoPostulante.Nombre 
		FROM
		  dbo.tz_PostulacionItem z
		  INNER JOIN dbo.tz_EstadoPostulante ON (z.bEstado = dbo.tz_EstadoPostulante.IDEstado)
		WHERE
		  z.IDPostulacionItem IN ($idpostulacionitem)");
		return $query->row();	
	}
	
	public function get_comentario($idpostulacionitem){
		$query = $this->db->query("SELECT 
		  z.fecha_creacion,
		  a.NombreApellido,
		  z.observacion
		FROM
		  dbo.tz_reservas z
		  LEFT OUTER JOIN dbo.vUsuarioAll a ON (z.id_usuario = a.idt_usrio)
		WHERE
		  z.idpostulacionitem = $idpostulacionitem
		ORDER BY
		  CONVERT(FLOAT, z.fecha_creacion) DESC");
		return $query->result_array();	
	}
	
	public function get_actividades_estado($id_usuario,$estados){
		$query = $this->db->query("SELECT 
		  dbo.tz_Postulacion.IDUsuario,
		  dbo.tz_PostulacionItem.ValorNuevo,
		  dbo.tz_PostulacionItem.bEstado,
		  dbo.tz_PostulacionItem.IDSeccion,
		  dbo.tz_Cursos.Nombre_Curso,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaInicio, 103) AS FechaInicio,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaTermino, 103) AS FechaTermino,
		  dbo.tz_EstadoPostulante.Nombre AS estado,
		  dbo.tz_PostulacionItem.IDPostulacionItem,
		  (SELECT dbo.tz_IC_Boletas.NroBoleta FROM dbo.tz_IC_Boletas WHERE dbo.tz_IC_Boletas.IDPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem AND dbo.tz_IC_Boletas.idtipopago <> 1442 GROUP BY dbo.tz_IC_Boletas.NroBoleta) AS NroBoleta,
          (SELECT dbo.tz_IC_Facturas.NroFactura FROM dbo.tz_IC_Facturas WHERE dbo.tz_IC_Facturas.IDPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem AND dbo.tz_IC_Facturas.idtipopago <> 1442 GROUP BY dbo.tz_IC_Facturas.NroFactura) AS NroFactura
		FROM
		  dbo.tz_PostulacionItem
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.tz_EstadoPostulante ON (dbo.tz_PostulacionItem.bEstado = dbo.tz_EstadoPostulante.IDEstado)		  
		WHERE
		  dbo.tz_Postulacion.IDUsuario = $id_usuario AND 
		  dbo.tz_PostulacionItem.bEstado IN ($estados)
		ORDER BY
		  CONVERT(FLOAT,dbo.tz_Secciones.FechaInicio) DESC");
		return $query->result_array();	
	}
	
	public function get_actividades_asignarboleta($id_usuario){
		$query = $this->db->query("SELECT 
		  dbo.tz_PostulacionItem.ValorNuevo,
		  dbo.tz_PostulacionItem.IDSeccion,
		  dbo.tz_Cursos.Nombre_Curso,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaInicio, 103) AS FechaInicio,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaTermino, 103) AS FechaTermino,
		  dbo.tz_EstadoPostulante.Nombre AS estado,
		  dbo.tz_PostulacionItem.IDPostulacionItem
		FROM
		  dbo.tz_PostulacionItem
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.tz_EstadoPostulante ON (dbo.tz_PostulacionItem.bEstado = dbo.tz_EstadoPostulante.IDEstado)
		WHERE
		  dbo.tz_Postulacion.IDUsuario = $id_usuario AND 
		  dbo.tz_PostulacionItem.bEstado IN (3,12)
		ORDER BY
		  CONVERT(FLOAT,dbo.tz_Secciones.FechaInicio) DESC");
		return $query->result_array();	
	}
	
	function get_pagos_por_facturar($id_usrio){
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaInicio, 105) AS FechaInicio,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaTermino, 105) AS FechaTermino,
		  dbo.tz_Postulacion.IDUsuario,
		  dbo.tz_PostulacionItem.IDPostulacionItem,
		  dbo.tz_PostulacionItem.bEstado,
		  dbo.tz_EstadoPostulante.Nombre AS estado,
		  dbo.tz_PostulacionItem.ValorNuevo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  CASE dbo.tz_Secciones.es_admision
			WHEN 1
			  THEN '(Cuota admision)'
		  END AS solo_cuota,
		  dbo.ta_MetadatoDetalle.nmbre AS tipo_actividad,
		  CASE 
			WHEN dbo.tz_Secciones.FechaInicio <= GETDATE()
			  THEN '1'
		  END AS plazo,
		  (SELECT count(*) AS c FROM dbo.tz_reservas z WHERE z.idpostulacionitem = dbo.tz_PostulacionItem.IDPostulacionItem) AS comentarios,
		  dbo.tz_PostulacionItem.IDSeccion,
		  (dbo.tz_Secciones.Vacante - (SELECT count(*) AS c FROM dbo.tz_PostulacionItem x WHERE x.bEstado IN(3, 12) AND x.IDSeccion = dbo.tz_PostulacionItem.IDSeccion)) AS cupos,
		  dbo.tz_PostulacionItem_TipoPago.IDTipoPago,
		  dbo.tz_pagoPorFacturar.Monto,
		  dbo.tz_EmpresaRegistrada.RUT AS rut_empresa,
		  dbo.tz_EmpresaRegistrada.RSocial AS razon_empresa,
		  dbo.tz_Otic.Nombre AS razon_otic,
		  dbo.tz_Otic.RUT AS rut_otic
		FROM
		  dbo.tz_PostulacionItem
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_EstadoPostulante ON (dbo.tz_PostulacionItem.bEstado = dbo.tz_EstadoPostulante.IDEstado)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.Tipo = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_PostulacionItem_TipoPago ON (dbo.tz_PostulacionItem.IDPostulacionItem = dbo.tz_PostulacionItem_TipoPago.IDPostulacionItem)
		  LEFT OUTER JOIN dbo.tz_pagoPorFacturar ON (dbo.tz_PostulacionItem_TipoPago.IDPostulacionItem = dbo.tz_pagoPorFacturar.IDPostulacionItem)
		  LEFT OUTER JOIN dbo.tz_EmpresaRegistrada ON (dbo.tz_pagoPorFacturar.IDEmpresa = dbo.tz_EmpresaRegistrada.IDEmpRegistrada)
  		  LEFT OUTER JOIN dbo.tz_Otic ON (dbo.tz_pagoPorFacturar.IDOTIC = dbo.tz_Otic.IDOtic)
		WHERE
		  dbo.tz_Postulacion.IDUsuario = $id_usrio AND 
		  dbo.tz_PostulacionItem.ValorNuevo > 0 AND 
		  dbo.tz_PostulacionItem.bEstado IN (3,12) AND
		  dbo.tz_PostulacionItem_TipoPago.IDTipoPago = 3811");
		return $query->result_array();
		
	}
	
	public function get_actapagar_porid($id_usrio){
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaInicio, 105) AS FechaInicio,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaTermino, 105) AS FechaTermino,
		  dbo.tz_Secciones.ValorSeccion,
		  dbo.tz_Postulacion.IDUsuario,
		  dbo.tz_PostulacionItem.IDPostulacionItem,
		  dbo.tz_PostulacionItem.bEstado,
		  dbo.tz_EstadoPostulante.Nombre AS estado,
		  dbo.tz_PostulacionItem.ValorNuevo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  CASE dbo.tz_Secciones.es_admision 
		  WHEN 1 THEN '(Cuota admision)' END AS solo_cuota,
		  dbo.ta_MetadatoDetalle.nmbre AS tipo_actividad,
		  CASE WHEN dbo.tz_Secciones.FechaInicio <= GETDATE() THEN '1' END AS plazo,
		  (SELECT 
			  count(*) AS c
			FROM
			  dbo.tz_reservas z
			WHERE
			  z.idpostulacionitem = dbo.tz_PostulacionItem.IDPostulacionItem) AS comentarios,
		  dbo.tz_PostulacionItem.IDSeccion,
		  (dbo.tz_Secciones.Vacante - (SELECT 
		  count(*) AS c
		FROM
		  dbo.tz_PostulacionItem x
		WHERE
		  x.bEstado IN (3,12) AND 
		  x.IDSeccion = dbo.tz_PostulacionItem.IDSeccion) ) AS cupos
		FROM
		  dbo.tz_PostulacionItem
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_EstadoPostulante ON (dbo.tz_PostulacionItem.bEstado = dbo.tz_EstadoPostulante.IDEstado)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.Tipo = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		WHERE
		  dbo.tz_Postulacion.IDUsuario = $id_usrio AND
		  dbo.tz_PostulacionItem.ValorNuevo > 0 AND
		  dbo.tz_PostulacionItem.bEstado IN(1,2,14)
		ORDER BY
		  CONVERT(FLOAT,dbo.tz_Secciones.FechaInicio) DESC,
		  actividad");
		return $query->result_array();	
		
	}
	
	public function get_todas_actividades($id_usrio){
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaInicio, 105) AS FechaInicio,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaTermino, 105) AS FechaTermino,
		  dbo.tz_Postulacion.IDUsuario,
		  dbo.tz_PostulacionItem.IDPostulacionItem,
		  dbo.tz_PostulacionItem.bEstado,
		  dbo.tz_EstadoPostulante.Nombre AS estado,
		  dbo.tz_PostulacionItem.ValorNuevo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  CASE dbo.tz_Secciones.es_admision 
		  WHEN 1 THEN '(Cuota admision)' END AS solo_cuota,
		  dbo.ta_MetadatoDetalle.nmbre AS tipo_actividad,
		  CASE WHEN dbo.tz_Secciones.FechaInicio <= GETDATE() THEN '1' END AS plazo,
		  (SELECT 
			  count(*) AS c
			FROM
			  dbo.tz_reservas z
			WHERE
			  z.idpostulacionitem = dbo.tz_PostulacionItem.IDPostulacionItem) AS comentarios,
		  dbo.tz_PostulacionItem.IDSeccion,
		  (dbo.tz_Secciones.Vacante - (SELECT 
		  COUNT(*) AS c
		FROM
		  dbo.tz_PostulacionItem x
		WHERE
		  x.bEstado IN (3,12) AND 
		  x.IDSeccion = dbo.tz_PostulacionItem.IDSeccion) ) AS cupos
		FROM
		  dbo.tz_PostulacionItem
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_EstadoPostulante ON (dbo.tz_PostulacionItem.bEstado = dbo.tz_EstadoPostulante.IDEstado)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.Tipo = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		WHERE
		  dbo.tz_Postulacion.IDUsuario = $id_usrio 
		ORDER BY
		  CONVERT(FLOAT,dbo.tz_Secciones.FechaInicio) DESC,
		  actividad");
		return $query->result_array();	
		
	}
	
	public function get_otrasact_porid($id_usrio){
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaInicio, 105) AS FechaInicio,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaTermino, 105) AS FechaTermino,
		  dbo.tz_Postulacion.IDUsuario,
		  dbo.tz_PostulacionItem.IDPostulacionItem,
		  dbo.tz_PostulacionItem.bEstado,
		  dbo.tz_EstadoPostulante.Nombre AS estado,
		  dbo.tz_PostulacionItem.ValorNuevo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  dbo.tz_PostulacionItem.IDSeccion,
		  dbo.ta_MetadatoDetalle.nmbre AS tipo_actividad,
		  CASE dbo.tz_Secciones.es_admision 
		  WHEN 1 THEN '(Cuota admision)' END AS solo_cuota,
		  (SELECT 
			  count(*) AS c
			FROM
			  dbo.tz_reservas z
			WHERE
			  z.idpostulacionitem = dbo.tz_PostulacionItem.IDPostulacionItem) AS comentarios
		FROM
		  dbo.tz_PostulacionItem
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_EstadoPostulante ON (dbo.tz_PostulacionItem.bEstado = dbo.tz_EstadoPostulante.IDEstado)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.Tipo = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		WHERE
		  dbo.tz_Postulacion.IDUsuario = $id_usrio AND
		  dbo.tz_PostulacionItem.bEstado NOT IN(1,2,14,9)
		ORDER BY
		  CONVERT(FLOAT,dbo.tz_Secciones.FechaInicio) DESC,
		  actividad");
		return $query->result_array();	
		
	}
	
	public function get_act_postulando_porid($id_usrio){
		
		$query = $this->db->query("SELECT 
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaInicio, 105) AS FechaInicio,
		  CONVERT(CHAR(10), dbo.tz_Secciones.FechaTermino, 105) AS FechaTermino,
		  dbo.tz_Postulacion.IDUsuario,
		  dbo.tz_PostulacionItem.IDPostulacionItem,
		  dbo.tz_PostulacionItem.bEstado,
		  dbo.tz_EstadoPostulante.Nombre AS estado,
		  dbo.tz_PostulacionItem.ValorNuevo,
		  dbo.tz_Cursos.Nombre_Curso AS actividad,
		  dbo.tz_PostulacionItem.IDSeccion,
		  dbo.ta_MetadatoDetalle.nmbre AS tipo_actividad,
		  CASE dbo.tz_Secciones.es_admision 
		  WHEN 1 THEN '(Cuota admision)' END AS solo_cuota,
		  (SELECT 
			  count(*) AS c
			FROM
			  dbo.tz_reservas z
			WHERE
			  z.idpostulacionitem = dbo.tz_PostulacionItem.IDPostulacionItem) AS comentarios
		FROM
		  dbo.tz_PostulacionItem
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.tz_EstadoPostulante ON (dbo.tz_PostulacionItem.bEstado = dbo.tz_EstadoPostulante.IDEstado)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.Tipo = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		WHERE
		  dbo.tz_Postulacion.IDUsuario = $id_usrio AND
		  dbo.tz_PostulacionItem.ValorNuevo > 0 AND
		  dbo.tz_PostulacionItem.bEstado IN(9)
		ORDER BY
		  CONVERT(FLOAT,dbo.tz_Secciones.FechaInicio) DESC,
		  actividad");
		return $query->result_array();	
		
	}	
	
}