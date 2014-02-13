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
<title><?=$page_title?></title>
</head>

<body id="body">
<?=$menuusuario?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo" >Menu Principal</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td>
<ul id="menu_lista"><?
foreach($modulos as $value){
	?>
    <li><a href="<?=$value['url']?>"><div><img src="<?=base_url()?>assets/images/<?=$value['icon']?>" border="0" /></div> <?=$value['nombre']?></a></li><?
}?>
</ul>
</td>
</tr>
</table>
</body>
</html>
