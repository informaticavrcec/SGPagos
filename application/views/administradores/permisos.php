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
	//$(".sub_listado").show();
	$(".mod_padre").not('input:checkbox').click(function(){
		$(this).parent().find(".sub_listado").slideToggle('fast');		
	});
	
});
</script>
<title>
<?=$page_title?>
</title>

<body>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2" class="titulo" >Administrador de permisos</td>
</tr>
<tr>
<td width="125" height="40"><strong>Usuario</strong></td>
<td><?=$usuario->nombres?> <?=$usuario->apellidos?></td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" >
<tr >
<td height="20" ><strong class="blue">Menu</strong></td>
</tr>
</table>

<ul class="listado_modulos"><?
foreach($modulos as $value){	
	?>
    <li><div class="mod_padre">&bull; <?=$value['nombre']?> 
    <div class="permiso">
    <form method="post" action="/administrador/permisos/<?=$id_usuario?>" >
    <input type="radio" name="<?=$value['id_modulo']?>" onclick="submit()" value="s" <?=($value['sino'])?'checked="checked"':'';?> /> Si / No 
    <input type="radio" name="<?=$value['id_modulo']?>" onclick="submit()" value="n" <?=(!$value['sino'])?'checked="checked"':'';?> />
    </form>
    </div></div><?
	if(count($value['hijos']) > 0){
		?>
        <ul class="sub_listado"><?
		foreach($value['hijos'] as $value2){
			?>
          <li class="row" ><div class="mod_hijo" ><?=$value2['nombre']?> <div class="permiso">
            <form method="post" action="/administrador/permisos/<?=$id_usuario?>" >
            <input type="radio" name="<?=$value2['id_modulo']?>" onclick="submit()" value="s" <?=($value2['sino'])?'checked="checked"':'';?> /> <strong>Si / No</strong> 
            <input type="radio" name="<?=$value2['id_modulo']?>" onclick="submit()" value="n" <?=(!$value2['sino'])?'checked="checked"':'';?> />
            </form>
    	</div></div></li><?
		}?>
        </ul><?
	}?>		
    </li><?
}?>
</ul>

<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
<tr >
<td >&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" >
<tr >
<td height="20" ><strong class="blue">Modulos</strong></td>
</tr>
</table>

<ul class="listado_modulos"><?
foreach($otros_modulos as $value){
	if($value['modulo'] != $ultimo){
		?>
		<li><div class="mod_padre">&bull; <?=ucwords(strtolower($value['modulo']))?></div></li><?
	}
	?>
    <li class="row"><div class="mod_hijo" ><?=$value['nombre']?> 
    <div class="permiso">
    <form method="post" action="/administrador/permisos/<?=$id_usuario?>" >
    <input type="radio" name="<?=$value['id_modulo']?>" onclick="submit()" value="s" <?=($value['sino'])?'checked="checked"':'';?> /> <strong>Si / No</strong> 
    <input type="radio" name="<?=$value['id_modulo']?>" onclick="submit()" value="n" <?=(!$value['sino'])?'checked="checked"':'';?> />
    </form>
    </div></div>
	</li>
	<?
	$ultimo = $value['modulo'];
}?>
</ul>

</body>
</html>
