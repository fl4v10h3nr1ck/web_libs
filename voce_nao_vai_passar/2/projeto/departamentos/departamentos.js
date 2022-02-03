


	function novoDepartamento(){
		
		mostrarForm(0);
	}
	
	
	
	
	
	
	function editarDepartamento(){
		
	var id = getIdSelecionado("tab_departamentos");
	
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
				path:"/departamentos/",
				classe:"Departamentos",
				id_departamento:id
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
					
					mostrarDialogo("form_edicao_departamento", aux.msg);
				
				}		
			}
		);		
	}
	
	
	
	
	
	
	function salvarDepartamento(id){

		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarDepartamento",
				path:"/departamentos/",
				classe:"Departamentos",
				id_departamento:id,
				codigo:$('#codigo').val(),
				nome:$('#nome').val(),
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
				else{
					
					alert("Operação realizada com sucesso.");
			
					carregaPagina("op=DPT");	
				}			
			}
		);		
	}
	
	
	
	
	
	
	
	function ativarDesativar(){
		
		var id = getIdSelecionado("tab_departamentos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		jQuery.post(				
		$("#VNVP_PATH_SMP").val()+'acao.php',
			{
				funcao:"ativarDesativar",
				path:"/departamentos/",
				classe:"Departamentos",
				id_departamento:id,
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
			
					carregaPagina("op=DPT");	
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
	
	
	
	