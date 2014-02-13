<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_pagowebpay extends CI_Model {
	
	public function get_informe_porcajero($desde,$hasta){
		
		$query = $this->db->query("SELECT 
		  z.IDPagoWebPay,
		  z.IDPostulacionItem,
		  z.Monto,
		  z.NumeroTransaccion,
		  z.UltimosDigitosTarjeta,
		  z.NumeroCuotas,
		  z.TasaInteresMax,
		  z.HoraTransaccion,
		  CONVERT(CHAR(10), z.FechaTransaccion, 105) AS FechaTransaccion,
		  z.FechaContable,
		  z.IDTipoDocumento,
		  z.IDModificador,
		  z.FechaModificacion,
		  z.OrdenCompra,
		  z.IDSesion,
		  z.CodigoAutorizacion,
		  z.Respuesta,
		  z.Mac,
		  z.IDEmpresa,
		  z.NOC_Empresa,
		  dbo.tz_Cursos.Nombre_Curso,
		  dbo.ta_MetadatoDetalle.nmbre AS proyecto,
		  dbo.tz_Aplicacion.Nombre AS programa,
		  dbo.ta_usrio.usrio AS rut,
		  UPPER(dbo.vUsuarioAll.NombreApellido) AS NombreApellido,
		  (SELECT 
			  'B/' + d.NroBoleta AS NroBoleta
			FROM
			  dbo.tz_IC_Boletas d
			WHERE
			  d.IDPostulacionItem = z.IDPostulacionItem) AS NroBoleta,
		  (SELECT 
			  'F/' + d.NroFactura AS NroFactura
			FROM
			  dbo.tz_IC_Facturas d
			WHERE
			  d.IDPostulacionItem = z.IDPostulacionItem) AS NroFactura
		FROM
		  dbo.tz_PagoWebPay z
		  INNER JOIN dbo.tz_TipoDocumento ON (z.IDTipoDocumento = dbo.tz_TipoDocumento.IDTipoDocumento)
		  INNER JOIN dbo.tz_PostulacionItem ON (z.IDPostulacionItem = dbo.tz_PostulacionItem.IDPostulacionItem)
		  INNER JOIN dbo.tz_Secciones ON (dbo.tz_PostulacionItem.IDSeccion = dbo.tz_Secciones.IDSeccion)
		  INNER JOIN dbo.tz_Cursos ON (dbo.tz_Secciones.IDCurso = dbo.tz_Cursos.IDCurso)
		  INNER JOIN dbo.tz_Aplicacion ON (dbo.tz_Cursos.IDAplicacion = dbo.tz_Aplicacion.IDAplicacion)
		  LEFT OUTER JOIN dbo.ta_MetadatoDetalle ON (dbo.tz_Cursos.IDTipoProyecto = dbo.ta_MetadatoDetalle.IDMetadatoDetalle)
		  INNER JOIN dbo.tz_Postulacion ON (dbo.tz_PostulacionItem.IDPostulacion = dbo.tz_Postulacion.IDPostulacion)
		  INNER JOIN dbo.ta_usrio ON (dbo.tz_Postulacion.IDUsuario = dbo.ta_usrio.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll ON (dbo.tz_Postulacion.IDUsuario = dbo.vUsuarioAll.idt_usrio)
		WHERE
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.FechaTransaccion)) >= '$desde' AND 
		  DATEADD(dd, 0, DATEDIFF(dd, 0, z.FechaTransaccion)) <= '$hasta'
		ORDER BY
		  programa,
		  proyecto");
		return $query->result_array(); 	
		
	}
}