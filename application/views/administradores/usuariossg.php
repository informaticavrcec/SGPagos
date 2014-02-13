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
<script type="text/javascript" src="<?=base_url()?>assets/js/datehelper.js"></script>
<script type="text/javascript">



$(document).ready(function(){
	
	$("#calcula").click(function(){
		
		var part = $("#fecha").val().split("/");
		var fecha =  new Date(part[2],part[1],part[0]);
		var new_fecha = new Date(fecha);
		var new_fecha = fecha.setDate(fecha.getDate() + 30);
		alert(fecha.getDay() + "/" + fecha.getMonth() + "/" + fecha.getYear());
				
	});
	
	
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body id="body">
<?=($error)?'<div class="alert">'.$error.'<input type="button" id="close" value="Cerrar"></div>':'';?>
<?=$menuusuario?>
<form method="post">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2" class="titulo" >Usuarios SG</td>
</tr>
<tr>
<td colspan="2">Listado de usuarios</td>
</tr>
<tr>
<td height="40" width="125"><strong>Rut</strong>  </td>
<td>
<input type="text" name="rut" value="<?=set_value('rut')?>" size="11" class="center" /> 
<input type="text" name="nombres" value="<?=set_value('nombres')?>" size="40" placeholder="Nombres" />
<input type="text" name="apellidos" value="<?=set_value('apellidos')?>" size="40" placeholder="Apellidos" />
<input type="submit" value="Buscar" /> 

<input type="button" id="calcula" value="Calucla" /> 
<input type="text" id="fecha" value="<?=date('d/m/Y')?>" /></td>
</tr>
</table>
</form>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th width="80">Rut</th>
<th >Nombres</th>
<th >Apellidos</th>
<th width="80">&nbsp;</th>
</tr>
<?
if(count($usuarios) < 1){
	?>
    <tr>
    <td colspan="5">NADA PARA LISTAR</td>
    </tr><?
}else{
	$c = 1;
	foreach($usuarios as $value){
		?>
		<tr class="row" >
		<td align="center"><strong><?=$c?></strong></td>
		<td align="center"><?=$value['rut']?></td>
		<td><?=$value['nombres']?></td>
		<td ><?=$value['apellidos']?></td>
		<td align="center"><a href="/administrador/fichausuario/<?=$value['idt_usrio']?>" class="popup blue">editar</a></td>
		</tr>
		<?
		$c++;	
	}
}?>
</table>
</body>
</html>
