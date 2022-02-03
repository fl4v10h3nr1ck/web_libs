 <?php

chdir(dirname(__FILE__)); 

chdir('../');

include_once getcwd().'/base/VNVPBase.class.php';


final class Login extends VNVPBase{





	function __construct() {
		
		parent::__construct();
		
		$this->configuraCookiesDeUsuario();
	}

	

	
	public function dependencias(){
	
		parent::dependencias();
	
		echo "
		
		<script src='".VNVP_PATH_SMP."login/login.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".VNVP_PATH_SMP."login/login.css' type='text/css' media='all'>";
	}

	
	
	
	public function logar(){
	
		$form = 
		$this->path()."
		<form method='POST' action='javascript:login();'>
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
					<div align='center' id='area_bt_logar'>
					<input type='submit' style='width:110px' value='Entrar'/>
					</div>
					<div align='center' id='area_carregando'>
					<img src='".VNVP_PATH_SMP."imgs/load.gif'>
					</div>
					<div align='center' id='login_msg_erro'>
					</div>
				</div>
			</div>	
		</form>";
		
		echo $form;
	}



	
	
	public function permitirAcesso(){
		
		if(is_object($this->usuarioAtual()))
			return true;
		
		
		if(array_key_exists("usuario", $_COOKIE) && strlen($_COOKIE["usuario"])>0 &&
			array_key_exists("senha", $_COOKIE) && strlen($_COOKIE["senha"])>0){
			
			include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		
			$comuns = new Comuns();
			
			$usuario = $comuns->anti_injection($_COOKIE["usuario"]);
			$senha = $comuns->anti_injection($_COOKIE["senha"]);

			return $this->loginValido($usuario, $senha);	
		}
		
		return false;	
	}
	
	
	
	
	public function sair(){
	
		unset($_SESSION["usuario"]);	
		$_SESSION["salvar_login"]  =  0;
		$_SESSION["remove_cookies"]  = 	 1;

	}
	
		
	
	
	
	private function loginValido($usuario, $senha){
		
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
	
		include_once VNVP_PATH_BD_ABS.'BdUtil.class.php';
	
		$bd = new BdUtil();
	
		$retorno = $bd->getPrimeiroOuNada(
						new BeanUsuario(), 
						null, 
						"(###.usuario = '".$usuario."' OR ###.email='".$usuario."') AND ###.senha = '".$senha."' AND ###.status>0", null);		
	
		if(is_object($retorno)){
		
			include_once VNVP_PATH_ABS.'usuarios/BeanUsuarioCargo.class.php';
		
			$cargo = $bd->getPrimeiroOuNada(new BeanUsuarioCargo(), 
									null, "###.padrao>0 and ###.fk_usuario =".$retorno->id, null);		
	
			if(!is_object($cargo))
				return false;	
		
			$retorno->id_filial_atual = $cargo->id_filial;
		
			$_SESSION["usuario"]  = serialize($retorno);
			return true;
		}
	
		$_SESSION["usuario"]  = null;
		return false;	
	}
	
	
	

	
	public function tentativaDeLogin(){
	
		include_once VNVP_PATH_BIB_ABS.'Comuns.class.php';
		
		$comuns = new Comuns();

		$_POST["usuario"] = $comuns->anti_injection( $_POST["usuario"]);
		$_POST["senha"] = $comuns->anti_injection($_POST["senha"]);

		if( strlen($_POST["usuario"])== 0 || 
				strlen($_POST["senha"]) < 6 ){
		
			echo '{"status":"erro"}';
			$_SESSION["usuario"]  = null;
			return;
		}
	
		if(strcmp($_POST["usuario"], NOME_USER_DEV)==0 && 
			strcmp($_POST["senha"], SENHA_USER_DEV)==0){
		

			$_SESSION["usuario"]  = new BeanUsuario();
			
			$_SESSION["usuario"]->id =99999;
			$_SESSION["usuario"]->usuario =NOME_USER_DEV;
			$_SESSION["usuario"]->email ="";
			$_SESSION["usuario"]->senha =SENHA_USER_DEV;
			$_SESSION["usuario"]->status =1;
			$_SESSION["usuario"]->dev = 1;
			
			echo '{"status":"sucesso"}';
			return;
		}
	
		if( $this->loginValido($_POST["usuario"], hash('sha256', $_POST["senha"]))){
		
			if($_POST["salvar"]>0)
				$_SESSION["salvar_login"] = 1;
			else
				$_SESSION["salvar_login"] = 0;
		
			echo '{"status":"sucesso"}';
			return;
		}
	
	
		$_SESSION["usuario"]  = null;	
		$_SESSION["salvar_login"]  = 0;
		echo '{"status":"erro"}';			
	}
	
	
	
		

	
	public function configuraCookiesDeUsuario(){
		
		if( array_key_exists("remove_cookies", $_SESSION) && 
			$_SESSION["remove_cookies"]>0){

			setcookie("usuario", "", time() - 3600);
			setcookie("senha",   "", time() - 3600);
			$_SESSION["remove_cookies"] = 0;
		}
		else{
	
			$usuario =$this->usuarioAtual();
	
			if( array_key_exists("salvar_login", $_SESSION) && 
					$_SESSION["salvar_login"]> 0 &&
						is_object($usuario)){
		
				if(!array_key_exists("usuario", $_COOKIE) || 
						!array_key_exists("senha", $_COOKIE)){
				
					// 1 mes
					$tempo = time()+60*60*24*30;	
				
					setcookie("usuario", $usuario->usuario, $tempo);
					setcookie("senha", $usuario->senha, $tempo);
					$_SESSION["salvar_login"] = 0;
				}
			}
		}
	}
	
	
	

}

?>