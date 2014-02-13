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
$(document).ready(function() {
    $("a.popup_new").colorbox({
		iframe:true,
		width : 600,
		height : '90%',
		onClosed : function(){
			$("#form").submit();	
		}
	});
});
</script>
<title><?=$page_title?></title>
</head>

<body id="body" >
<?=$menuusuario?>
<?=($flash_error)?'<div class="alert" >'.$flash_error.'<input type="button" id="close" value="Cerrar"></div>':'';?>
<?=($this->session->flashdata('error'))?'<div class="alert" >'.$this->session->flashdata('error').'<input type="button" id="close" value="Cerrar"></div>':'';?>
<?
if($total_arecaudar > 0){
	?><div id="formas_pago">
	<ul><?
	foreach($formas_pago as $value){
		?>
		<li><a href="/caja/pago/<?=$value['IDMetadatoDetalle']?>/<?=base64_encode(implode(',',$a_pagar))?>" class="popup_new"><?=$value['nmbre']?></a></li><?
	}?>
	</ul>
	</div><?
}?>
<form method="post" action="/administrador/pagos">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo">Detalle pago</td>
</tr>
<tr>
<td height="40"><input type="submit" value="Salir" /> 
<input type="hidden" name="rut" value="<?=set_value('rut')?>" />
<input type="hidden" name="a_pagar" value="<?=implode(',',$a_pagar)?>" /></td>
</tr>
</table>
</form>
<form method="post" id="form" >
<input type="hidden" name="a_pagar[]" value="<?=implode(',',$a_pagar)?>" />
<input type="hidden" name="rut" value="<?=set_value('rut')?>" />
</form>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result"><?
foreach($seccion as $valor){
	?>
    <tr class="row2" >
    <td colspan="2"><strong><?=$valor['Nombre_Curso']?></strong> <span class="show_detail detail" id="<?=$valor['IDPostulacionItem']?>" >ver detalle</span></td>
    <td width="100" align="right"><strong style="float:left">$</strong><strong><?=number_format($valor['ValorNuevo'],0,',','.')?></strong></td>
    <td width="100" align="right">&nbsp;</td>
    </tr>
  <tr class="<?=$valor['IDPostulacionItem']?> hide" >
    <td width="150" ><strong>Programa</strong></td>
    <td colspan="3"><?=$valor['programa']?></td>    
  </tr>
    <tr class="<?=$valor['IDPostulacionItem']?> hide" >
    <td width="150" ><strong>ID</strong></td>
    <td colspan="3"><?=$valor['IDSeccion']?></td>    
    </tr>
  <tr class="<?=$valor['IDPostulacionItem']?> hide" >
    <td ><strong>Nombre </strong></td>
    <td colspan="3"><?=$valor['Nombre_Curso']?></td>    
  </tr>
    <tr class="<?=$valor['IDPostulacionItem']?> hide" >
    <td><strong>Fecha inicio</strong></td>
    <td colspan="3"><?=$valor['FechaInicio']?></td>   
  </tr>
    <tr class="<?=$valor['IDPostulacionItem']?> hide" >
    <td><strong>Fecha termino</strong></td>
    <td colspan="3"><?=$valor['FechaTermino']?></td>    
  </tr><?	
}?>
<tr >
<td colspan="2" bgcolor="#F4F4F4" ><strong>TOTAL A CANCELAR</strong></td>
<td align="right" bgcolor="#F4F4F4" width="100" ><strong style="float:left">$</strong> <strong><?=number_format($total_arecaudar,0,',','.')?></strong></td>
<td align="right" bgcolor="#F4F4F4" width="100" >&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td>&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th>Detalle</th>
<th width="100">Monto</th>
<th width="100">&nbsp;</th>
</tr>
<?
$c = 1;
$suma_final = 0;
foreach($pagos as $value){
	
	?>
    <tr class="row">
    <td align="center"><strong><?=$c?></strong></td>
    <td><strong><?=$value['nombre_pago']?></strong> <span class="show_detail detail" id="<?=$value['IDTipoPago']?>" >ver detalle</span></td> 
    <td align="right"><strong><?=number_format($value['suma_tipo'],0,',','.')?></strong></td>
    <td align="right">&nbsp;</td>   
    </tr>
    <?
	foreach($value['detalle'] as $val){
		?>
        <tr class="<?=$value['IDTipoPago']?> hide" >
        <td></td>
        <td><?=$val['detalle']?></td>
        <td align="right"><?=number_format($val['Monto'],0,',','.')?></td>
        <td align="center"><a href="/administrador/eliminarpago/<?=$value['IDTipoPago']?>/<?=$val['id']?>/<?=base64_encode(implode(',',$a_pagar))?>" class="eliminar">Eliminar pago</a></td>       
        </tr>
        <?
	}
	$suma_final += $value['suma_tipo'];
	$c++;
}?>
<tr>
<td bgcolor="#F4F4F4" colspan="2"><strong>TOTAL ABONADO</strong></td>
<td align="right" bgcolor="#F4F4F4" ><strong style="float:left">$</strong><strong><?=number_format($suma_final,0,',','.')?></strong></td>
<td align="right"  >&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
<tr>
<td>&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result"><?
if($suma_final == 0){
    $color = 'background-color:#FFF;';
}else{
    if($suma_final !== $total_arecaudar){
        $color = 'background-color:#CC0000;color:#FFF;';
    }elseif($suma_final === $total_arecaudar){
        $color = 'background-color:#009900;color:#FFF;';
    }		
}

$diferencia = $suma_final - $total_arecaudar;
if($diferencia != 0){
	$msg = 'DIFERENCIA';
}else{
	$msg = 'TOTALES IGUALES';
}
?>
<tr>
<td colspan="2" bgcolor="#F4F4F4" ><strong><?=$msg?></strong></td>
<td width="100" align="right" style="<?=$color?>" ><strong style="float:left">$</strong><strong><?=number_format($diferencia,0,',','.')?></strong></td>
<td width="100" align="right"  >&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
<tr>
<td>&nbsp;</td>
</tr>
</table>
<form method="post" action="/administrador/procesapago">
<input type="hidden" name="a_pagar[]" value="<?=implode(',',$a_pagar)?>" />
<input type="hidden" name="rut" value="<?=set_value('rut')?>" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" >
<tr>
<td><!--<input type="submit"  value="Matricular / Generar comprobantes" />--></td>
</tr>
</table>
</form>
</body>
</html>