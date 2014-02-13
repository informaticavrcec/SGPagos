<table border="1" cellpadding="3" cellspacing="0" >
<tr>
<th bgcolor="#F4F4F4">No. bol</th>
<th bgcolor="#F4F4F4" >Monto</th>
<th bgcolor="#F4F4F4" >Fecha bol.</th>
<th bgcolor="#F4F4F4" >Unidad</th>
<th bgcolor="#F4F4F4" >Proyecto</th>
<th bgcolor="#F4F4F4" >Nombre actividad</th>
<th bgcolor="#F4F4F4" >Paterno materno</th>
<th bgcolor="#F4F4F4" >Nombre 1 nombre 2</th>
<th bgcolor="#F4F4F4" >Rut</th>
<th bgcolor="#F4F4F4" >Periodo curso</th>
<th bgcolor="#F4F4F4" >Forma de pago</th>
<th bgcolor="#F4F4F4" >Matriculador</th>
</tr><?
if(count($boletas) < 1 ){
	?>
    <tr>
    <td colspan="11">NADA PARA LISTAR</td>
    </tr><?
}else{
	foreach($boletas as $value){
		
		$partes = explode('-',$value['proyecto']);
		$partes2 = explode(',',$value['alumno']);
		?>		
		<tr height="20" >
		<td align="center"><?=$value['NroBoleta']?></td>
		<td align="right"><?=number_format($value['MontoBoleta'],0,',','.')?></td>
		<td align="center"><?=$value['Fecha']?></td>
        <td align="center" style="mso-number-format:'\@'" ><?=$partes[0]?></td>
        <td align="center" style="mso-number-format:'\@'" ><?=$partes[1]?></td>
        <td ><?=$value['actividad']?></td>
        <td ><?=$partes2[0]?></td>
        <td ><?=$partes2[1]?></td>
        <td align="center"><?=$value['rut']?></td>
        <td align="center"><?=$value['FechaInicio']?>/<?=$value['FechaTermino']?></td>
        <td><?=$value['formaspago']?></td>
        <td ><?=$value['matriculador']?></td>
        </tr>
		<?		
	}
}?>
</table>
</body>
</html>
