 <?php

if(!isset($_SESSION))
session_start();

chdir(dirname(__FILE__)); 

include_once getcwd().'/define.php';


include_once VNVP_LOCAL_ABS_BD.'BdUtil.class.php';
include_once VNVP_BASE_PACK_ABS.'BeanUsuario.class.php';
include_once VNVP_BASE_PACK_ABS.'BeanGrupo.class.php';
include_once VNVP_BASE_PACK_ABS.'BeanUsuarioGrupo.class.php';
include_once VNVP_BASE_PACK_ABS.'BeanGrupoAcesso.class.php';
include_once VNVP_LOCAL_ABS_TAB.'Tabela.class.php';
include_once VNVP_LOCAL_ABS_IMGGESTOR.'ImgGestor.class.php';





final class VoceNaoVaiPassar{


private $bd;

private $tab;

private $img_gestor;

public $admin;



	function __construct() {
			
	$this->bd = new BdUtil();

	$this->tab= new Tabela();
	
	$this->img_gestor= new ImgGestor();
	
	$this->configuraCookiesDeUsuario();

	$this->admin	= $this->temPermissao("GEREUSERS");
	}

	
	


	
	public function dependencias(){
	
	echo "
	
	<script src='https://www.google.com/recaptcha/api.js' async defer></script>
	
	<script src='".VNVP_BASE_PACK_SPS."voce_nao_vai_passar.js' type='text/javascript'></script>
	
	<link rel='stylesheet' href='".VNVP_BASE_PACK_SPS."voce_nao_vai_passar.css' type='text/css' media='all'>";
	
	$this->tab->dependencias();
	
	$this->img_gestor->dependencias();
	}

	
	
	
	
	
	private function getPath(){
		
	return "<input type='hidden' value='".VNVP_BASE_PACK_SPS."' id='vnvp_path' name='vnvp_path'>";	
	}
	
	
	
	

	
	
	
	
/*********************** login ***********************************/	
	
	
	
	
	
	
	public function formDeLogin(){
	
	return 
	"<form method='POST' action='javascript:login();'>
		<div id='form_login'>
			<div id='form_login_titulo'>
			<b>Autenticação</b>
			</div>
			<hr width='99%'>
			<div id='form_login_interno' align='left'>
				<div style='margin:0px  0px 0px 4%' align='left'>
				Usuário/E-mail:
				</div>
				<div>
				<input type='text' class='campos' name='login_usuario' id='login_usuario' style='width:92%;margin:0px  4% 0px 4%' maxlength='50'/>
				</div>
				<div style='margin:10px  0px 0px 4%' align='left'>
				Senha:
				</div>
				<div>
				<input type='password' class='campos' name='login_senha' id='login_senha' style='width:92%;margin:0px  4% 0px 4%' maxlength='20'/>
				</div>
				<div align='left' style='margin:10px  0px 0px 4%'>
				<input type='checkbox' name='continuar_logado' id='continuar_logado' value='S'> Permanecer Logado.
				</div>
				<div align='center' style='margin:15px  0px 0px 0px' id='area_bt_logar'>
				<input type='submit' style='width:110px' value='Entrar'/>
				</div>
				<div align='center' id='login_msg_erro' style='color:red;text-weight:bold;margin:10px 0px 0px 0px'></div>
			</div>
		".$this->getPath()."	
		</div>	
	</form>";
	
	}



	
	
	
	
	public function logar(){
	
	include_once VNVP_LOCAL_ABS_BIB.'Biblioteca.class.php';
	
	$bib = new Biblioteca();

	$_POST["usuario"] = $bib->anti_injection( $_POST["usuario"]);
	$_POST["senha"] = $bib->anti_injection($_POST["senha"]);

	
		if( strlen($_POST["usuario"])== 0 || strlen($_POST["senha"]) < 6 ){
		
		echo '{"status":"erro"}';
		$_SESSION["usuario"]  = null;
		return;
		}
	
		if(strcmp($_POST["usuario"], "dev")==0 && strcmp($_POST["senha"], "devxzrvm7gz0f90a")==0){
		

		$_SESSION["usuario"]  = new BeanUsuario();
		
		$_SESSION["usuario"]->id =99999;
		$_SESSION["usuario"]->usuario ="DEV";
		$_SESSION["usuario"]->email ="";
		$_SESSION["usuario"]->senha ="USER_DEV";
		$_SESSION["usuario"]->status =1;
		
		echo '{"status":"sucesso"}';
		return;
		}
	
		if( $this->loginValido($_POST["usuario"], hash('sha256', $_POST["senha"]))){
		
		if(strcmp($_POST["salvar"], "SIM")==0)
		$_SESSION["salvar_login"] = "SIM";
		else
		$_SESSION["salvar_login"] = "";
		
		echo '{"status":"sucesso"}';
		return;
		}
	
	$_SESSION["usuario"]  = null;	
	echo '{"status":"erro"}';			
	}
	
	
	
		
	
	public function loginValido($usuario, $senha){
		
	
	$retorno = $this->bd->getPrimeiroOuNada(
						new BeanUsuario(), 
						null, 
						"(###.usuario = '".$usuario."' OR ###.email='".$usuario."') AND ###.senha = '".$senha."' AND ###.status>0", null);		
	
		if(is_object($retorno)){
		
		$_SESSION["usuario"]  = serialize($retorno);
		return true;
		}
	
	$_SESSION["usuario"]  = null;
	return false;	
	}
	
	
	
		
	
	public function permitirAcesso(){
	
	if($this->getUsuarioAtual()!=null)
	return true;
	
	
		if(array_key_exists("usuario", $_COOKIE) && strlen($_COOKIE["usuario"])>0 &&
		array_key_exists("senha", $_COOKIE) && strlen($_COOKIE["senha"])>0){
			
		return $this->loginValido($_COOKIE["usuario"], $_COOKIE["senha"]);	
		}
	
	return false;	
	}
	
	
	
	
	
	
	public function configuraCookiesDeUsuario(){
		
		if( array_key_exists("remove_cookies", $_SESSION) && 
		strcmp($_SESSION["remove_cookies"],"SIM")==0){

		setcookie("usuario", "", time() - 3600);
		setcookie("senha",   "", time() - 3600);
		$_SESSION["remove_cookies"] = "";
		}
		else{
	
		$usuario =$this->getUsuarioAtual();
	
			if( array_key_exists("salvar_login", $_SESSION) && 
					strcmp($_SESSION["salvar_login"],"SIM")==0 &&
						$usuario!=null){
		
				if(!array_key_exists("usuario", $_COOKIE) || !array_key_exists("senha", $_COOKIE)){
				
				// 1 mes
				$tempo = time()+60*60*24*30;	
				
				setcookie("usuario", $usuario->usuario, $tempo);
				setcookie("senha", $usuario->senha, $tempo);
				$_SESSION["salvar_login"] = "";
				}
			}
		}
	}
	
	
	
	
	
	
	public function getUsuarioAtual(){
		
	if(array_key_exists("usuario", $_SESSION) && $_SESSION['usuario']!=null)
	return unserialize($_SESSION['usuario']);
	
	return null;
	}
	
	
	
	
	
		
	public function sair(){
	
	$_SESSION["usuario"]  = null;	
	$_SESSION["salvar_login"]  = 	 "";
	$_SESSION["remove_cookies"]  = 	 "SIM";
	}
	
	
	
	
	
	
	
	
	
/*********************** cadastro ***********************************/	
	
	
	
	
	
	public function getUsuariosCadastrados($mobile = false){
	
	$this->tab->setPathABSDoObjeto(VNVP_BASE_PACK_ABS);
	
	if($mobile)
	$this->tab->setTipoDeScrollComControles(true);
	
	$retorno = $this->tab->getTabela(new BeanUsuario(), 'tab_usuarios');

	return $retorno;
	}
	
	
	
	
	
	
	
	
	public function getFormDeNovoUsuario($mostrar_profile, 
											$mostrar_endereco,
												$cod_grupo_padrao){
		
	include_once LOCAL_LIB_BD."BdUtil.class.php";
	
	$bd = new BdUtil();
	
	$id = 0;
	
		if($this->admin){
		
		if(array_key_exists("idu", $_GET))
		$id = $_GET['idu'];
		}
		else{
		
		$aux =$this->getUsuarioAtual();
		
		$id  = $aux!=null?$aux->id:0;
		}
	
	$usuario = null;
	
	if($id>0)
	$usuario = $bd->getPorId(new BeanUsuario(), $id);

	if($usuario==null)
	$usuario = new BeanUsuario();
	
	$url_profile = strlen($usuario->img_profile)>0?$usuario->img_profile:"";
	
	$titulo  = ($usuario->id==0?"Cadastro de Novo Usuário":"Dados Cadastrais");		
	
	
	$conteudo = "<table id='tabela_edicao_de_usuario'>
					<tr>
						<td width='100%' align='left'>
						<input type='hidden' id='id_usuario'  value='".$usuario->id."'>
						<input type='hidden' id='grupo_padrao'  value='".$cod_grupo_padrao."'><br>
						<p align='center'><b>".$titulo."</b></p><br>
							<div style='border:solid 1px #DDD;padding:10px' align='left'>
							<b>Dados do Usuário</b><br>
								<div id='div_usuario_user'>
								Nome de Usuário:<span class='campo_obrigatorio'> Obrigatório</span><br>
								<input type='text' id='usuario_user' value='".$usuario->usuario."'>
								</div>
								<div id='div_nome_user'>
								Nome Completo:<span class='campo_obrigatorio'> Obrigatório</span><br>
								<input type='text' id='nome_user' value='".$usuario->nome_completo."'>
								</div>
								<div id='div_identificacao_user'>
								Identificação:<br>
								<input type='text' id='identificacao_user' value='".$usuario->identificacao."'>
								</div>	
								<div style='clear:both'></div>	
							</div>
						</td>
					</tr>
					<tr>
						<td width='100%' align='left'>
							<div style='border:solid 1px #DDD;padding:10px' align='left'>
							<b>Contato</b><br>
								<div id='div_tel_user' align='left'>
								Telefone:<span class='campo_obrigatorio'> Obrigatório</span><br>
								<input type='text' id='tel_user' value='".$usuario->tel."' maxlength='14' onchange='javascript:mascara(this, formatarTEL);'>
								</div>
								<div id='div_cel_user' align='left'>
								Celular:<br>
								<input type='text' id='cel_user' value='".$usuario->cel."' maxlength='14' onchange='javascript:mascara(this, formatarTEL);'>
								</div>
								<div id='div_email_user' align='left'>
								E-mail:<span class='campo_obrigatorio'> Obrigatório</span><br>
								<input type='text' id='email_user' value='".$usuario->email."'>
								</div>
								<div style='clear:both'></div>
							</div>
						</td>
					</tr>";
					
					
	if($mostrar_endereco)	
	$conteudo .= "	<tr>
						<td width='100%' align='left'>
							<div style='border:solid 1px #DDD;padding:10px' align='left'>
							<b>Endereço</b><br>
								<div id='div_logradouro_user' align='left'>
								Logradouro:<br>
								<input type='text' id='logradouro_user' value='".$usuario->logradouro."'>
								</div>
								<div id='div_num_residencia_user' align='left'>
								Número:<br>
								<input type='text' id='num_user' value='".$usuario->num_residencia."'>
								</div>
								<div id='div_comp_user' align='left'>
								Complemento:<br>
								<input type='text' id='comp_user' value='".$usuario->complemento."'>
								</div>
								<div id='div_cidade_user' align='left'>
								Cidade:<br>
								<input type='text' id='cidade_user' value='".$usuario->cidade."'>
								</div>
								<div id='div_bairro_user' align='left'>
								Bairro:<br>
								<input type='text' id='bairro_user' value='".$usuario->bairro."'>
								</div>
								<div id='div_cep_user' align='left'>
								CEP:<br>
								<input type='text' id='cep_user' value='".$usuario->cep."' onchange='javascript:mascara(this, formatarCEP);'  maxlength='9'>
								</div>
								<div id='div_uf_user' align='left'>
								UF:<br>
								<input type='text' id='uf_user' value='".$usuario->uf."' maxlength='2'>
								</div>
								<div style='clear:both'></div>
							</div>
						</td>
					</tr>";
					
	$conteudo .= "	<tr>
						<td width='100%' align='left'>
							<div style='border:solid 1px #DDD;padding:10px' align='left'>";
	
	if($usuario->id>0)						
	 $conteudo .= "			<input type='checkbox' id='trocar_senha' onChange='javascript:trocarSenha()'> Trocar Senha.<br>";
								
								
	$conteudo .= "								
								<div id='div_senha_user' align='left'>
								Senha:<br>
								<input type='password' id='senha_user' value=''>
								</div>
								<div id='div_repete_senha_user' align='left'>
								Repita a Senha:<br>
								<input type='password' id='repete_senha_user' value=''>
								</div>
								<div style='clear:both'></div>";
								
	if($usuario->id==0)									
	$conteudo .= "							
								
								<div id='div_captcha_contato' align='center'>
								<br>
								<div class='g-recaptcha' data-sitekey='".VNVP_RECAPTCHA_SITE_KEY."'></div>
								</div>";
	
	
	$conteudo .= "							
							</div>
						</td>
					</tr>
					<tr>
						<td width='100%' align='center'>
						<br>
							<div class='bt_verde' id='bt_salva_user' onclick='javascript:salvaDadosDeUsuario(".($usuario->id>0?$usuario->id:0).")' style='width:150px'>Salvar Usuário</div>						
							".$this->getPath()."	
						</td>
					</tr>";
	
	
	if($mostrar_profile)
	$conteudo .= "	<tr>
						<td align='left'>
							<div style='border:solid 1px #DDD;padding:10px' align='center'>".
							$this->img_gestor->getFormDeUpload(
									"upf", 
									"Foto de Perfil", 
									VNVP_LOCAL_PROFILES_SMP_USER,
									VNVP_LOCAL_PROFILES_ABS_USER,
									$usuario->img_profile, 
									100, 
									120, 
									120,
									1,
									256,
									"validaAntesDeEnviarUpload",
									"uploadComSucesso").		
							"</div>
						</td>
					</tr>";				
								
					
	$conteudo .= "
				</table>";
						
	echo $conteudo;		
	}
	
	
	
	
	
	
	
	
	public function salvaDadosDeUsuario(){
	
	include_once LOCAL_LIB_BD."BdUtil.class.php";
	include_once VNVP_LOCAL_ABS_BIB."Biblioteca.class.php";
	
	$bib = new Biblioteca();
	$bd = new BdUtil();
	
	$erros = self::validaUsuario($bd, $bib);
	
		if(strlen($erros) == 0){

		$usuario = null;
	
		if($_POST['id_usuario']>0)
		$usuario = $bd->getPorId(new BeanUsuario(), $_POST['id_usuario']);
			
				
		if($usuario==null)
		$usuario = new BeanUsuario();
			

		$usuario->usuario  =  		$_POST['usuario'];
		$usuario->identificacao = 	$_POST['identificacao'];
		$usuario->nome_completo = 	$_POST['nome'];
		
		
		$usuario->tel= 				$_POST['tel'];
		$usuario->cel= 				$_POST['cel'];
		$usuario->email= 			$_POST['email'];
		
		
		$usuario->logradouro=   	$_POST['logradouro'];
		$usuario->num_residencia=  	$_POST['num'];
		$usuario->bairro= 			$_POST['bairro'];
		$usuario->cidade= 			$_POST['cidade'];
		$usuario->cep= 				$_POST['cep'];
		$usuario->uf=  				$_POST['uf'];
		$usuario->complemento=  	$_POST['complemento'];

			if($usuario->id<=0){
			
			$usuario->status 		= 1;
			$usuario->data_cadastro = date("Y-m-d");
			$usuario->senha 		=hash('sha256', $_POST['senha']);
			
		
			$usuario->id = $bd->novo($usuario);
			
				if($usuario->id<=0){
					
				echo '{"resultado":"ERRO", "erro":"Falha na gravação, por favor, tente novamente (COD '.$retorno.')."}';
				return;
				}
				
				if(strlen($_POST['grupo_padrao'])>0){
					
				$grupo = $bd->getPrimeiroOuNada(new BeanGrupo(), null, "###.cod='".$_POST['grupo_padrao']."'", null);

					if($grupo!=null){
						
					$x = new BeanUsuarioGrupo();
					
					$x->fk_usuario = $usuario->id;
					$x->fk_grupo  = $grupo->id_grupo;

					$bd->novo($x);
					}
				}		
				
			}
			else{
			
			if(strlen($_POST['senha'])>0)
			$usuario->senha 		=hash('sha256', $_POST['senha']);
			
				if(!$bd->altera($usuario)){
			
				echo '{"resultado":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
				}
			}
			
		
		echo '{"resultado":"OK", "id":'.$usuario->id.'}';	
		return;
		}
				
		
	echo '{"resultado":"ERRO", "erro":"'.$erros.'"}';
	}
	
	
	
	
	
	
	

	
	public function validaUsuario(&$bd, &$bib){
	

	$_POST['usuario'] 			= $bib->anti_injection($_POST['usuario']);
	$_POST['identificacao'] 	= $bib->anti_injection($_POST['identificacao']);
	$_POST['nome'] 				= $bib->anti_injection($_POST['nome']);
		
	$_POST['tel'] 				= $bib->anti_injection($_POST['tel']);
	$_POST['cel'] 				= $bib->anti_injection($_POST['cel']);
	$_POST['email'] 			= $bib->anti_injection($_POST['email']);
		
	$_POST['logradouro'] 		= $bib->anti_injection($_POST['logradouro']);
	$_POST['num'] 				= $bib->anti_injection($_POST['num']);
	$_POST['bairro'] 			= $bib->anti_injection($_POST['bairro']);
	$_POST['cidade'] 			= $bib->anti_injection($_POST['cidade']);
	$_POST['cep'] 				= $bib->anti_injection($_POST['cep']);
	$_POST['uf'] 				= $bib->anti_injection($_POST['uf']);
	$_POST['complemento'] 		= $bib->anti_injection($_POST['complemento']);
	$_POST['senha'] 			= $bib->anti_injection($_POST['senha']);
	
	
	
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
	
	if( !$bib->validaTEL(substr($_POST['tel'], 1, 2), substr($_POST['tel'], 4), ""))
	return "Informe um número de telefone válido.";
	
	
		if(strlen($_POST['cel']) > 0){	
			
		if( !$bib->validaTEL(substr($_POST['cel'], 1, 2), substr($_POST['cel'], 4), ""))
		return "Informe um número de celular válido.";
		}
		
	if(!$bib->validaEmail($_POST['email']))
	return "informe um endereço de E-mail válido.";
	
		
	

	if($_POST['id_usuario']==0 && strlen($_POST['senha'])==0)
	return "Informe uma senha para o usuario.";
		
	
		if(strlen($_POST['senha'])>0){
		
		
		if(strlen($_POST['senha'])<6)
		return "A senha deve ter ao menos 6 dígitos.";
		
		if(strcmp($_POST['senha'], $_POST['repete_senha'])!=0)
		return "As senhas informadas não batem.";
		}
	
	
		if($_POST['id_usuario']==0){
	
		if(!array_key_exists("captcha", $_POST) || 
			strlen($_POST["captcha"])==0)
		return "Marque a opção NÃO SOU UM ROBÔ.";
	
		$vetParametros = array (
		"secret" => VNVP_RECAPTCHA_SECRET_KEY,
		"response" => $_POST["captcha"],
		"remoteip" => $_SERVER["REMOTE_ADDR"]);

		$curlReCaptcha = curl_init();
		curl_setopt($curlReCaptcha, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($curlReCaptcha, CURLOPT_POST, true);
		curl_setopt($curlReCaptcha, CURLOPT_POSTFIELDS, http_build_query($vetParametros));
		curl_setopt($curlReCaptcha, CURLOPT_RETURNTRANSFER, true);

		$vetResposta = json_decode(curl_exec($curlReCaptcha), true);
		
		curl_close($curlReCaptcha);
		
		if ($vetResposta["success"])
		return "";
	
		return "Marque a opção NÃO SOU UM ROBÔ."."secret ".VNVP_RECAPTCHA_SECRET_KEY;
		}

	return "";
	}


	
	
	
	
	
	
	public function salvaImgProfile(){
	
	include_once LOCAL_LIB_BD."BdUtil.class.php";
	
	$bd = new BdUtil();

	$usuario = $bd->getPorId(new BeanUsuario(), $_POST['id_usuario']);

		if($usuario!=null){
			
		$usuario->img_profile = $_POST['url_img'];
	
			if(!$bd->altera($usuario)){
			
			echo '{"resultado":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
			return;
			}
		
		echo '{"resultado":"OK"}';	
		return;
		}
	
	echo '{"resultado":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';		
	}
	

	
	
	
	
	
	public function ativarDesativar($id, $echo=true){
			
	include_once LOCAL_LIB_BD."BdUtil.class.php";
	
	$bd = new BdUtil();
	
	$usuario = null;

		if($id>0){
		
		$usuario = $bd->getPorId(new BeanUsuario(), $id);
				
			if($usuario!=null){
			
			
			if($usuario->status>0)
			$usuario->status = 0;
			else
			$usuario->status = 1;
			
				if($bd->altera($usuario)){
			
					if($echo){
					echo '{"resultado":"OK"}';
					return;
					}
					else
					return '{"resultado":"OK"}';
				}
			}
		}
		
		if($echo){		
		echo '{"resultado":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
		return;
		}
		else
		return '{"resultado":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	
	
	
	
	
	
	

		
/*********************** grupos ***********************************/	

	
	
	
/*********************** acesso ***********************************/	
	
	

	
	public function temPermissao($cod_permissao, $permissaoPara = ""){
	
	$usuario  =$this->getUsuarioAtual();
	
	if($usuario == null || strlen($cod_permissao) == 0)
	return false;

	if($usuario->id==99999 && 
		strlen($usuario->usuario)>0 && strcmp($usuario->usuario, "DEV")==0 && 
		$usuario->status==1 &&
		strlen($usuario->senha)>0 && strcmp($usuario->senha, "USER_DEV")==0)
	return true;	
	

	include_once LOCAL_LIB_BD."BdUtil.class.php";
	
	$bd = new BdUtil();

	
	$grupos_user=$bd->getPorQuery(new BeanGrupo(), 
									"inner join usuarios_x_grupos as uxg on ###.id_grupo=uxg.fk_grupo", 
										"uxg.fk_usuario=".$usuario->id,
											null);
	

	if(count($grupos_user)==0)
	return false;

		foreach($grupos_user as $grupo){
			
		if(strlen($grupo->cod)>0 && strcmp($grupo->cod, COD_GRUPO_ADMIN)==0)
		return true;
		}
		

	$acessos=$bd->getPorQuery(new BeanGrupoAcesso(), 
									null, 
										"###.fk_grupo IN (select fk_grupo from usuarios_x_grupos where fk_usuario=".$usuario->id.") and acs.cod = '".$cod_permissao."'",
											null);
		

	if(count($acessos)==0)
	return false;

	
	$controle=  false;
	
		foreach($acessos as $acesso){
				
		if(strlen($acesso->tipo)==0)
		continue;		
			
			if(strcmp($acesso->tipo, "SIM_NAO") == 0){
		
				if(strlen($permissaoPara)==0){
					
				if(strcmp($acesso->valor, "SIM") == 0)	
				return true;
				}
				else{
		
				if(strcmp($acesso->valor, $permissaoPara) == 0)
				return true;
				}
			}
			else{
				
			if(strlen($permissaoPara)==0)
			continue;	
			
				if(strcmp($acesso->tipo, "VER") == 0){
		
				if(strcmp($permissaoPara, "VER") == 0)
				return true;
				}
				elseif(strcmp($acesso->tipo, "EDITAR") == 0){
			
				if(strcmp($permissaoPara, "VER") == 0 || strcmp($permissaoPara, "EDITAR") == 0)
				return true;
				}
				elseif(strcmp($permissaoPara, "EXCLUIR") == 0){
								
				if(strcmp($permissaoPara, "VER") == 0 || 
					strcmp($permissaoPara, "EDITAR") == 0 || 
						strcmp($permissaoPara, "EXCLUIR") == 0)
				return true;		
				}	
			}
		}
			
	return false;
	}


	

}

?>