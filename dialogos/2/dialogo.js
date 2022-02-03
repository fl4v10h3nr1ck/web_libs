

jQuery(document).ready(
	function(){

		$(".sombra").click(
			function(){
			fechar();
			}
		);
	
	
	
	
/*
	
	$(".dialogo_div_geral").each(
		function(){
			
		var altura  = parseInt($(this).css("height").replace(/\D+/g, ''));
			
			console.log(altura);
			
		if(altura<=0)
		altura = 10;
			
		if(altura>altura_tela)
		altura = altura_tela;
		else
		altura = (altura_tela - altura)/2 - 50;
			
		if(altura>30)
		altura -=20;
		
		$(this).css({"top":altura+"px"});
		}
	);*/
});







function mostrarDialogo(id){
	
	mostrarDialogo(id, null);
}






function mostrarDialogo(id, dados){
	

	if(dados!=null && dados.length>0)
	$("#area_conteudo_"+id).html(dados);
	
	
	var altura_tela = jQuery(window).outerHeight();
	
	if(altura_tela>screen.height)
	var altura_tela = screen.height;

	
	$("#"+id).css({"top":"50px"});
	
	
	$(".sombra").css({"display":"block"});
	$("#"+id).fadeIn("slow", function(){
		
		var altura  = parseInt($("#"+id).height());
				
		if(altura<=0)
		altura = 10;
				
		if(altura>altura_tela)
			altura = altura_tela;
		else
			altura = (altura_tela - altura)/2;
				
		if(altura>30)
			altura -=20;
		
		$("#"+id).css({"top":altura+"px"});
		
		var altura_tela = jQuery(document).outerHeight();
			
		$(".sombra").css({"height":altura_tela+"px"});
	});
		
}





function fechar(){
	
	$(".dialogo_div_geral").fadeOut("fast", 
		function(){
			$(".sombra").css({"display":"none"})
		}
	);
}














