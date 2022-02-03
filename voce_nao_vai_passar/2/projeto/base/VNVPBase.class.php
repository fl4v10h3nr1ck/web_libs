 <?php

chdir(dirname(__FILE__)); 

chdir('../');

include_once getcwd().'/define.php';



class VNVPBase{



	function __construct() {}

	
	
	
	public function dependencias(){
		
		echo "
		
		<script src='".VNVP_PATH_SMP."base/VNVPBase.js' type='text/javascript'></script>
		
		<link rel='stylesheet' href='".VNVP_PATH_SMP."base/VNVPBase.css' type='text/css' media='all'>";
	}

	
	
	
	public function conteudo(){}
	
	
	
	
	public function path(){
		
		return "<input type='hidden' id='VNVP_PATH_SMP' value='".VNVP_PATH_SMP."'>";	
	}
	
	

	
	
	public function usuarioAtual(){
		
		/*importante, pois, em algumas situacoes a classe BeanUsuario nunca foi incluida antes de chamar
		unserialize, logo, o php nao iria saber como criar o objeto de BeanUsuario, gerando um objeto 
		incompleto _PHP_Incomplete_Class. a funcao is_object sempre retorna false para um 
		_PHP_Incomplete_Class*/
		include_once VNVP_PATH_ABS.'usuarios/BeanUsuario.class.php';
		
		if(array_key_exists("usuario", $_SESSION) && $_SESSION['usuario']!=null)
			return unserialize($_SESSION['usuario']);
	
		return null;
	}
	
	
	

	

	public function temPermissao($cod_permissao, $permissaoPara = "SIM"){
	
		$usuario = $this->usuarioAtual();
	
		if(!is_object($usuario) || strlen($cod_permissao) == 0)
			return false;
	
		if($usuario->id==99999 && $usuario->status==1 &&
			strlen($usuario->usuario)>0 && strcmp($usuario->usuario, NOME_USER_DEV)==0 && 
				strlen($usuario->senha)>0 && strcmp($usuario->senha, SENHA_USER_DEV)==0)
			return true;
	
		return $this->temPermissaoPorUsuario($usuario->id, $usuario->id_filial_atual, $cod_permissao, $permissaoPara);
	}


	
	

	public function temPermissaoPorUsuario($id_usuario, $id_filial, $cod_permissao, $permissaoPara = "SIM"){
	
		
		if($id_usuario<=0 || strlen($cod_permissao) == 0 || $id_filial<=0)
			return false;	
	
	
		include_once VNVP_PATH_BD_ABS.'BdUtil.class.php';
		include_once VNVP_PATH_ABS.'grupos/BeanGrupo.class.php';
		include_once VNVP_PATH_ABS.'grupos/BeanGrupoAcesso.class.php';
	
		$bd = new BdUtil();

		if($this->usuarioEhAdmin($bd, $id_usuario, $id_filial))
			return true;
		
		// se nao é admin, permissaoPara tem que esta definida. 
		if(strlen($permissaoPara) == 0)
			return false;

		
		$grupos_user=$bd->getPorQuery(new BeanGrupo(), 
									"inner join usuarios_x_grupos as uxg on ###.id_grupo=uxg.fk_grupo and uxg.fk_usuario=".$id_usuario, 
										"###.status>0 and ###.fk_filial=".$id_filial,
											null);
		
		if(count($grupos_user)>0){
	
			foreach($grupos_user as $grupo){
				
				if($grupo->status>0){
			
					$acessos=$bd->getPorQuery(new BeanGrupoAcesso(), 
														null, 
															"acs.cod='".$cod_permissao."' and ###.fk_grupo=".$grupo->id_grupo,
																null);
			
					if(count($acessos)==0)
						continue;
					
					foreach($acessos as $acesso){
					
						if(strlen($acesso->valor)==0 || strlen($acesso->tipo)==0)
							continue;		
				
						if(strcmp($acesso->tipo, ACESSO_SIM_NAO) == 0){
			
							if(strcmp($acesso->valor, ACESSO_SIM) == 0)	
								return true;
						}
						else{
				
							if(strcmp($acesso->valor, ACESSO_VER) == 0){
			
								if(strcmp($permissaoPara, ACESSO_VER) == 0)
									return true;
							}
							elseif(strcmp($acesso->valor, ACESSO_EDITAR) == 0){
				
								if(strcmp($permissaoPara, ACESSO_VER) == 0 || 
									strcmp($permissaoPara, ACESSO_EDITAR) == 0)
									return true;
							}
							elseif(strcmp($acesso->valor, ACESSO_EXCLUIR) == 0){
								
								if(strcmp($permissaoPara, ACESSO_VER) == 0 || 
										strcmp($permissaoPara, ACESSO_EDITAR) == 0 || 
											strcmp($permissaoPara, ACESSO_EXCLUIR) == 0)
									return true;		
							}	
						}
					}
				}
			}
		}
		
		
		return false;
	}


	
	
	
	public function usuarioEhAdmin(&$bd, $id_usuario, $id_filial){
		
		include_once PSNL_VNVP_PATH_ABS.'grupos/BeanGrupo.class.php';
	
		$grupos_user=$bd->getPorQuery(new BeanGrupo(), 
									"inner join usuarios_x_grupos as uxg on ###.id_grupo=uxg.fk_grupo and uxg.fk_usuario=".$id_usuario, 
										"###.status>0 and ###.fk_filial=".$id_filial." and ###.admin>0",
											null);
		if(count($grupos_user)==0)
			return false;
		
		return true;
	}


	


}


?>