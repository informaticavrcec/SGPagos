<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />

<link rel="stylesheet" href="<?=base_url()?>assets/css/general-print.css" media="print" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/colorbox.css"  />
<link rel="stylesheet" href="<?=base_url()?>assets/css/datepicker.css" />
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
<script type="text/javascript">
$(document).ready(function(){	

	
});
</script>
<style type="text/css">
*{
	font-size:10px;
	font-family:Tahoma, Geneva, sans-serif;
		
}
.day{
	display:inline-block;
	width:40px;
	text-align:center;
	font-weight:bold;	
}

.month{
	display:inline-block;
	text-align:center;
	width:100px;
	font-weight:bold;
}

.year{
	display:inline-block;
	text-align:right;
	width:60px;
	font-weight:bold;
}
</style>
<title>
<?=$page_title?>
</title>
</head>

<body>
<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td width="70" ></td>
<td height="5" ></td>
</tr>
<tr>
  <td align="right">&nbsp;</td>
<td align="right">
<div class="day"><?=$boleta->dia_boleta?></div>
<div class="month"><?=$boleta->mes_boleta?></div>
<div class="year"><?=$boleta->anio_boleta?></div></td>
</tr>
<tr>
  <td height="30" >&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
<td>Actividad: </td>
</tr>
<tr>
  <td>&nbsp;</td>
<td><?=$boleta->Nombre_Curso?></td>
</tr>
<tr>
  <td>&nbsp;</td>
<td>Proyecto: <?=$boleta->proyecto?></td>
</tr>
<tr>
  <td>&nbsp;</td>
<td>Periodo: <?=$boleta->FechaInicio?> / <?=$boleta->FechaTermino?></td>
</tr>
<tr>
  <td>&nbsp;</td>
<td>Participante: <?=$boleta->NombreApellido?> </td>
</tr>
<tr>
  <td>&nbsp;</td>
<td>R.U.T: <?=number_format(substr($boleta->rut,0,strlen($boleta->rut)-1),0,',','.').'-'.substr($boleta->rut,-1)?> </td>
</tr>
<tr>
  <td>&nbsp;</td>
<td>Forma de pago</td>
</tr>
<tr>
  <td valign="top"></td>
<td height="210" valign="top"><?

if(count($pagos_padre) > 0){
	
	foreach($pagos_padre as $value){	
	
		foreach($value['detalle'] as $valor ){			
				?>
                <?=$value['nombre_pago']?> : <?=$valor['detalle']?> $ <?=number_format($valor['Monto'],0,',','.')?><br /><?
			
		}
			
	}
}else{	
	?><p>No existen pagos asociados</p><?
}?>
</td>
</tr>
<tr>
  <td>&nbsp;</td>
<td>Asistente: <?=$boleta->asistente?> <span style="float:right;font-weight:bold;"><?=number_format($totales,0,',','.')?>.-</span></td>
</tr>
<tr>
  <td>&nbsp;</td>
<td height="40"><input type="button" value="Imprimir" onclick="window.print();" /></td>
</tr>
</table>
</body>
</html>
