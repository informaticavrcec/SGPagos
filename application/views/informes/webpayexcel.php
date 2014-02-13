<table width="100%" border="1" cellpadding="3" cellspacing="0" align="center" >
<tr>
<th bgcolor="#F4F4F4" >Proyecto</th>
<th bgcolor="#F4F4F4" >Cod. Autorización</th>
<th bgcolor="#F4F4F4" >Ultimos digitos tarjeta</th>
<th bgcolor="#F4F4F4" >Fecha de transacción</th>
<th bgcolor="#F4F4F4" >Monto</th>
<th bgcolor="#F4F4F4" >Alumno</th>
<th bgcolor="#F4F4F4" >Rut</th>
<th bgcolor="#F4F4F4" >Programa</th>
<th bgcolor="#F4F4F4" >Curso / SP</th>
<th bgcolor="#F4F4F4" >IDPI</th>
<th bgcolor="#F4F4F4" >Boleta / Factura</th>
</tr><?
foreach($listado_webpay as $value){
	if($proyecto != $value['proyecto']){
		?>
        <tr bgcolor="#F4F4F4">   
        <td></td>     
        <td align="center" ><strong><?=$value['proyecto']?></strong></td>
        <td colspan="9"></td>
        </tr><?	
	}
	?>
    <tr class="row">
    <td align="center"><?=$value['proyecto']?></td>
    <td align="center"><?=$value['CodigoAutorizacion']?></td>
    <td align="center"><?=$value['UltimosDigitosTarjeta']?></td>
    <td align="center"><?=$value['FechaTransaccion']?></td>
    <td align="right"><?=number_format($value['Monto'],0,',','.')?></td> 
    <td align="left"><?=$value['NombreApellido']?></td>
    <td align="center" style="mso-number-format:'@'" ><?=$value['rut']?></td> 
    <td align="left"><?=$value['programa']?></td>
    <td align="left"><?=$value['Nombre_Curso']?></td>
    <td align="center"><?=$value['IDPostulacionItem']?></td>
    <td align="left"><?=$value['NroBoleta']?><br /><?=$value['NroFactura']?></td>       
    </tr><?
	$proyecto = $value['proyecto'];
}?>
</table>