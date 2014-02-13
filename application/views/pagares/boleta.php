<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/general.css" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/datepicker.css" />
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#imprimir").click(function(){
		
		location.href = '/caja/imprimirboleta/<?=$idpostulacionitem?>';
		location.target = "new";
	});
	
	<?=$datepicker?>
});
</script>
</head>

<body>
<?=validation_errors('<div class="alert">','<input type="button" id="close" value="Cerrar"></div>'); ?>
<?=($error)?'<div class="alert">'.$error.'<input type="button" id="close" value="Cerrar"></div>':''; ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
  <tr>
    <td class="titulo" >Asignar boleta pagaré</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<form method="post" action="/pagare/asignarboleta/<?=$idpostulacionitem?>/<?=$id_pago?>">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
  <tr>
    <td align="right"><strong>Fecha</strong></td>
  </tr>
  <tr>
    <td align="right" style="font-size:18px;" ><input type="text" size="11" id="calendar" name="fecha" class="center" readonly="readonly" value="<?=($pagare->Fecha)?$pagare->Fecha:date('d/m/Y');?>" /></td>
  </tr>
  <tr>
    <td  align="right"><strong>Boleta</strong></td>
  </tr>
  <tr>
    <td><input type="text" name="numero_boleta" class="right" style="font-size:18px;width:98%;background-color:#FFC;" value="<?=($pagare->NroBoleta)?$pagare->NroBoleta:set_value('numero_boleta');?>"  /></td>
  </tr>
  <tr>
    <td align="right"><strong>Monto</strong></td>
  </tr>
  <tr>
    <td align="right" class="blue" style="font-size:18px;"><input type="hidden" name="monto_boleta" value="<?=$pagare->MontoCuota?>" class="right" /><span style="float:left">$</span><?=number_format($pagare->MontoCuota,0,',','.')?></td>
  </tr>
  <tr>
    <td height="40" align="right"><input type="submit" value="Guardar" />
      <?
if($pagare->NroBoleta){	
	?>
      <a href="/caja/imprimirboleta/<?=$idpostulacionitem?>/<?=$pagare->NroBoleta?>" class="btn_normal" target="new">Imprimir</a>
      <?
}?></td>
  </tr>
  <tr>
<td align="center"><?=($message)?'<span class="text_alert">'.$message.'</span>':'Verifique monto(s) antes de imprimir la boleta.'; ?></td>
</tr>
</table>
</body>
</html>
