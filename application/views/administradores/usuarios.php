<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/general.css" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/colorbox.css" />
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$("#rut").keyup(function(){
		
		var txt = $.trim($(this).val()).toLowerCase();
				
		$(".row").each(function(){
			
			var cell = $.trim($(this).find("td:eq(2)").text()).toLowerCase();
			
			if(cell.indexOf(txt) != -1){
				$(this).show();	
			}else{
				$(this).hide();
			}
			
		});
	});
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body id="body">
<?=($this->session->flashdata('error'))?'<div class="alert" >'.$this->session->flashdata('error').'<input type="button" id="close" value="Cerrar"></div>':'';?>
<?=$menuusuario?>
<form method="post">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2" class="titulo" >Administrador de usuarios SGP</td>
</tr>
<tr>
<td colspan="2">Listado de usuarios</td>
</tr>
<tr>
<td height="40" width="125"><strong>Buscar</strong>  </td>
<td><input type="text" id="rut" name="rut" value="<?=set_value('rut')?>" size="90" placeholder="Buscar por nombres y/o apellidos" /> <a href="" class="popup btn_normal">Agregar usuario</a></td>
</tr>
</table>
</form>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th width="80">Rut</th>
<th>Nombres</th>
<th width="80">&nbsp;</th>
<th width="40">&nbsp;</th>
</tr>
<?
$c = 1;
foreach($usuarios as $value){
	?>
    <tr class="row" title="<?=$value['creador']?> / <?=$value['creado']?>">
    <td align="center"><strong><?=$c?></strong></td>
    <td align="center"><?=$value['rut']?></td>
    <td><?=$value['nombreusuario']?></td>
    <td align="center"><a href="/administrador/permisos/<?=$value['id_usuario']?>" class="popup blue">permisos</a></td>
    <td align="center"><a href="/administrador/eliminarusuario/<?=$value['id_usuario']?>" class="eliminar_2" title="Eliminar usuario"><img src="<?=base_url()?>assets/images/delete.png" align="absmiddle" /></a></td>  
    </tr>
    <?
	$c++;	
}?>
</table>
</body>
</html>
