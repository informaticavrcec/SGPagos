<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tz_permisos extends CI_Model {
	
	function delete_usuario($id_usuario){
		
		$query = $this->db->query("DELETE 
		FROM
		  dbo.sgp_usuarios
		WHERE
		  dbo.sgp_usuarios.id_usuario = $id_usuario ");		
		return TRUE;
		
	}
	
	function get_modulos_padres(){
		
		$idusuario = $this->session->userdata('id_usuario');
		
		$query = $this->db->query("SELECT 
		  dbo.sgp_modulos.id_modulo,	
		  dbo.sgp_usuario_modulo.id_usuario,
		  dbo.sgp_modulos.id_padre,
		  dbo.sgp_modulos.nombre,
		  dbo.sgp_modulos.url
		FROM
		  dbo.sgp_usuario_modulo
		  INNER JOIN dbo.sgp_modulos ON (dbo.sgp_usuario_modulo.id_modulo = dbo.sgp_modulos.id_modulo)
		WHERE
		  dbo.sgp_usuario_modulo.id_usuario = $idusuario AND 
		  dbo.sgp_modulos.id_padre IS NULL AND
		  dbo.sgp_modulos.down <> 1 ");		
		return $query->result_array();
		
	}
	
	function get_usuarios_modulo($modulos){
		
		$query = $this->db->query("SELECT 
		  dbo.sgp_usuario_modulo.id_usuario,
		  dbo.sgp_usuario_modulo.id_modulo,
		  dbo.vUsuarioAll.NombreApellido
		FROM
		  dbo.sgp_usuario_modulo
		  INNER JOIN dbo.vUsuarioAll ON (dbo.sgp_usuario_modulo.id_usuario = dbo.vUsuarioAll.idt_usrio)
		WHERE
		  dbo.sgp_usuario_modulo.id_modulo IN ($modulos)
		ORDER BY
		  dbo.vUsuarioAll.NombreApellido");
		return $query->result_array();
		
	}
	
	function get_modulos_up($id_usuario){	
		
		$query = $this->db->query("SELECT 
		  dbo.sgp_modulos.nombre,  
		  dbo.sgp_modulos.id_modulo,
		  dbo.sgp_modulos.id_padre,
		  (SELECT x.id_modulo FROM dbo.sgp_usuario_modulo x WHERE x.id_modulo = dbo.sgp_modulos.id_modulo AND x.id_usuario = $id_usuario) AS sino
		FROM
		  dbo.sgp_modulos
		WHERE
		  dbo.sgp_modulos.down <> 1 AND 
  		  dbo.sgp_modulos.id_padre IS NULL
		ORDER BY
		  dbo.sgp_modulos.nombre");
		return $query->result_array();
			
	}
	
	function get_modulos_subup($id_padre,$id_usuario){			

		$query = $this->db->query("SELECT 
		  dbo.sgp_modulos.nombre,
		  dbo.sgp_modulos.down,
		  dbo.sgp_modulos.id_modulo,
		  dbo.sgp_modulos.id_padre,
		  dbo.sgp_modulos.estado,
		  (SELECT x.id_modulo FROM dbo.sgp_usuario_modulo x WHERE x.id_modulo = dbo.sgp_modulos.id_modulo AND x.id_usuario = $id_usuario) AS sino
		FROM
		  dbo.sgp_modulos
		WHERE
		  dbo.sgp_modulos.estado IS NULL AND 
		  dbo.sgp_modulos.modulo IS NULL AND 
		  dbo.sgp_modulos.id_padre = $id_padre
		ORDER BY
		  dbo.sgp_modulos.nombre");
		return $query->result_array();
			
	}
	
	function get_modulos_otros($id_usuario){			

		$query = $this->db->query("SELECT 
		  dbo.sgp_modulos.nombre,
		  dbo.sgp_modulos.down,
		  dbo.sgp_modulos.id_modulo,
		  dbo.sgp_modulos.estado,
		  (SELECT x.id_modulo FROM dbo.sgp_usuario_modulo x WHERE x.id_modulo = dbo.sgp_modulos.id_modulo AND x.id_usuario = $id_usuario) AS sino,
		  dbo.sgp_modulos.modulo
		FROM
		  dbo.sgp_modulos
		WHERE
		  dbo.sgp_modulos.estado IS NULL AND 
		  dbo.sgp_modulos.modulo IS NOT NULL AND 
		  dbo.sgp_modulos.down = 1
		ORDER BY
		  dbo.sgp_modulos.modulo,
		  dbo.sgp_modulos.nombre");
		return $query->result_array();
			
	}
	
	function update_permisos($id_modulo,$id_usuario,$estado){
		
		if($estado == 's'){
			
			$sql = "DELETE 
			FROM
			  dbo.sgp_usuario_modulo
			WHERE
			  dbo.sgp_usuario_modulo.id_usuario = $id_usuario AND 
			  dbo.sgp_usuario_modulo.id_modulo = $id_modulo ";
			$query = $this->db->query($sql);
			$sql = "INSERT INTO
			  dbo.sgp_usuario_modulo(
			  id_usuario,
			  id_modulo)
			VALUES(
			  $id_usuario,
			  $id_modulo)";	
		}else{
			$sql = "DELETE 
			FROM
			  dbo.sgp_usuario_modulo
			WHERE
			  dbo.sgp_usuario_modulo.id_usuario = $id_usuario AND 
			  dbo.sgp_usuario_modulo.id_modulo = $id_modulo ";
		}

		$query = $this->db->query($sql);
		return TRUE;
			
	}
	
	
	function get_usuarios_sistema(){		
	
		$query = $this->db->query("SELECT 
		  dbo.ta_usrio.usrio AS rut,
		  dbo.sgp_usuarios.estado,
		  dbo.sgp_usuarios.creado,
		  a.NombreApellido AS creador,
		  dbo.vUsuarioAll.NombreApellido AS nombreusuario,
		  dbo.sgp_usuarios.id_usuario
		FROM
		  dbo.sgp_usuarios
		  INNER JOIN dbo.ta_usrio ON (dbo.sgp_usuarios.id_usuario = dbo.ta_usrio.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll ON (dbo.sgp_usuarios.id_usuario = dbo.vUsuarioAll.idt_usrio)
		  INNER JOIN dbo.vUsuarioAll a ON (dbo.sgp_usuarios.creador = a.idt_usrio)
		ORDER BY
		  dbo.vUsuarioAll.NombreApellido");
		return $query->result_array();
		
	}
	
	function get_modulos_por_nombre($nombre){		
	
		$idusuario = $this->session->userdata('id_usuario');
		$query = $this->db->query("SELECT 
		  dbo.sgp_usuario_modulo.id_usuario,
		  dbo.sgp_modulos.nombre,
		  dbo.sgp_modulos.url,
		  dbo.sgp_modulos.icon,
		  dbo.sgp_modulos.modulo
		FROM
		  dbo.sgp_usuario_modulo
		  INNER JOIN dbo.sgp_modulos ON (dbo.sgp_usuario_modulo.id_modulo = dbo.sgp_modulos.id_modulo)
		WHERE
		  dbo.sgp_usuario_modulo.id_usuario = $idusuario AND 
		  dbo.sgp_modulos.modulo = ".$this->db->escape($nombre)."
		ORDER BY
		  dbo.sgp_modulos.nombre  ");
		return $query->result_array();
		
	}
	
	function get_credenciales($usuario,$password){
		
		$query = $this->db->query("SELECT TOP 1 
		  dbo.ta_usrio.usrio AS rut,
		  dbo.ta_usrio.idt_usrio AS id_usuario,
		  CAST(a.valor AS VARCHAR(200)) AS nombres,
		  CAST(b.valor AS VARCHAR(200)) AS apellidos,
		  CAST(c.valor AS VARCHAR(100)) AS pass
		FROM
		  dbo.ta_usrio
		  INNER JOIN dbo.[rel-usrio/membresia] a ON (dbo.ta_usrio.idt_usrio = a.idt_usrio)
		  AND (a.idt_dato_membresia = 1)
		  INNER JOIN dbo.[rel-usrio/membresia] b ON (dbo.ta_usrio.idt_usrio = b.idt_usrio)
		  AND (b.idt_dato_membresia = 2)
		  INNER JOIN dbo.[rel-usrio/membresia] c ON (dbo.ta_usrio.idt_usrio = c.idt_usrio)
		  AND (c.idt_dato_membresia = 16)
		WHERE
		  dbo.ta_usrio.usrio = ".$this->db->escape($usuario)." ");
		$respuesta = $query->row();
		if($respuesta){
		 	return $respuesta;
		}else{
		 	return FALSE;
		}
			
	}
	
	function get_tiene_acceso($id_usuario){
		$query = $this->db->query("SELECT 
		  count(*) AS c
		FROM
		  dbo.sgp_usuarios
		  INNER JOIN dbo.sgp_usuario_modulo ON (dbo.sgp_usuarios.id_usuario = dbo.sgp_usuario_modulo.id_usuario)
		WHERE
		  dbo.sgp_usuario_modulo.id_usuario = $id_usuario AND 
		  dbo.sgp_usuarios.estado = 1
		GROUP BY
		  dbo.sgp_usuarios.estado");
		$resp = $query->row();
		if($resp->c > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function get_modulos_hijos($id_padre){
		
		$idusuario = $this->session->userdata('id_usuario');	
		$query = $this->db->query("SELECT 
		  dbo.sgp_usuario_modulo.id_usuario,
		  dbo.sgp_modulos.id_padre,
		  dbo.sgp_modulos.nombre,
		  dbo.sgp_modulos.url
		FROM
		  dbo.sgp_usuario_modulo
		  INNER JOIN dbo.sgp_modulos ON (dbo.sgp_usuario_modulo.id_modulo = dbo.sgp_modulos.id_modulo)
		WHERE
		  dbo.sgp_usuario_modulo.id_usuario = $idusuario AND 
		  dbo.sgp_modulos.id_padre = $id_padre AND
		  dbo.sgp_modulos.url IS NOT NULL");		
		return $query->result_array();
		
	}
	
	function get_permiso_modulo($id_modulo){
		
		$idusuario = $this->session->userdata('id_usuario');
		$query = $this->db->query("SELECT 
		  dbo.sgp_usuario_modulo.id_usuario,
		  dbo.sgp_modulos.id_padre,
		  dbo.sgp_modulos.nombre,
		  dbo.sgp_modulos.id_modulo
		FROM
		  dbo.sgp_usuario_modulo
		  INNER JOIN dbo.sgp_modulos ON (dbo.sgp_usuario_modulo.id_modulo = dbo.sgp_modulos.id_modulo)
		WHERE
		  dbo.sgp_usuario_modulo.id_usuario = $idusuario AND 
		  dbo.sgp_modulos.id_modulo = $id_modulo ");
		if(count($query->result_array()) > 0){
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
	
	
}