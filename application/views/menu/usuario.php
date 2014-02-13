<div id="menu_usuario">
<ul>
<li class="logo"><?=$this->config->item('logo')?></li>
<li>| <a href="/menu">Inicio</a></li><?
foreach($menupadre as $value){
	echo $value;
}?>
<!--<li>| <a href="/administrador/fichausuario/<?=$this->session->userdata('id_usuario')?>" class="popup">Mis datos</a></li>-->
</ul>
<div id="userdata">
<div id="userimage"><img src="<?=base_url()?>assets/images/user.png" /></div>
<div id="usernames" title="<?=$this->session->userdata('nombres')?> <?=$this->session->userdata('apellidos')?>"><?=substr($this->session->userdata('nombres').' '.$this->session->userdata('apellidos'),0,25)?><br /><a href="/logout">Cerrar session</a></div>
</div>
</div>