
		
	function login(){
	
		carregando(
			function(){
				
				jQuery.post(
							
				$("#VNVP_PATH_SMP").val()+'acao.php',
					{
						funcao:"tentativaDeLogin",
						path:"/login/",
						classe:"Login",
						usuario:$("#login_usuario").val(), 
						senha:$("#login_senha").val(), 
						salvar:$("#continuar_logado").is(":checked")?1:0
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
						
						if(erro || aux.status!='sucesso'){
						
							$("#login_msg_erro").html("Usuário ou senha inválidos.");
								
							finaliza("area_bt_logar", "area_carregando");
						}
						else
							location.reload();
						
					}
				);		
			},
		"area_bt_logar",
		"area_carregando");
	}
	
	
	
	
	
	function sair(){
	
		carregando(
			function(){
				jQuery.post(
							
				$("#VNVP_PATH_SMP").val()+'acao.php',
					{
						funcao:"sair",
						path:"/login/",
						classe:"Login"
					},
					function(retorno){ 
							
						location.reload();
					}
				);
			},
		"bt_sair",
		"area_carregando");
	}
	
	