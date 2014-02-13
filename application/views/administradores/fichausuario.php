<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	
	$("#nombre").blur(function(){
		$(this).val($.trim($(this).val()).toUpperCase());
	});
	
	$("#apellidopaterno,#apellidomaterno").blur(function(){
		$(this).val($.trim($(this).val()).toUpperCase());
		
		$("#apellidos").val($.trim($("#apellidopaterno").val()) + ' ' + $.trim($("#apellidomaterno").val()));
	});
	
	
	
	$("#reset").click(function(){
		
		var txt = $.trim($("#rut").val()).substring(0,4);
		$("#password").val(txt);
	});
	
	var txt = $.trim($("#rut").val()).substring(0,4);
	$("#four").text(txt);
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body>
<?=($message)?'<div class="alert">'.$message.'<input type="button" id="close" value="Cerrar"></div>':'';?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2" class="titulo" >Ficha usuario SG</td>
</tr>
<tr>
<td colspan="2">Detalle</td>
</tr>
<tr>
  <td >&nbsp;</td>
  <td></td>
</tr>
</table>
<form method="post" action="/administrador/fichausuario/<?=$idt_usrio?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td height="20" width="120"><strong>Rut</strong>  </td>
<td>
<input type="text" name="rut" value="<?=$usuario->rut?>" id="rut" size="11" maxlength="20" /> 
<input type="hidden" name="id" value="<?=$idt_usrio?>" /> <span style="color:red"><?=$error_rut?></span></td>
</tr>
<tr>
  <td><strong>Nombres</strong></td>
<td><input type="text" name="datos[1]" size="60" id="nombre" value="<?=$usuario->nombres?>" /></td>
</tr>
<tr>
  <td><strong>Apellido paterno</strong></td>
<td><input type="text" name="datos[297]" size="60" id="apellidopaterno" value="<?=($usuario->paterno)?$usuario->paterno:$usuario->apellidos;?>" /></td>
</tr>
<tr>
  <td><strong>Apellido materno</strong></td>
<td><input type="text" name="datos[298]" size="60" id="apellidomaterno" value="<?=($usuario->materno)?$usuario->materno:''?>" /></td>
</tr>
<tr>
  <td><strong>Correo</strong></td>
<td><input type="text" name="datos[83]" size="60" value="<?=$usuario->correo?>" /></td>
</tr>
<tr>
  <td><strong>Telefono fijo</strong></td>
<td><input type="text" name="datos[81]" size="60" value="<?=$usuario->fijo?>" /></td>
</tr>
<tr>
  <td><strong>Telefono celular</strong></td>
<td><input type="text" name="datos[118]" size="60" value="<?=$usuario->celular?>" /></td>
</tr>
<tr >
  <td height="20">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr class="row2">
  <td height="40">&nbsp;<strong>Password</strong></td>
<td><input type="password" name="datos[16]" id="password" size="60" value="<?=$usuario->password?>" title="<?=$usuario->password?>" /> <a href="#" id="reset" style="color:blue" >resetear clave a <span id="four"></span></a></td>
</tr>
<tr >
  <td height="20">&nbsp;</td>
  <td>&nbsp; <input type="hidden" name="datos[2]" id="apellidos" value="<?=$usuario->paterno.' '.$usuario->materno?>" /></td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="Guardar" /></td>
</tr>
</table>
</form>
</body>
</html>
