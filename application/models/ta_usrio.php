<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_usrio extends CI_Model {
	
	public function get_usrionombres_porrut($rut){		
				
		$query = $this->db->query("SELECT TOP 1
		  CAST(a.valor AS VARCHAR(1000)) AS nombres,
		  CAST(b.valor AS VARCHAR(1000)) AS apellidos,
		  z.usrio,
		  z.idt_usrio
		FROM
		  dbo.ta_usrio z
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] a ON (z.idt_usrio = a.idt_usrio)
		  AND (a.idt_dato_membresia = 1)
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] b ON (z.idt_usrio = b.idt_usrio)
		  AND (b.idt_dato_membresia = 2)
		WHERE
		  z.usrio = ".$this->db->escape($rut));		
		if($query->num_rows() < 1){
			return array( 0 => array('nombres' => 'El rut ingresado no existe','apellidos' => '' ,'idt_usrio' => '','usrio' => ''));
		}else{
			return $query->result_array();		
		}
		
	}
	
	public function get_usrionombres_porid($idt_usrio){		
				
		$query = $this->db->query("SELECT 
		  CAST(a.valor AS VARCHAR(1000)) AS nombres,
		  CAST(b.valor AS VARCHAR(1000)) AS apellidos,
		  CAST(c.valor AS VARCHAR(1000)) AS correo,
		  CAST(d.valor AS VARCHAR(1000)) AS fijo,
		  CAST(e.valor AS VARCHAR(1000)) AS celular,
		  CAST(f.valor AS VARCHAR(1000)) AS password,
		  CAST(g.valor AS VARCHAR(1000)) AS paterno,
		  CAST(h.valor AS VARCHAR(1000)) AS materno,
		  z.usrio AS rut,
		  z.idt_usrio
		FROM
		  dbo.ta_usrio z
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] a ON (z.idt_usrio = a.idt_usrio)
		  AND (a.idt_dato_membresia = 1)
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] b ON (z.idt_usrio = b.idt_usrio)
		  AND (b.idt_dato_membresia = 2)
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] c ON (z.idt_usrio = c.idt_usrio)
		  AND (c.idt_dato_membresia = 83)
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] d ON (z.idt_usrio = d.idt_usrio)
		  AND (d.idt_dato_membresia = 81)
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] e ON (z.idt_usrio = e.idt_usrio)
		  AND (e.idt_dato_membresia = 118)
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] f ON (z.idt_usrio = f.idt_usrio)
		  AND (f.idt_dato_membresia = 16)
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] g ON (z.idt_usrio = g.idt_usrio)
		  AND (g.idt_dato_membresia = 297)
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] h ON (z.idt_usrio = h.idt_usrio)
		  AND (h.idt_dato_membresia = 298)
		WHERE
		  z.idt_usrio = ".$idt_usrio);	
		return $query->row();	
	}
	
	function update_metadata($idt_usrio,$data,$texto){
		
		if(is_numeric($idt_usrio) AND is_numeric($data) AND $texto){
			
			$query = $this->db->query("SELECT 
			  COUNT(*) AS c
			FROM
			  dbo.[rel-usrio/membresia] z
			WHERE
			  z.idt_usrio = $idt_usrio AND 
			  z.idt_dato_membresia = $data ");
			$resultado = $query->row();
			if($resultado->c > 0){
				$sql = "UPDATE 
				  dbo.[rel-usrio/membresia]
				SET
				  valor = ".$this->db->escape($texto)."
				WHERE
				  dbo.[rel-usrio/membresia].idt_usrio = $idt_usrio AND 
				  dbo.[rel-usrio/membresia].idt_dato_membresia = $data ";
			}else{
				$sql = "INSERT INTO
				  dbo.[rel-usrio/membresia](
				  valor,
				  idt_usrio,
				  idt_dato_membresia)
				VALUES(
				  ".$this->db->escape($texto).",
				  $idt_usrio,
				  $data)";
			}
		
			$this->db->query($sql);	
		}
		
		
	}
	
	function update_rut($rut,$idt_usrio,$id_usuario){
		
		if(is_numeric($idt_usrio)){
			$opt = "AND dbo.ta_usrio.idt_usrio <> $idt_usrio ";	
		}
		
		$query = $this->db->query("SELECT 
		  COUNT(*) AS c
		FROM
		  dbo.ta_usrio
		WHERE
		  dbo.ta_usrio.usrio = ".$this->db->escape($rut)." $opt");
		$cantidad = $query->row();
		if($cantidad->c > 0){
			return 'El rut ingresado ya esta asociado a otro usuario';
		}else{
			if($idt_usrio){
				$sql = "UPDATE 
				  dbo.ta_usrio
				SET
				  usrio = ".$this->db->escape($rut).",
				  IdModificador = $id_usuario,
				  fch_mdfcn = GETDATE()
				WHERE
				  dbo.ta_usrio.idt_usrio = $idt_usrio ";	
			}else{
				$sql = "";
			}
			$this->db->query($sql);
			
		}
	}
	
	public function buscar_usuario($rut,$nombres,$apellidos){
		
		if($rut){
			$opt = " AND z.usrio LIKE '".$this->db->escape_like_str($rut)."%'";
			
		}
		
		if($nombres){
			$opt .= " AND CAST(a.valor AS VARCHAR(1000)) LIKE '%".$this->db->escape_like_str($nombres)."%'";			
		}
		
		if($apellidos){
			$opt .= " AND CAST(b.valor AS VARCHAR(1000)) LIKE '%".$this->db->escape_like_str($apellidos)."%'";
		}
		
		$query = $this->db->query("SELECT 
		  CAST(a.valor AS VARCHAR(1000)) AS nombres,
		  CAST(b.valor AS VARCHAR(1000)) AS apellidos,
		  z.usrio AS rut,
		  z.idt_usrio
		FROM
		  dbo.ta_usrio z
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] a ON (z.idt_usrio = a.idt_usrio)
		  AND (a.idt_dato_membresia = 1)
		  LEFT OUTER JOIN dbo.[rel-usrio/membresia] b ON (z.idt_usrio = b.idt_usrio)
		  AND (b.idt_dato_membresia = 2)
		WHERE 
		  z.idt_usrio NOT IN (10,117503) $opt
		ORDER BY apellidos ");
		//echo $opt;
		return $query->result_array();
		
	}
	
	public function get_cajeros(){
		$query = $this->db->query("SELECT DISTINCT
		  b.NombreApellido,
		  z.usrio AS rut,
		  z.idt_usrio
		FROM
		  dbo.ta_usrio z
		  INNER JOIN dbo.vUsuarioAll b ON (z.idt_usrio = b.idt_usrio)
		  INNER JOIN dbo.[rel-usrio/grupo] c ON (z.idt_usrio = c.idt_usrio)
		  INNER JOIN dbo.ta_grupo d ON (d.idt_grupo = c.idt_grupo)
		WHERE
		  d.idt_grupo_padre = 551 AND 
		  z.idt_estdo = 1
		ORDER BY
		  b.NombreApellido");
		return $query->result_array();	
	}
	
		
	
	
}

?>