<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sgp_descuentos extends CI_Model {
	
	function get_descuentos($idpostulacionitem){
		$query = $this->db->query("SELECT 
		  z.id_descuento,
		  z.nombre,
		  z.estado,
		  z.id_usuario,
		  z.creado,
		  z.tipo,
		  z.valor,
		  z.comentario,
		  (SELECT 
			  x.id_descuento AS id_alumno
			FROM
			  dbo.sgp_alumno_descuento x
			WHERE
			  x.id_postulacionitem = $idpostulacionitem AND
			  x.id_descuento = z.id_descuento) AS sino
		FROM
		  dbo.sgp_descuentos z
		WHERE
		  z.estado = 1 AND
		  z.id_seccion IS NULL
		ORDER BY
		  z.valor");
		return $query->result_array();	
	}
	
	function get_descuentos_libres($idpostulacionitem){
		$query = $this->db->query("SELECT 
		  z.id_descuento,
		  z.valor,
		  z.motivo,
		  z.id_usuario,
		  z.creado,
		  z.tipo,
		  z.id_postulacionitem,
		  z.antes,
		  z.despues,
		  dbo.vUsuarioAll.NombreApellido
		FROM
		  dbo.sgp_descuentos_libres z
		  LEFT OUTER JOIN dbo.vUsuarioAll ON (z.id_usuario = dbo.vUsuarioAll.idt_usrio)
		WHERE
		  z.id_postulacionitem = $idpostulacionitem ");
		return $query->result_array();	
	}
	
	function update_descuentos_libre($idpostulacionitem,$tipo,$valor,$id_usuario,$motivo){
		
		$query =$this->db->query("SELECT 
		  z.ValorNuevo
		FROM
		  dbo.tz_PostulacionItem z
		WHERE
		  z.IDPostulacionItem = $idpostulacionitem ");
		$resp2 = $query->row();
		
		if($tipo == '0'){
			
			if($resp2->ValorNuevo < $valor){
				return 'El valor del descuento no puede ser mayor al valor a recaudar.';
			}else{		
				
				$valor_nuevo = $resp2->ValorNuevo - $valor;
				
			}
		}else{
			if($valor > 100 ){
				return 'El valor del descuento no puede ser mayor al 100%.';
			}else{
			
				$valor = (int)$resp2->ValorNuevo * ($valor / 100);
				$valor_nuevo = $resp2->ValorNuevo - $valor;
				
			}
		}
		
		$query =$this->db->query("UPDATE 
		  dbo.tz_PostulacionItem
		SET
		  ValorNuevo = ".$valor_nuevo."
		WHERE
		  dbo.tz_PostulacionItem.IDPostulacionItem = $idpostulacionitem ");
		
		$data = array(
			'valor' => $valor,
			'motivo' => $motivo,
			'id_usuario' => $id_usuario ,
			'creado' => date('d-m-Y H:i:s'),
			'tipo' => $tipo,
			'id_postulacionitem' => $idpostulacionitem,
			'antes' => $resp2->ValorNuevo,
			'despues' => $valor_nuevo);
		$query = $this->db->insert('sgp_descuentos_libres',$data);
		return 'Registro grabado con exito.';
	}
	
	function update_descuentos($idpostulacionitem,$id_descuento,$id_seccion,$id_usuario){
		
		$query = $this->db->query("SELECT 
		  COUNT(*) AS c
		FROM
		  dbo.sgp_alumno_descuento z
		WHERE
		  z.id_postulacionitem = $idpostulacionitem");
		$resp = $query->row();
		if($resp->c > 0){
			return 'Los descuentos no son acumulables, ya tiene un descuento asociado.';
		}else{
			$query =$this->db->query("SELECT 
			  z.ValorNuevo
			FROM
			  dbo.tz_PostulacionItem z
			WHERE
			  z.IDPostulacionItem = $idpostulacionitem ");
			$resp2 = $query->row();
			$query =$this->db->query("SELECT 
			  z.id_descuento,
			  z.tipo,
			  z.estado,
			  z.valor,
			  z.id_seccion
			FROM
			  dbo.sgp_descuentos z
			WHERE
			  z.id_descuento = $id_descuento ");
			$resp3 = $query->row();
			if(($resp3->id_seccion != '') AND $resp3->id_seccion != $id_seccion){
				return 'Este descuento no es aplicable para esta actividad.';
			}else{
				if($resp3->tipo == 0){
					$nuevo = $resp3->valor;
				}else{
					$nuevo = $resp2->ValorNuevo * ($resp3->valor / 100);
				}
							
				$nuevo = $resp2->ValorNuevo - (int)$nuevo;
				//Aplica descuento
				$query =$this->db->query("UPDATE 
				  dbo.tz_PostulacionItem
				SET
				  ValorNuevo = ".$nuevo."
				WHERE
				  dbo.tz_PostulacionItem.IDPostulacionItem = $idpostulacionitem ");
				//Registra descuento  
				$query =$this->db->query("INSERT INTO
				  dbo.sgp_alumno_descuento(
				  id_postulacionitem,
				  id_descuento,
				  id_usuario,
				  creado,
				  valor_anterior,
				  valor_nuevo)
				VALUES(
				  $idpostulacionitem,
				  $id_descuento,
				  $id_usuario,
				  GETDATE(),
				  ".$resp2->ValorNuevo.",
				  $nuevo)");
				return 'Descuento aplicado con exito.';
			}
			
		}
	}
		
	
	function get_descuentos_seccion($idpostulacionitem,$id_seccion){
		$query = $this->db->query("SELECT 
		  z.id_descuento,
		  z.nombre,
		  z.estado,
		  z.id_usuario,
		  z.creado,
		  z.tipo,
		  z.valor,
		  z.comentario,
		  z.id_seccion,
		  (SELECT 
			  x.id_descuento AS id_alumno
			FROM
			  dbo.sgp_alumno_descuento x
			WHERE
			  x.id_postulacionitem = $idpostulacionitem AND
			  x.id_descuento = z.id_descuento) AS sino
		FROM
		  dbo.sgp_descuentos z
		WHERE
		  z.id_seccion = $id_seccion AND
		  z.estado = 1
		ORDER BY
		  z.valor");
		return $query->result_array();	
	}
	
}