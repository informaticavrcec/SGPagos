<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detallepago extends CI_Controller {
	
	public function alumno($idpostulacionitem){
		
		$this->load->helper(array('url','form'));
		if(!is_numeric($idpostulacionitem)){
			redirect('/moduloincorrecto');
		}		
				
		$data['idpostulacionitem'] = $idpostulacionitem;
		$this->load->model(array('Tz_postulacionitem_tipodepago','Tz_grupospago'));
		$res = $this->Tz_postulacionitem_tipodepago->get_formaspago_porpostulacionitem($idpostulacionitem);
		$grupos = $this->Tz_grupospago->get_grupo($idpostulacionitem);
		if(count($grupos) > 0){
			
			foreach($grupos as $value){
				$detalle = $this->Tz_postulacionitem_tipodepago->get_metadetallepago($value['IdPostulacionItem'],FALSE);				
				$data['meta_datos'][] = array(
				'rut' => $detalle->rut,
				'NombreApellido' => $detalle->NombreApellido,
				'Nombre_Curso' => $detalle->Nombre_Curso,
				'ValorNuevo' => $detalle->ValorNuevo,
				'solo_cuota' => $detalle->solo_cuota,
				'IDSeccion' => $detalle->IDSeccion);
				unset($detalle);
			}
		}else{
			$detalle = $this->Tz_postulacionitem_tipodepago->get_metadetallepago($idpostulacionitem,FALSE);				
			$data['meta_datos'][] = array(
			'rut' => $detalle->rut,
			'NombreApellido' => $detalle->NombreApellido,
			'Nombre_Curso' => $detalle->Nombre_Curso,
			'ValorNuevo' => $detalle->ValorNuevo,
			'solo_cuota' => $detalle->solo_cuota,
			'IDSeccion' => $detalle->IDSeccion);
			unset($detalle);
		}
		$boleta = $this->Tz_postulacionitem_tipodepago->get_detalle_boleta($idpostulacionitem);
		$factura = $this->Tz_postulacionitem_tipodepago->get_detalle_factura($idpostulacionitem);
		
		$data['boleta'] = $boleta;
		$data['factura'] = $factura;	
		
		$x = 0;
		$data['tiene_pago'] = 'n';
		$data['totales'] = 0;		
		
		foreach($res as $value){
			$data['tiene_pago'] = 's';
			$data['pagos_padre'][$x]['nombre_pago'] = $value['nombre_pago'];
			$data['pagos_padre'][$x]['FechaCreacion'] = $value['FechaCreacion'];
			$data['pagos_padre'][$x]['creador'] = $value['creador'];
			$data['pagos_padre'][$x]['IDPostulacionItem'] = $value['IDPostulacionItem'];
			$data['pagos_padre'][$x]['IDTipoPago'] = $value['IDTipoPago'];
			
			if($value['IDTipoPago']){			
			
				$res2 = $this->Tz_postulacionitem_tipodepago->get_detalle_delpago($idpostulacionitem,$value['IDTipoPago']);		
				$data['pagos_padre'][$x]['detalle'] = $res2;
				
				$sum = 0;			
				foreach($res2 as $suma){							
					$sum += $suma['Monto'];	
				}				
				$data['pagos_padre'][$x]['suma_total'] = $sum;
				$data['totales'] += $sum;
				
			}
			$x++;
		}		
		
		$data['page_title'] = $this->config->item('page_title').'Detalle del pago'; 		
		$this->load->view('caja/detallepago',$data);	
		
	}
	
	
}