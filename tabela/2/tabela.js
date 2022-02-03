
	jQuery(document).ready(
		function(){
		
			if($(".tab_carregamento").length){
				jQuery(".tab_conteudo").each(
					function(){
				
					setPosicaoTopDeCarregamento(this);
					}
				);
			}
		}
	);




	
	function setPosicaoTopDeCarregamento(tabela){
		
		if($(tabela).length){
			var altura = parseInt($(tabela).css("height").replace("px","").replace(" ",""));
				
			if(altura<5)
			altura  = 5;

			$(".tab_carregamento").css("top", altura/2);
		}
	}	







	function seleciona(id_tab, id_objeto){
		
		$("."+id_tab).css("background", "#FFF");
		
		$("."+id_tab).removeClass("selecionado");

		$("#"+id_tab+"_"+id_objeto).addClass("selecionado");
		
		$("#"+id_tab+"_"+id_objeto).css("background", "#32baff");
		

	if($("#func_pos_selecionar_"+id_tab).length && $("#func_pos_selecionar_"+id_tab).val().length>0)
	this[$("#func_pos_selecionar_"+id_tab).val().replace("(", "").replace(")", "")]();

	}


	


	
	function pesquisar(id_tab){

	pesquisar(id_tab, 0);
	}



	
	
	
	function pesquisar(id_tab, tipo){

	id_tab = $("#"+id_tab).val();

		if(tipo==null || tipo ==0){
		
		if($("#pag_index_"+id_tab).length)
		$("#pag_index_"+id_tab).val("0");	
		}
		
	setPosicaoTopDeCarregamento($("#area_conteudo_tab_"+id_tab));
	$("#tab_carregamento_"+id_tab).fadeIn("fast");

		
	var where = "";

	if($("#func_where_dinamica_"+id_tab).length && $("#func_where_dinamica_"+id_tab).val().length>0)
	where = this[$("#func_where_dinamica_"+id_tab).val().replace("(", "").replace(")", "")]();


	var order = "";

	if($("#func_orderby_dinamica_"+id_tab).length && $("#func_orderby_dinamica_"+id_tab).val().length>0)
	order = this[$("#func_orderby_dinamica_"+id_tab).val().replace("(", "").replace(")", "")]();

		$.post(	
		$("#tab_path_"+id_tab).val()+'acao.php',
			{
			nome_da_funcao:"pesquisar", 
			id_tab:id_tab,
			termos:$("#tab_termos_"+id_tab).val(),
			inicio:$("#tab_data_ini_"+id_tab).val(),
			fim:$("#tab_data_fim_"+id_tab).val(),
			local:$("#tab_local_"+id_tab).val(),
			where:where,
			order:order,
			tipo:(tipo==null?0:tipo),
			index:($("#pag_index_"+id_tab).length?$("#pag_index_"+id_tab).val():0),
			path_objeto:  $("#path_"+id_tab).val(),
			nome_objeto:  $("#classe_"+id_tab).val(),
			orderby_fixo:  $("#orderby_"+id_tab).val(),
			where_fixo:  $("#where_"+id_tab).val(),
			paginar:  $("#paginar_"+id_tab).val(),
			indices_destaque:  $("#nome_indices_destaque_"+id_tab).val(),
			campo_destaque:  $("#nome_campo_destaque_"+id_tab).val()},
			function(retorno){
			
			console.log(retorno);
			
			$("#tab_carregamento_"+id_tab).fadeOut("fast");
			
			limpaTabela(id_tab);
			
			var aux = "";
			
				if(retorno.indexOf("##_@_##")>=0){
				
				aux = retorno.split("##_@_##");
				$("#area_conteudo_tab_"+id_tab).append(aux[0]);
				
				aux = aux[1].split("_");
				
				$("#rot_pag_"+id_tab).html("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+aux[0]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
			
				if($("#pag_index_"+id_tab).length)
				$("#pag_index_"+id_tab).val(aux[1]);
				}
				else
				$("#area_conteudo_tab_"+id_tab).append(retorno);
			
			if($("#func_pos_pesquisar_"+id_tab).length && $("#func_pos_pesquisar_"+id_tab).val().length>0)
				this[$("#func_pos_pesquisar_"+id_tab).val().replace("(", "").replace(")", "")]();

			}
		);
	}
		
		
	
	
	

	function getIdSelecionado(id_tab){

	var id_tab_aux = $("#"+id_tab).val();

	var id =0;
		$("."+id_tab_aux).each(function(){
		
			if($(this).hasClass("selecionado")){
				
			var aux  =	$(this).attr('id').split("_");
			
			if(aux.length>1)
			id =   aux[1]; 
			
			return;
			}
		});
		
	return id;
	}





	function getLinhaSelecionada(id_tab){

	var id = $("#"+id_tab).val();

	var valores= "";
				

		$("."+id).each(function(){
		
			if($(this).hasClass("selecionado")){
			
				$(this).find("td").each(function(){
				valores += $(this).html()+";_;_;";
				});
			
			return;
			}	
		});
		
	return valores.length>0?valores.split(";_;_;"):"";
	}






	function limpaTabela(id_tab){
		
		$("#area_conteudo_tab_"+id_tab).find("tr").each(
			function(){
			
			if($(this).hasClass("linha_cabecalho"))	
			return;
			
			$(this).remove();
			}
		);
	}





	function executaDuploClick(id){
	
	if($("#func_duploclique_"+id).length && $("#func_duploclique_"+id).val().length>0)
	this[$("#func_duploclique_"+id).val().replace("(", "").replace(")", "")]();
	}



	
	function mudaTipo(id){
		
		if($("#tab_local_"+id).val().split("#")[1]>0){
			
			if($("#pesquisa_termos_"+id).is( ":visible" ))
				$("#pesquisa_termos_"+id).fadeOut("fast", function(){$("#pesquisa_data_"+id).fadeIn("fast")});
		}
		else{
			
			if($("#pesquisa_data_"+id).is( ":visible" ))
				$("#pesquisa_data_"+id).fadeOut("fast", function(){$("#pesquisa_termos_"+id).fadeIn("fast")});
		}
	}

