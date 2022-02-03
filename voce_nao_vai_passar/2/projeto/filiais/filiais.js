

	function novaFilial(){
		
		mostrarForm(0);
	}
	
	
	
	
	
	
	function editarFilial(){
		
	var id = getIdSelecionado("tab_filiais");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		mostrarForm(id);
	}
	
	
	
	
	
	function mostrarForm(id){

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"getForm",
				path:"/filiais/",
				classe:"Filiais",
				id_filial:id
			},
			function(retorno){ 	
					
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else
					mostrarDialogo("form_edicao_filiais", aux.msg);
						
			}
		);		
	}
	
	
	
	
	
	function salvarFilial(id){

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarFilial",
				path:"/filiais/",
				classe:"Filiais",
				id_filial:id,
				codigo:$('#codigo').val(),
				nome:$('#nome').val(),
				endereco:$('#endereco').val(),
				tel_1:$('#tel_1').val(),
				tel_2:$('#tel_2').val(),
				email:$('#email').val(),
				site:$('#site').val()
			},
			function(retorno){ 
					
				console.log(retorno);
					
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					alert("Operação realizada com sucesso.");
			
					carregaPagina("op=FLS");	
				}			
			}
		);		
	}
	
	
	

	
	
	function ativarDesativar(){
		
		var id = getIdSelecionado("tab_filiais");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"ativarDesativar",
				path:"/filiais/",
				classe:"Filiais",
				id_filial:id,
			},
			function(retorno){ 
				
				var erro = false;
				var aux;
					try { 
						aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
					}
					catch (e) { 
						erro = true; 
					} 
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					alert("Operação realizada com sucesso.");
			
					carregaPagina("op=FLS");	
				}			
			}
		);	
	}
	
	
	
	