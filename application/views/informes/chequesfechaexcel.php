<table width="100%" border="1" cellpadding="3" cellspacing="0" align="center" >
<tr>
<th bgcolor="#F4F4F4" >Serie cheque</th>
<th bgcolor="#F4F4F4" >Vencimiento</th>
<th bgcolor="#F4F4F4" >Monto</th>
<th bgcolor="#F4F4F4" >Rut</th>
<th bgcolor="#F4F4F4" >Unidad</th>
<th bgcolor="#F4F4F4" >Girador</th>
<th bgcolor="#F4F4F4" >Rut girador</th>
<th bgcolor="#F4F4F4" >Telefono</th>
<th bgcolor="#F4F4F4" >Banco</th>
<th bgcolor="#F4F4F4" >Unidad</th>
<th bgcolor="#F4F4F4" >Proyecto</th>
<th bgcolor="#F4F4F4" >Caja</th>
<th bgcolor="#F4F4F4" >Boleta</th>
<th bgcolor="#F4F4F4" >F. carga</th>
</tr>
<?
if(count($fecha) < 1 ){
	?>
    <tr>
    <td colspan="11">NADA PARA LISTAR</td>
    </tr><?
}else{
	foreach($fecha as $value){
		
		$partes = explode('-',$value['proyecto']);
		$partes2 = explode(',',$value['alumno']);
		?>		
		<tr class="row">
		<td align="center"><?=$value['NSerie']?></td>
		<td align="center"><?=$value['vcto']?> </td>
		<td align="right"><?=number_format($value['MontoBoleta'],0,',','.')?></td>
        <td align="center" style="mso-number-format:'\@'"  >5-1</td>
        <td align="center" style="mso-number-format:'\@'" >0001</td>
        <td ><?=$value['Nombre_Girador']?></td>
        <td align="right" style="mso-number-format:'\@'" ><?=$value['rut_girador']?></td>
        <td align="center" ><?=$value['telefono_girador']?></td>
        <td align="center"><?=$value['banco']?></td>
        <td align="center" style="mso-number-format:'\@'" ><?=$partes[0]?></td>
        <td align="center" style="mso-number-format:'\@'" ><?=$partes[1]?></td>
        <td align="center" >81</td>
        <td align="center" ><?=$value['NroBoleta']?></td>
        <td  align="center"><?=date('d-m-Y')?></td>
		<?		
	}
}?>
</table>
</body>
</html>
