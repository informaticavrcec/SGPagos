$(document).ready(function(){
	
	
	$("#buscar_act").width($(".tabla_result").find("th:eq(2)").width()-1);
	$("#detalle_nombre").width($(".tabla_result").find("th:eq(2)").width()-107);		
	
	$(window).resize(function() {
        $("#buscar_act").width($(".tabla_result").find("th:eq(2)").width()-1);
		$("#detalle_nombre").width($(".tabla_result").find("th:eq(2)").width()-107);
    });	
	
	$("#buscar_act").keyup(function(){
		var text = $.trim($(this).val()).toLowerCase();
		
		$(".row,.row2").each(function(){
			
			var fila = $.trim($(this).find("td:eq(2)").text()).toLowerCase();
			if(fila.indexOf(text) >= 0 ){
				$(this).show();	
			}else{
				$(this).hide();
			}
			
		});	
		
	});
	
	$(".bco:eq(0)").change(function(){
		
		var txt = $.trim($(this).val());
		
		$(".bco:not(eq(0))").val(txt);
		
	});
	
	$(".monto:eq(0)").change(function(){
		
		var txt = $.trim($(this).val());
		
		$(".monto:not(eq(0))").val(txt);
		
	});
	
	$(".tar:eq(0)").change(function(){
		
		var txt = $.trim($(this).val());		
		$(".tar:not(eq(0))").val(txt);
		
	});
	
	$(".uno:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());			
		$(".uno:not(eq(0))").val(txt);
		
	});
	
	$(".pagare:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());			
		$(".pagare:not(eq(0))").val(txt);
		
	});
	
	$(".dos:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());			
		$(".dos:not(eq(0))").val(txt);
		
	});
	$(".tres:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());			
		$(".tres:not(eq(0))").val(txt);
		
	});
	
	$(".cuatro:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());			
		$(".cuatro:not(eq(0))").val(txt);
		
	});
	
	$(".num:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());			
		$(".num:not(eq(0))").val(txt);
		
	});
	
	$(".mes:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());			
		$(".mes:not(eq(0))").val(txt);
		
	});
	
	$(".anio:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());			
		$(".anio:not(eq(0))").val(txt);
		
	});
	
	$(".eliminar,.eliminar_2").click(function(e){
		
		if(!confirm("Seguro desea eliminar este registro ?")){
			e.preventDefault();
		}
		
	});
	
	$(".tipo_documento:eq(0)").change(function(){
		
		var txt = $.trim($(this).val());
		
		$(".tipo_documento:not(eq(0))").val(txt);
		
	});
	
	$(".ctacte:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());
		
		$(".ctacte:not(eq(0))").val(txt);
		
	});
	
	$(".rut:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());
		
		$(".rut:not(eq(0))").val(txt);
		
	});
	
	$(".nom:eq(0)").keyup(function(){
		
		var txt = $(this).val();
		
		$(".nom:not(eq(0))").val(txt);
		
	});
	
	$(".tel:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());
		
		$(".tel:not(eq(0))").val(txt);
		
	});
	
	$(".email:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());
		
		$(".email:not(eq(0))").val(txt);
		
	});
	
	$(".nom:eq(0)").change(function(){
		
		var txt = $.trim($(this).val());
		
		$(".nom:not(eq(0))").val(txt);
		
	});
	
	$("input:text:first").click(function(){
		$(this).focus();
	});
	
	
	
	$(".email:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());
		
		$(".email:not(eq(0))").val(txt);
		
	});
	
	$(".tel:eq(0)").keyup(function(){
		
		var txt = $.trim($(this).val());
		
		$(".tel:not(eq(0))").val(txt);
		
	});
	
	$(".serie").eq(0).keyup(function(){
		
		var txt = $.trim($(this).val());
		if(isNaN(txt) == false && txt != '' ){
			txt = parseInt(txt) + 1;
			$(".serie").not(":eq(0)").each(function(){
				$(this).val(txt)
				txt = parseInt(txt) + 1;
			});	
		}else{
			$(".serie:not(:eq(0))").val(txt);
		}
		
				
		
	});
	
	$("#validate").click(function(){	
		
		$(".obl:visible").each(function(){
			var val = $.trim($(this).val());
			
			if(val == ''){
				$(this).addClass('vacio');
			}else{
				$(this).removeClass('vacio');
			}
			
		});
		
		if($(".vacio").length > 0){
			alert("Debe completar todos los campos destacados");
		}else{
			$("#form_validate").submit();
		}
		
	});
	
	$(".detail").click(function(){
		
		var id = $(this).attr("id");		
		$("." + id ).slideToggle('fast');
		
	});
	
	$("a.popup").colorbox({
		iframe:true,
		width : '90%',
		height : '90%'
	});
	
	$("a.popup_short").colorbox({
		iframe:true,
		width : 600,
		height : '90%'
	});
	
	$("#close").click(function(){
		$(".alert").fadeOut('fast','linear',function(){
			$(".alert").remove();
		});	
	});
	
	

	
	
});//END START