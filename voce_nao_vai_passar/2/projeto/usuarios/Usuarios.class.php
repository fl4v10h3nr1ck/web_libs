 <?php

if(!isset($_SESSION))
session_start();

chdir(dirname(__FILE__)); 

chdir('../');

include_once getcwd().'/base/VNVPBase.class.php';

include_once VNVP_PATH_TAB_ABS.'Tabela.class.php';
include_once VNVP_PATH_DIA_ABS.'Dialogo.class.php';


final class Usuarios extends VNVPBase{


private $tab;

private $dia;



	function __construct() {
		
		parent::__construct();
		
		$_SESSION['PODE_EDITAR'] = false;
		
		$this->tab= new Tabela();	
		
		$this->dia= new Dialogo();
	}

	
	


	
	public function dependencias(){
	
		echo "
		
		<script src='".VNVP_PATH_SMP."usuarios/usuarios.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".VNVP_PATH_SMP."usuarios/usuarios.css' type='text/css' media='all'>";
	
		$this->tab->dependencias();
		
		$this->dia->dependencias();
	}

	
	
	
	
	
	public function conteudo(){

		$usuarioAtual = $this->usuarioAtual(); 
	
		$this->tab->setPathABSDoObjeto(VNVP_PATH_ABS."usuarios/");

		$pode_editar = $this->temPermissao("GEGUSR", ACESSO_EDITAR);
		$pode_excluir = $this->temPermissao("GEGUSR", ACESSO_EXCLUIR);
		
		
		$form = "";
		
		if($pode_editar || $pode_excluir){		

			$form .= "
			<div id='area_opcoes_direita'>
				<table>
					<tr>";
			
			
			if($pode_editar){	
				
				$_SESSION['PODE_EDITAR'] = true;
				
				$this->tab->setFuncaoDuploClick("editarUsuario");
				
				$form .= "	<td align='center' class='opcao_vnvp'>
								<button onclick='javascript:novoUsuario()'>
									<img src='".VNVP_PATH_SMP."imgs/novo.png' class='bt_vnvp'>
								</button>
								<br>Novo
							</td><td  align='center' class='opcao_vnvp'>
								<button onclick='javascript:editarUsuario()'>
									<img src='".VNVP_PATH_SMP."imgs/alterar.png' class='bt_vnvp'>
								</button>
								<br>Editar
							</td>";
			}

			if($pode_excluir)	
				$form .= "	<td  align='center' class='opcao_vnvp'>
								<button onclick='javascript:ativarDesativar()'>
									<img src='".VNVP_PATH_SMP."imgs/ativar.png' class='bt_vnvp'>
								</button>
								<br>Ativ./Desativ.
							</td>";
			
/*			
			if($this->temPermissao("GEGFIL"))					
				$form .= "	<td  align='center' class='opcao_vnvp'>
								<button onclick='javascript:adicionarFiliais()'>
									<img src='".VNVP_PATH_SMP."imgs/transferir.png' class='bt_vnvp'>
								</button>
								<br>Filiais
							</td>";
							
*/							
							
			if($this->temPermissao("GEGCAR"))								
				$form .= "	<td  align='center' class='opcao_vnvp'>
								<button onclick='javascript:adicionarCargos()'>
									<img src='".VNVP_PATH_SMP."imgs/cargos.png' class='bt_vnvp'>
								</button>
								<br>Cargo
							</td>";
				
			if($pode_editar)								
				$form .= "	<td  align='center' class='opcao_vnvp'>
								<button onclick='javascript:resetarSenha(\"".SENHA_PADRAO."\")'>
									<img src='".VNVP_PATH_SMP."imgs/reset.png' class='bt_vnvp'>
								</button>
								<br>Resetar Senha
							</td>
							<td  align='center' class='opcao_vnvp'>
								<button onclick='javascript:clonar()'>
									<img src='".VNVP_PATH_SMP."imgs/clonar.png' class='bt_vnvp'>
								</button>
								<br>Clonar
							</td>";

				
			$form .= "				
						</tr>
					</table>&nbsp;
				</div>
				<div id='area_opcoes_esquerda' align='right'>
					<table>
						<tr>
							<td align='center' class='opcao'>
								<button onclick='javascript:window.open(\"".PSNL_PATH_SMP."\", \"_SELF\")'>
									<img src='".PSNL_PATH_SMP."imgs/voltar.png' class='bt_opcao'>
								</button>
								<br>Voltar
							</td>
						</tr>
					</table>
				</div>";
			
			$form .= $this->tab->getTabela("BeanUsuario", 'tab_usuarios');
			
			$form .= $this->dia->getForm("", "form_edicao_usuarios", "Cadastro de Usuário", 70, 250);
		
			$form .= $this->dia->getForm("", "form_add_filiais", "Adição de Filiais", 80, 400);
			
			$form .= $this->dia->getForm("", "form_add_cargos", "Adição de Cargos", 70, 400);
			
			$form .= $this->dia->getForm("", "form_clonar", "Clonar Usuários", 70, 250);
		
		}
		else{
			
			$form .= "	
			<div id='area_opcoes_direita'>
				<br>
			</div>
			<div id='area_opcoes_esquerda' align='right'>
				<table>
					<tr>
						<td align='center' class='opcao'>
							<button onclick='javascript:window.open(\"".PSNL_PATH_SMP."\", \"_SELF\")'>
								<img src='".PSNL_PATH_SMP."imgs/voltar.png' class='bt_opcao'>
							</button>
							<br>Voltar
						</td>
					</tr>
				</table>
			</div>";	
		
			$form .=  $this->getFormDeUsuario($usuarioAtual->id);
		}
		
		
		echo $form;
	}
	
	
	
	
	
	private function getFormDeUsuario($id_usuario){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
		
	
		$bd = new BdUtil();
		
		$usuario = $bd->getPorId(new BeanUsuario(), $id_usuario);

		if(!is_object($usuario))
			$usuario  = new BeanUsuario();
	
		$form = "
				<div id='div_usuario' class='item_form'>
					Usuário (para logar):<span class='campo_obrigatorio'>*</span><br>	
					<input type='text' id='usuario' value='".$usuario->usuario."'>
				</div>
				<div id='div_nome' class='item_form'>
					Nome Completo:<span class='campo_obrigatorio'>*</span><br>
					<input type='text' id='nome' value='".$usuario->nome_completo."'>
				</div>
				<div id='div_email' class='item_form'>
					E-mail:<span class='campo_obrigatorio'>*</span><br>
					<input type='text' id='email' value='".$usuario->email."'>
				</div>
				<div id='div_tel' class='item_form'>
					Fone:<span class='campo_obrigatorio'>* </span><span class='indicadores_ajuda'>(xx) xxxxx-xxxx</span><br>
					<input type='text' id='tel' value='".$usuario->tel."' maxlength='15' onchange='javascript:mascara(this, formatarTEL);'>
				</div>
				<div id='div_cel' class='item_form'>
					Celular: <span class='indicadores_ajuda'>(xx) xxxxx-xxxx</span><br>
					<input type='text' id='cel' value='".$usuario->cel."' maxlength='15' onchange='javascript:mascara(this, formatarTEL);'>
				</div>";
			
				
		if($usuario->id>0)				
			$form .= "
				<div style='clear:both'></div>
				<div id='div_trocar_senha' align='left'>
					<br>
					<input type='checkbox' id='trocar_senha' onChange='javascript:trocarSenha()'> Trocar Senha.<br>
				</div>";
													
		$form .= "	
				<div style='clear:both'></div>
				<div id='div_senha' align='left'>
					Senha:<span class='campo_obrigatorio'>* </span></span><span class='indicadores_ajuda'>(a-z A-Z 0-9) entre 6 e 18 dígitos</span><br>
					<input type='password' id='senha' value=''>
				</div>
				<div id='div_repete_senha' align='left'>
					Repita a Senha:<br>
					<input type='password' id='repete_senha' value=''>
				</div>";
								
		$form .= "	
				<div style='clear:both'></div>
				<div align='center'>
					<div class='vnvp_bt' id='bt_salvar_usuario' onclick='javascript:salvarUsuario(".($usuario->id>0?$usuario->id:0).")'>
						Salvar Usuário
					</div>	
					<br><br>
				</div>";
				
				
		return $form;
	}
	
	
	
	
	
	
	public function getForm(){
	
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$_POST['id_usuario'] = $comuns->anti_injection($_POST['id_usuario']);


		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($this->getFormDeUsuario($_POST['id_usuario'])).'"}';
	}
		
		
		
	
		
/*	
	private function getFiliais($id_filial, &$bd){
	
		include_once VNVP_PATH_ABS."filiais/BeanFilial.class.php";
		
		$form ="<div id='div_filial' class='item_form'>
					Filial Padrão:
					<select id='filial'>
						<option value='0'> ... </option>";
		
		$filiais = $bd->getPorQuery(new BeanFilial(), null, "###.status>0", null);
		
			if(count($filiais)>0){
							
				foreach($filiais as $filial)					
					$form .= "	
						<option value='".$filial->id."' ".($id_filial==$filial->id?"selected":"").">(".$filial->codigo.") ".$filial->nome."</option>";			
							
			}				
		
		$form .= "	</select>
				</div>";		
		
		return $form;
	}
	
*/	



	
	public function salvarUsuario(){
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$erros = self::validaUsuario($bd, $comuns);
	
			if(strlen($erros) == 0){

				$usuario = null;
	
				if($_POST['id_usuario']>0)
					$usuario = $bd->getPorId(new BeanUsuario(), $_POST['id_usuario']);
				
				if(!is_object($usuario))
					$usuario = new BeanUsuario();
			

				$usuario->usuario  =  		$_POST['usuario'];
				$usuario->nome_completo = 	$_POST['nome'];
				$usuario->email = 			$_POST['email'];
				$usuario->tel = 			$_POST['tel'];
				$usuario->cel = 			$_POST['cel'];
		
				if($usuario->id<=0){
			
			
					$usuario->status 		 = 1;
					$usuario->data_cadastro  = date("Y-m-d");
					$usuario->senha 		 = hash('sha256', $_POST['senha']);
					
					$usuario->id = $bd->novo($usuario);
			
					if($usuario->id<=0){
					
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
					/*
					if($this->novaFilialPadrao($bd, $usuario->id, $_POST['filial'])<=0){
					
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}*/
				}
				else{
			
					if(strlen($_POST['senha'])>0)
						$usuario->senha 	= hash('sha256', $_POST['senha']);
			
					if(!$bd->altera($usuario)){
			
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
					
					/*
					// transforma todas as filiais do usuario em nao padrao
					if(!$bd->executaQuery("update usuarios_x_filiais set padrao=0 where fk_usuario=".$usuario->id)){
			
					echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
					return;
					}
					
					$filial = $bd->getPrimeiroOuNada(new BeanUsuarioFilial(), 
											null, 
												"###.fk_usuario=".$usuario->id." and ###.fk_filial=".$_POST['filial'],
												null);
						
						// torna existente padrao
					if(is_object($filial)){
					
						$filial->padrao = 1;
						
						if(!$bd->altera($filial)){
				
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
						}
					}
					else{
							
							// cria nova padrao
						if($this->novaFilialPadrao($bd, $usuario->id, $_POST['filial'])<=0){
					
							echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
							return;
						}
					}*/
				}
			
				echo '{"status":"sucesso"}';	
				return;
			}
				
		
		echo '{"status":"ERRO", "erro":"'.$erros.'"}';
	}
	
	
	
	

	
	public function validaUsuario(&$bd, &$comuns){
	
		$_POST['usuario'] 			= $comuns->anti_injection($_POST['usuario']);
		$_POST['nome'] 				= $comuns->anti_injection($_POST['nome']);
		$_POST['email'] 			= $comuns->anti_injection($_POST['email']);
		$_POST['tel'] 				= $comuns->anti_injection($_POST['tel']);
		$_POST['cel'] 				= $comuns->anti_injection($_POST['cel']);
		$_POST['cargo'] 			= $comuns->anti_injection($_POST['cargo']);
		
		if(strlen($_POST['usuario']) == 0)
			return "Informe um nome de usuário.";
		
		
		$reg = $bd->getPrimeiroOuNada(new BeanUsuario(), 
											null, 
												"###.usuario='".$_POST['usuario']."'".($_POST['id_usuario']>0?" and id_usuario<>".$_POST['id_usuario']:""),
												null);
		
		if($reg!=null)
			return "O nome de usuário informado já está sendo usado por outro usuário.";
		
		if(strlen($_POST['nome']) == 0)
			return "Informe seu nome completo.";
		
		if(!$comuns->validaEmail($_POST['email']))
			return "informe um endereço de E-mail válido.";
		
		
		if( !$comuns->validaTEL($_POST['tel']))
			return "Informe um número de telefone válido.";
		
			if(strlen($_POST['cel']) > 0){	
				
				if( !$comuns->validaTEL($_POST['cel']))
					return "Informe um número de celular válido.";
			}
		
/*		
		if($_POST['filial']<=0)
			return "selecione um filial padrão para o usuário.";
*/		
			
		if($_POST['id_usuario']==0 && strlen($_POST['senha'])==0)
			return "Informe uma senha para o usuario.";
			
			if(strlen($_POST['senha'])>0){
			
				if(strlen($_POST['senha'])<6)
					return "A senha deve ter ao menos 6 dígitos.";
			
				if(strcmp($_POST['senha'], $_POST['repete_senha'])!=0)
					return "As senhas informadas não são iguais.";
			}
		
		return "";
	}



	
	
		
	public function ativarDesativar(){
			
	include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
	include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
	
	$comuns = new Comuns();
	$bd = new BdUtil();
	
	$_POST['id_usuario'] = 	$comuns->anti_injection($_POST['id_usuario']);
	
	$usuario = $bd->getPorId(new BeanUsuario(), $_POST['id_usuario']);
				
		if(is_object($usuario)){
			
			if($usuario->status>0)
				$usuario->status = 0;
			else
				$usuario->status = 1;
			
			if($bd->altera($usuario)){
			
				echo '{"status":"sucesso"}';
				return;
			}
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	

	

/*	
	private function novaFilialPadrao(&$bd, $id_usuario, $id_filial){
		
		$userFilial = new BeanUsuarioFilial();
					
		$userFilial->fk_usuario = $id_usuario;
		$userFilial->fk_filial = $id_filial;
		$userFilial->padrao = 1;
					
		return $bd->novo($userFilial);
	}
	
	

	
/*	
	
	public function getFormDeFiliais(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'filiais/BeanFilial.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$bd = new BdUtil();
		$comuns = new Comuns();
	
		$form = "
			<div class='area_lista_filiais'>
				<div style='margin:10px 0px 10px 10px'>
					<b>Filiais disponíveis:</b>
				</div>
				<div id='area_para_add'>
				".$this->getParaAdicionar($bd)."
				</div>
			</div>
			<div id='area_transferir_filiais' align='center'>
				<div class='vnvp_bt' id='bt_transferir_filial' onclick='javascript:transferirFilial(".$_POST['id_usuario'].")'>
					&raquo ADD &raquo;
				</div>	
			</div>
			<div class='area_lista_filiais'>
				<div style='margin:10px 0px 10px 10px'>
					<b>Filiais do Usuário:</b>
				</div>
				<div id='area_ja_add'>
				".$this->getjaAdicionados($bd)."
				</div>
			</div>
			<div style='clear:both'></div>";
		
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
	
	
	
	private function getParaAdicionar(&$bd){
		
		$para_add = $bd->getPorQuery(new BeanFilial(), 
											null, 
											"###.id_filial NOT IN (select fk_filial from usuarios_x_filiais where fk_usuario=".$_POST['id_usuario'].")", 
											null);
		
		$form = "
				<table class='tab_lista' cellspacing=0 id='tab_para_add'>
					<tr>
						<th align='center'>
						NOME
						</th>
					</tr>";
		
		if(count($para_add)>0){
			
			foreach($para_add as $filial)
				$form .= "
					<tr class='linha_para_add' id='linha_para_add_".$filial->id."' onclick='javascript:selecionaListaDeFiliais(".$filial->id.")'> 
						<td align='left'>
						(".$filial->codigo.") ".$filial->nome."
						</td>
					</tr>";	
		}		
						
		$form .= "
			</table>";
			
		return $form;
	}
	
	
	
	
	
	private function getjaAdicionados(&$bd){
		
		include_once VNVP_PATH_ABS.'filiais/BeanUsuarioFilial.class.php';
		
		$ja_add = $bd->getPorQuery(new BeanFilial(), 
											null, 
											"###.id_filial IN (select fk_filial from usuarios_x_filiais where fk_usuario=".$_POST['id_usuario'].")", 
											null);

		$form = "
				<table class='tab_lista' cellspacing=0  id='tab_ja_add'>
					<tr>
						<th align='center' width='15%'>
						PADRÃO
						</th>
						<th align='center' width='85%'>
						NOME
						</th>
					</tr>";
		
		if(count($ja_add)>0){

			foreach($ja_add as $filial){
				
				$x = $bd->getPrimeiroOuNada(new BeanUsuarioFilial(), 
											null, 
											"###.fk_filial=".$filial->id." and ###.fk_usuario=".$_POST['id_usuario'], 
											null);
				
				$form .= "	
					<tr id='linha_ja_add_".$filial->id."'>
						<td align='center'>
							<input type='radio' name='radio_filial_padrao' id='filial_padrao_".$filial->id."' ".(is_object($x) && $x->padrao>0?"checked":"")." onclick='javascript:mudaFilialPadrao(".$filial->id.", ".$_POST['id_usuario'].")'>		
						</td>
						<td align='left'>
							<table width='100%'>
								<tr>
									<td align='left' width='90%' style='border:none'>
									(".$filial->codigo.") ".$filial->nome."
									</td>
									<td align='center' width='10%' style='border:none'>
										<div class='vnvp_bt vnvp_bt_vermelho bt_remover' style='margin-top:0px' onclick='javascript:removerFilialDeUsuario(".$_POST['id_usuario'].", ".$filial->id.")'>
										X
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>";	
			}
					
		}	
		
		$form .= "
			</table>";
		
		return $form;
	}
	
	
	
	
	
	public function salvarAdicaoDeFilial(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'filiais/BeanFilial.class.php';
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
		include_once VNVP_PATH_ABS.'filiais/BeanUsuarioFilial.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
		
		$_POST['id_filial'] = $comuns->anti_injection($_POST['id_filial']);
		$_POST['id_usuario'] = $comuns->anti_injection($_POST['id_usuario']);
		
		
		$para_add = $bd->getPorId(new BeanFilial(), $_POST['id_filial']);
	
		$usuario = $bd->getPorId(new BeanUsuario(), $_POST['id_usuario']);
		
		$cont = $bd->cont(new BeanUsuarioFilial(), 
							null, 
								"###.fk_usuario=".$_POST['id_usuario'], 
								null);
		
		
		$msg ="";
		
		if(is_object($para_add) &&
			is_object($usuario)){
			
			$add = new BeanUsuarioFilial;
				
			$add->fk_filial = $para_add->id;
			$add->fk_usuario = $usuario->id;
			$add->padrao = $cont<=0?1:0;
			
			$add->id = $bd->novo($add);
			
			if($add->id<=0){
					
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
			
			$msg =	'["'.$comuns->preparaHTMLParaJson($this->getParaAdicionar($bd)).
					'","'.$comuns->preparaHTMLParaJson($this->getjaAdicionados($bd)).
					'"]';
				
		}
		else{
			
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
			return;	
		}
		
		echo '{"status":"sucesso", "msg":'.$msg.'}';
	}
	
	
	
	
	
	public function removerFilialDeUsuario(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'filiais/BeanFilial.class.php';
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
		include_once VNVP_PATH_ABS.'filiais/BeanUsuarioFilial.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
		
		$para_remover = $bd->getPorId(new BeanFilial(), $comuns->anti_injection($_POST['id_filial']));
	
		if(is_object($para_remover)){
		
			if(!$bd->deletaPorQuery(new BeanUsuarioFilial(), 
							"fk_usuario=".$comuns->anti_injection($_POST['id_usuario']).
								" and fk_filial=".$para_remover->id)){
				
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}

			$cont = $bd->cont(new BeanUsuarioFilial(), 
							null, 
								"###.fk_usuario=".$_POST['id_usuario']." and ###.padrao>0", 
								null);
			
				if($cont<=0){
					
				$novo_padrao = $bd->getPrimeiroOuNada(new BeanUsuarioFilial(), 
														null, 
															"###.fk_usuario=".$_POST['id_usuario'],
																null);	
					if(is_object($novo_padrao)){
						
						$novo_padrao->padrao=1;
						
						if(!$bd->altera($novo_padrao)){
			
							echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
							return;
						}
					}
				}
			
			$msg =	'["'.$comuns->preparaHTMLParaJson($this->getParaAdicionar($bd)).
					'","'.$comuns->preparaHTMLParaJson($this->getjaAdicionados($bd)).
					'"]';
			
			echo '{"status":"sucesso", "msg":'.$msg.'}';			
			return;
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
*/
	
	
	
	public function mudaFilialPadrao(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioCargo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
		
		$_POST['id_cargo'] = $comuns->anti_injection($_POST['id_cargo']);
		$_POST['id_usuario'] = $comuns->anti_injection($_POST['id_usuario']);
		
			
		$para_mudar = $bd->getPrimeiroOuNada(new BeanUsuarioCargo(), 
											null, 
											"###.fk_cargo=".$_POST['id_cargo']." and ###.fk_usuario=".$_POST['id_usuario'],
											null);
		
		if(is_object($para_mudar)){
			
			//torna todos nao padrao
			if(!$bd->executaQuery("update usuarios_x_cargos set padrao=0 where fk_usuario=".$_POST['id_usuario'])){
			
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
		
			$para_mudar->padrao = 1;
		
			if(!$bd->altera($para_mudar)){
			
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
			
			echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($this->getListaDeCargosDeUsuario($bd, $_POST['id_usuario'])).'"}';
			return;
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}


	
	
	
	public function getFormDeCargos(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$bd = new BdUtil();
		$comuns = new Comuns();
	
		$form = "
			<div id='div_filiais_cargo'>
				".$this->getFiliaisDeCargo($bd, $_POST['id_usuario'])."
			</div>
			<div id='div_departamentos_cargo'>
				".$this->getDepartamentosDeCargo($bd, 0)."
			</div>
			<div id='div_cargos_cargo'>
				".$this->getCargos($bd, 0)."
			</div>
			<div style='clear:both'></div>
			<div align='center'>
				<div class='vnvp_bt' id='bt_add_cargo' onclick='javascript:addNovoCargo(".$_POST['id_usuario'].")'>
				Adicionar Cargo
				</div>
			</div>
			<div id='div_area_cargos_adds'>
				".$this->getListaDeCargosDeUsuario($bd, $_POST['id_usuario'])."
			</div>";
		
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
	
	
	
	private function getFiliaisDeCargo(&$bd, $id_usuario){
	
		include_once VNVP_PATH_ABS."filiais/BeanFilial.class.php";
		
		$form ="	Filial Disponível:
					<select id='filial_cargo' onChange='javascript:mudaFilialDeCargo()'>
						<option value='0'> ... </option>";
	
		$filiais = $bd->getPorQuery(new BeanFilial(), null, 
		"###.status>0 and ###.id_filial NOT IN (select dpt.fk_filial from usuarios_x_cargos as x inner join cargos as crg on crg.id_cargo=x.fk_cargo inner join departamentos as dpt on crg.fk_departamento=dpt.id_departamento where x.fk_usuario = ".$id_usuario.")", null);
		
			if(count($filiais)>0){
							
				foreach($filiais as $filial)					
					$form .= "	
						<option value='".$filial->id."'>(".$filial->codigo.") ".$filial->nome."</option>";			
							
			}				
		
		$form .= "	</select>";		
		
		return $form;
	}
	
	

	
	

	private function getDepartamentosDeCargo(&$bd, $id_filial){
	
		include_once VNVP_PATH_ABS."departamentos/BeanDepartamento.class.php";
		
		$form ="	Departamentos:
					<select id='departamento_cargo' onChange='javascript:mudaDepartamentoDeCargo()'>
						<option value='0'> ... </option>";
	
		$departamentos = $bd->getPorQuery(new BeanDepartamento(), null, "###.status>0 and ###.fk_filial=".$id_filial, null);
		
			if(count($departamentos)>0){
							
				foreach($departamentos as $departamento)					
					$form .= "	
						<option value='".$departamento->id."'>".$departamento->nome."</option>";			
							
			}				
		
		$form .= "	</select>";		
		
		return $form;
	}
	
	
	
	
	
	
	private function getCargos(&$bd, $id_departamento){
	
		include_once VNVP_PATH_ABS."cargos/BeanCargo.class.php";
		
		$form ="	Cargos:
					<select id='cargo_cargo'>
						<option value='0'> ... </option>";
	
		$cargos = $bd->getPorQuery(new BeanCargo(), null, "###.status>0 and ###.fk_departamento=".$id_departamento, null);
		
			if(count($cargos)>0){
							
				foreach($cargos as $cargo)					
					$form .= "	
						<option value='".$cargo->id."'>".$cargo->nome."</option>";			
							
			}				
		
		$form .= "	</select>";		
		
		return $form;
	}
	
	
	
	
	
	private function getListaDeCargosDeUsuario(&$bd, $id_usuario){
		
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioCargo.class.php';
		
		$form ="
				<table class='tab_lista' width='100%' style='margin-top:20px'>
					<tr>
						<th width='10%' align='center'></th>
						<th width='25%' align='center'>Filial</th>
						<th width='25%' align='center'>Departamento</th>
						<th width='25%' align='center'>Cargo</th>
						<th width='15%' align='center'>Padrão</th>
					</tr>";
	
		$cargos = $bd->getPorQuery(new BeanUsuarioCargo(), null, "###.fk_usuario=". $id_usuario, "###.id_usuario_cargo ASC");
		
			if(count($cargos)>0){
							
				foreach($cargos as $cargo){					
					$form .= "
					<tr>					
						<td align='center'>
							<div class='vnvp_bt vnvp_bt_vermelho bt_remover' style='margin-top:0px' onclick='javascript:removerCargo(".$id_usuario.", ".$cargo->fk_cargo.")'>
							X
							</div>
						</td>
						<td align='center'>".$cargo->nome_filial."</td>
						<td align='center'>".$cargo->nome_departamento."</td>
						<td align='center'>".$cargo->nome_cargo."</td>
						<td align='center'>";
						
				if($cargo->padrao>0)
					$form .= "<b>PADRAO</b>";
				else		
					$form .= "
							<input  id='cargo_padrao_".$cargo->id."' class='switch switch--shadow' type='checkbox' onChange='javascript:mudaFilialPadrao(".$cargo->fk_cargo.", ".$id_usuario.")'>
							<label for='cargo_padrao_".$cargo->id."'></label>";
							
				$form .= "			
						</td>
					</tr>";	
				}					
							
			}				
		
		$form .= "	</table>";		
		
		return $form;
	
	}
	
	
	
	
	
	public function mudaFilialDeCargo(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($this->getDepartamentosDeCargo($bd, $_POST['filial'])).'"}';
	}
	
	
	
	
	
	
	public function mudaDepartamentoDeCargo(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($this->getCargos($bd, $_POST['departamento'])).'"}';
	}
	
	
	
	
	
	
	public function addNovoCargo(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioCargo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
		
		$_POST['id_cargo'] = $comuns->anti_injection($_POST['id_cargo']);
		$_POST['id_usuario'] = $comuns->anti_injection($_POST['id_usuario']);
		
			if($_POST['id_usuario']<=0){
					
				echo '{"status":"ERRO", "erro":"usuário não encontrado."}';
				return;
			}
			
			if($_POST['id_cargo']<=0){
					
				echo '{"status":"ERRO", "erro":"Selecione um cargo para adição."}';
				return;
			}

			$cont_padrao = $bd->cont(new BeanUsuarioCargo, null, "###.fk_usuario=".$_POST['id_usuario']." and ###.padrao>0", null);
			
			
			
			$add = new BeanUsuarioCargo;
				
			$add->fk_cargo = 	$_POST['id_cargo'];
			$add->fk_usuario =	 $_POST['id_usuario'];
			
			if($cont_padrao<=0)
				$add->padrao =	 1;
			
			
			$add->id = $bd->novo($add);
			
			if($add->id<=0){
					
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}	
			
		echo '{"status":"sucesso", "form":"'.$comuns->preparaHTMLParaJson($this->getListaDeCargosDeUsuario($bd, $_POST['id_usuario'])).'"}';
	}
	
	
	
	
	
	
	public function removerCargo(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioCargo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
		
		
		$padrao = $bd->cont(new BeanUsuarioCargo, null, "###.fk_usuario=".$_POST['id_usuario']." and ###.fk_cargo=".$_POST['id_cargo']." and ###.padrao>0", null);
			
		
		if(!$bd->deletaPorQuery(new BeanUsuarioCargo(), 
							"fk_usuario=".$comuns->anti_injection($_POST['id_usuario']).
								" and fk_cargo=".$comuns->anti_injection($_POST['id_cargo']))){
				
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
			return;
		}
		
		
		if($padrao>0){
			
			$cargo = $bd->getPrimeiroOuNada(new BeanUsuarioCargo, null, "###.fk_usuario=".$_POST['id_usuario'], "###.id_usuario_cargo ASC");
		
			if(is_object($cargo)){
				
				$cargo->padrao = 1;
				$bd->altera($cargo);
			}
		}
		
		echo '{"status":"sucesso", "form":"'.$comuns->preparaHTMLParaJson($this->getListaDeCargosDeUsuario($bd, $_POST['id_usuario'])).'"}';
	}


	
	
	
	
	
	public function resetarSenha(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
	
		$bd = new BdUtil();
	
		$usuario = null;
	
		if($_POST['id_usuario']>0)
			$usuario = $bd->getPorId(new BeanUsuario(), $_POST['id_usuario']);
				
		if(!is_object($usuario)){
		
			echo '{"status":"ERRO", "erro":"usuário não encontrado."}';
			return;
		}
			
		$usuario->senha 		 = hash('sha256', SENHA_PADRAO);
					
		if(!$bd->altera($usuario)){
			
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
			return;
		}
						
		echo '{"status":"sucesso"}';	
	}
	
	
	

	
	public function getFormClonar(){
		
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
	
		$bd = new BdUtil();
		$comuns = new Comuns();
	
	
		$usuario = $bd->getPorId(new BeanUsuario(), $_POST['id_usuario']);

		if(!is_object($usuario))
			$usuario  = new BeanUsuario();
	
		$form = "
			<div id='div_clonar'>
				Copiar grupos, filiais, departamentos e cargos de:<br>
				<input type='text' disabled id='usuario_de' value='".$usuario->usuario."'>
				<br><br>
				Para:<br>
				<select id='usuario_para'>
					<option value='0'>...</option>";
					
					
		$usuarios = $bd->getPorQuery(new BeanUsuario(), null, "###.status>0 and ###.id_usuario<>".$usuario->id, "###.usuario ASC");
		
			if(count($usuarios)>0){
							
				foreach($usuarios as $valor)					
					$form .= "	
						<option value='".$valor->id."'>(".$valor->usuario.") ".$valor->nome_completo."</option>";			
							
			}				
		
		$form .= "
				</select>		
			</div>
			<br>
			<div align='center'>
				<div class='vnvp_bt' id='bt_clonar' onclick='javascript:clonarUsuario(".$usuario->id.")'>
					Clonar
				</div>	
				<br><br>
			</div>";
		
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
	
	
	
	public function clonarUsuario(){
		
	
		include_once VNVP_PATH_BD_ABS."BdUtil.class.php";
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioGrupo.class.php';
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioCargo.class.php';
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';		

		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['id_para'] 			= $comuns->anti_injection($_POST['id_para']);
		$_POST['id_de'] 			= $comuns->anti_injection($_POST['id_de']);
		
		if($_POST['id_de']<=0){
		
			echo '{"status":"ERRO", "erro":"Nenhum usuário origem encontrado."}';
			return;
		}
		
		
		if($_POST['id_para']<=0){
		
			echo '{"status":"ERRO", "erro":"Selecione um usuário destino."}';
			return;
		}
		
		$bd->deletaPorQuery(new BeanUsuarioGrupo(), "fk_usuario=".$_POST['id_para']);
		
		$grupos = $bd->getPorQuery(new BeanUsuarioGrupo(), null, "###.fk_usuario=".$_POST['id_de'], null);
		
		if(count($grupos)>0){
		
			foreach($grupos as $grupo){
				
				$add = new BeanUsuarioGrupo;
					
				$add->fk_usuario = 	$_POST['id_para'];
				$add->fk_grupo =	$grupo->fk_grupo;
				
				$bd->novo($add);
			}
		}
		
		/*
		$bd->deletaPorQuery(new BeanUsuarioFilial(), "fk_usuario=".$_POST['id_para']);
		
		$filiais = $bd->getPorQuery(new BeanUsuarioFilial(), null, "###.fk_usuario=".$_POST['id_de'], null);
		
		foreach($filiais as $filial){
			
			$add = new BeanUsuarioFilial;
				
			$add->fk_usuario = 	$_POST['id_para'];
			$add->fk_filial =	$filial->fk_filial;
			$add->padrao 	=	$filial->padrao;
			
			$bd->novo($add);
		}
		*/

		$bd->deletaPorQuery(new BeanUsuarioCargo(), "fk_usuario=".$_POST['id_para']);
		
		$cargos = $bd->getPorQuery(new BeanUsuarioCargo(), null, "###.fk_usuario=".$_POST['id_de'], null);
		
		foreach($cargos as $cargo){
			
			$add = new BeanUsuarioCargo;
				
			$add->fk_usuario = 	$_POST['id_para'];
			$add->fk_cargo =	$cargo->fk_cargo;
			
			$bd->novo($add);
		}
		
		
		echo '{"status":"sucesso"}';
	}
	
	
}


?>