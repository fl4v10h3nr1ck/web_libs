


	function novoCargo(){
		
		mostrarForm(0);
	}
	
	
	
	
	
	
	function editarCargo(){
		
	var id = getIdSelecionado("tab_cargos");
	
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
				path:"/cargos/",
				classe:"Cargos",
				id_cargo:id
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
					
					mostrarDialogo("form_edicao_cargos", aux.msg);
				
				}		
			}
		);		
	}
	
	
	
	
	
	function salvarCargo(id){

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarCargo",
				path:"/cargos/",
				classe:"Cargos",
				id_cargo:id,
				codigo:$('#codigo').val(),
				nome:$('#nome').val(),
				departamento:$('#departamento').val()
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
			
					carregaPagina("op=CRG");	
				}			
			}
		);		
	}
	
	
	
	
	

	
	function ativarDesativar(){
		
		var id = getIdSelecionado("tab_cargos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"ativarDesativar",
				path:"/cargos/",
				classe:"Cargos",
				id_cargo:id
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
			
					carregaPagina("op=CRG");	
				}			
			}
		);	
	}
	
	
	
	
	
	
	function deletarCargo(){
		
		var id = getIdSelecionado("tab_cargos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		

		if(!confirm("Tem certeza que deseja excluir este cargo (operação irreversível)?"))
			return;
		
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"deletarCargo",
				path:"/cargos/",
				classe:"Cargos",
				id_cargo:id
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
			
					carregaPagina("op=CRG");	
				}			
			}
		);	
	}
	
	
	
	
	
	
	
	

	function mudaDepartamento(){
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"mudaDepartamento",
				path:"/cargos/",
				classe:"Cargos",
				filial:$('#filial').val()
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
				else
					$("#div_departamento").html(aux.msg);			
			}
		);	
	}
	
	
	
	
	