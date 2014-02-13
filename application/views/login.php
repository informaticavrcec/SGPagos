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
<title>
<?=$page_title?>
</title>
</head>

<body id="bodylogin" onload="document.getElementById('usuario').focus();">
<div id="login">
<form method="post" action="/login" autocomplete="off">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class=""><strong>Login</strong> <?=$this->config->item('logo')?></td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td align="center"><img src="<?=base_url()?>assets/images/logo_uc.png" height="100" /></td>
</tr>
<tr>
<td height="14" align="center"><?=($error)?'<span class="text_alert">'.$error.'</span>':'';?><?=validation_errors('<span class="text_alert">','</span>'); ?></td>
</tr>
<tr>
<td><strong>Usuario</strong></td>
</tr>
<tr>
<td><input type="text" id="usuario" name="usuario" style="width:95%" value="<?=set_value('usuario')?>"  maxlength="20" /></td>
</tr>
<tr>
<td ><strong>Password</strong></td>
</tr>
<tr>
<td><input type="password" name="password" style="width:95%" maxlength="20" /></td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td><input type="submit" style="width:100%" value="Ingresar" /> </td>
</tr>
<tr>
<td align="center">&copy; | <?=date('Y')?></td>
</tr>
</table>
</form>
</div>
</body>
</html>
