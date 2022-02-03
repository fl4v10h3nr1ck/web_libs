

jQuery(document).ready(
	function(){

		$(".sombra").click(
			function(){
			fechar();
			}
		);
	
	var altura_tela = jQuery(window).outerHeight();
	
	if(altura_tela>screen.height)
	var altura_tela = screen.height;


	$(".sombra").css({"height":altura_tela+"px"});
	
	
	$(".dialogo_div_geral").each(
		function(){
			
		var altura  = parseInt($(this).css("height").replace(/\D+/g, ''));
			
		if(altura<=0)
		altura = 10;
			
		if(altura>altura_tela)
		altura = altura_tela;
			
		altura = (altura_tela - altura)/2 - 10;
			
		if(altura>30)
		altura -=20;
		
		$(this).css({"top":altura+"px"});
		}
	);
});







function mostrarDialogo(id){
	
	mostrarDialogo(id, null);
}






function mostrarDialogo(id, dados){
	
	if(dados!=null && dados.length>0)
	$("#area_conteudo_"+id).html(dados);
	
	$(".sombra").css({"display":"block"});
	$("#"+id).fadeIn("slow");
}





function fechar(){
	
	$(".dialogo_div_geral").fadeOut("fast", 
		function(){
			$(".sombra").css({"display":"none"})
		}
	);
}














