 <?php

/*versao 2.0 (antiga Biblioteca)*/


/*

anti_injection($sql)->bool
validaEmail($mail)->bool
validaCNPJ($cnpj)->bool
validaCPF($cpf)->bool
validaData( $data)->bool
validaCEP( $cep)->bool
validaTEL($tel)->bool
validaHorario($hora, $min)->bool
formataCPF($cnpj)->string
formataCNPJ($cnpj)->string
formataData($data)->string
formataCEP($cep)->string
formataTEL($ddd, $num, $ramal)->string
formataNome($nome)->string
getComponentesDeTEL( $tel)->array(ddd, num, ramal)
formataEndereco($logradouro, $num, $bairro, $cep, $cidade, $uf, $complemento)->string
preparaHTMLParaJson($valor)->string
enviaEmail($de, $para, $assunto, $msg, $path_anexo, $cc, $bcc, $confirmar_leitura=false)->bool

*/


class Comuns{



/************************ validacao ********************************/


	public function anti_injection($sql) {
		   
		$sql = preg_replace( "/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/", "", $sql);
		$sql = trim($sql);
		$sql = strip_tags($sql);
		$sql = addslashes($sql);
	   
		return $sql;
	 }




	public function validaEmail($mail){

		if(strlen($mail) == 0)
			return false;


		if(preg_match("/^([[:alnum:]_.-]){2,}@([[:lower:][:digit:]_.-]{2,})(\.[[:lower:]]{2,3})(\.[[:lower:]]{2})?$/", $mail))
			return true;
		else
			return false;
	}




	public function validaCNPJ($cnpj){

		if(strlen($cnpj) > 19) 
			return false;
	
		$soment_num = preg_replace("/[^0-9\s]/", "", $cnpj);
	
		if(strlen($soment_num) < 14 || strlen($soment_num) > 15) 
			return false;
	
		$char_validos = array('0','1','2','3','4','5','6','7','8','9','.','/','-');
	
		$comp = strlen($cnpj);
			for($i = 0; $i < $comp; $i++){
				$control = false;	
				foreach( $char_validos as $value){
		
					if(strcmp($cnpj[$i], $value) == 0){
					$control = true;	
					break;
					}
				}
		
				if(!$control)
					return false;
			}
	
		return true;
	}
		
		
		

	public function validaCPF($cpf){

		if(strlen($cpf) > 14)
			return false;
	
		$soment_num = preg_replace("/[^0-9\s]/", "", $cpf);
	
		if(strlen($soment_num) != 11) 
			return false;
	
	
		$char_validos = array('0','1','2','3','4','5','6','7','8','9','.','-');
	
		$comp = strlen($cpf);
			for($i = 0; $i < $comp; $i++){
				$control = false;	
				foreach( $char_validos as $value){
		
					if(strcmp($cpf[$i], $value) == 0){
						$control = true;	
						break;
					}
				}
		
			if(!$control)
				return false;
			}
	
		return true;
	}
		

		
		
	
	public function validaData( $data){
	
		if(strlen($data) > 10)
			return false;
	
		$soment_num = preg_replace("/[^0-9\s]/", "", $data);
	
		if(strlen($soment_num) != 8) 
			return false;
	
	
		$char_validos = array('0','1','2','3','4','5','6','7','8','9','/');
	
		$comp = strlen($data);
			for($i = 0; $i < $comp; $i++){
				$control = false;	
				foreach( $char_validos as $value){
		
					if(strcmp($data[$i], $value) == 0){
						$control = true;	
						break;
					}
				}
		
			if(!$control)
				return false;
			}
	
	
		if( substr($soment_num, 0, 2) > 31 || substr($soment_num, 2, 2) > 12)
			return false;
	
		return true;
	}
	
	
	
	
	
	public function validaCEP( $cep){
	
		if(strlen($cep) > 9)
			return false;
	
		$soment_num = preg_replace("/[^0-9\s]/", "", $cep);
	
		if(strlen($soment_num) != 8) 
			return false;
	
	
		$char_validos = array('0','1','2','3','4','5','6','7','8','9','-');
	
		$comp = strlen($cep);
			for($i = 0; $i < $comp; $i++){
				$control = false;	
				foreach( $char_validos as $value){
		
					if(strcmp($cep[$i], $value) == 0){
						$control = true;	
						break;
					}
				}
		
				if(!$control)
					return false;
			}
	
		return true;
	}
	
	
	
	
	/* um tel valido tem a forma 
	(xx) xxxx-xxxx ou (xx) xxxxx-xxxx e ramal xxxx, 
	a funcao apenas valida, nao formata, logo,
	parênteses e traços bem como suas posições serão ignorados*/
	
	public function validaTEL($tel){
	
		$partes = $this->getComponentesDeTEL($tel);
	
		if(strlen($partes['ddd'])!=2)
			return false;
		
		if(strlen($partes['num'])!=8 && strlen($partes['num'])!=9)
			return false;
	
		return true;
	}
	

	
		
	public function validaHorario($hora, $min){
		
		if(strlen($hora)==0 || strlen($min)==0)
			return false;
		
		$aux = intval($hora);
		
		if($aux>23 || $aux<0)
			return false;
		
		$aux = intval($min);
		
		if($aux>59 || $aux<0)
			return false;
		
		return true;
	}


		
		
/************************ formatacao ********************************/		




	public function formataCPF($cnpj){
	
		$cnpj = preg_replace("/[^0-9\s]/", "", $cnpj);
	
		return substr($cnpj, 0, 3).".".
				substr($cnpj, 3, 3).".".
					substr($cnpj, 6, 3)."-".
						substr($cnpj, 9, 2);
	}



	
	
	public function formataCNPJ($cnpj){
	
		$cnpj = preg_replace("/[^0-9\s]/", "", $cnpj);
	
		$dig = 0;
		if(strlen($cnpj) == 15)
			$dig = 1;
	
		return substr($cnpj, 0, 2+$dig).".".
				substr($cnpj, 2+$dig, 3).".".
					substr($cnpj, 5+$dig, 3)."/".
						substr($cnpj, 8+$dig, 4).'-'.
							substr($cnpj, 12+$dig, 2);
	}


	
		
		
	public function formataData($data){
	

		$data = preg_replace("/[^0-9\s]/", "", $data);
	
		return substr($data, 0, 2)."/".
				substr($data, 2, 2)."/".
					substr($data, 4, 4);
	}
	
		
	
	
		
	public function formataCEP($cep){
	
		$cep = preg_replace("/[^0-9\s]/", "", $cep);
	
		return substr($cep, 0, 5)."-".
				substr($cep, 5, 8);
	}

	
	
	
	
	public function formataTEL($ddd, $num, $ramal){
	
		if( strlen($ddd)==0)
			return '';
	
		return "(".$ddd.") ".$num.(strlen($ramal)==0? "" : " ".$ramal);
	}

	

	
	/* recebe um telefone em forma de string e 
	retorna um array contendo as chaves 'ddd' 'num' 'ramal' com os respectivos dados. */
	public function getComponentesDeTEL( $tel){
	
		$aux = array('ddd'=>'', 'num'=>'', 'ramal'=>'');
	
		if( strlen($tel) > 0){
		
			$tel = str_replace(' ', '', $tel);
				
			$partes = explode("-", $tel);
				
			if(count($partes)!=2)
				return $aux;
				
			$partes[0] = preg_replace("/[^0-9\s]/", "", $partes[0]);
			$partes[1] = preg_replace("/[^0-9\s]/", "", $partes[1]);
				
			if(strlen($partes[0])<6 || strlen($partes[1])<4)
				return $aux;
			
			$aux['ddd'] = substr($partes[0], 0, 2);
			$aux['num'] = substr($partes[0], 2).substr($partes[1], 0, 4);
			$aux['ramal'] = (strlen($partes[1])>4?substr($partes[1], 4):"");
		}
			
		return $aux;
	}
	
	
	
	

	public function formataEndereco($logradouro, $num, $bairro, $cep, $cidade, $uf, $complemento){
	
		return    	(strlen($logradouro)==0?"":$logradouro).
					(strlen($num)==0?(strlen($logradouro)>0?" S/N":""):" Nº ".$num).
					(strlen($bairro)==0?"":" ".$bairro).
					(strlen($cep)==0?"":" CEP ".$cep).
					(strlen($cidade)==0?"":" ".$cidade).
					(strlen($uf)==0?"":" ".$uf).
					(strlen($complemento)==0?"":" Compl. ".$complemento);
	}
	
	
	
	
	
	
		
	public function formataDataBRparaEUA($data){
	
		$data = preg_replace("/[^0-9\s]/", "", $data);
	
		return substr($data, 4, 4)."-".substr($data, 2, 2)."-".substr($data, 0, 2);
	}
	
	
	

	public function formataNome($nome){
		
		if(strlen($nome)<=0)
			return "";
		
		$partes = explode(' ', $nome);
		$nome = array_shift($partes);
		$sobrenome = array_pop($partes);
		
		return (strlen($nome)>0?ucwords ($nome):"")." ".(strlen($sobrenome)>0?ucwords ($sobrenome):"");
	}
	
	
	
/************************ diversos ********************************/				
		

	
	
	
	public function preparaHTMLParaJson($valor){
		
		return 	str_replace('"', '\"', 
					str_replace("\t", "\\t", 
						str_replace("\n", "\\n", 
							str_replace("\r", "\\r", $valor))));
	}
	
	
	
	
	// email do remetente deve ser do mesmo dominio da servidor que esta enviando
	//formato de anexos suportados:
	//pdf, bmp, gif, html, ico, jpeg, jpg, txt, exe, ppt, doc, rar, zip, png
	
	public function enviaEmail($de, $para, $assunto, $msg, $path_anexo, $cc, $bcc, $confirmar_leitura=false){
	
		$separador = "\n";
	
		if(strcmp(PHP_OS, "WINNT")==0 || strcmp(PHP_OS, "Windows")==0)
			$separador = "\r\n";
	
		$sem_anexo = false;
	
		if(!file_exists($path_anexo))
			$sem_anexo  = true;		
	
		$cabecalho = "";
	
		$mensagem = "";
	
	
		if($sem_anexo){
	
			$cabecalho .= "MIME-Version: 1.0" . $separador;
			$cabecalho .= "Content-type: text/html; charset=\"utf-8\"" .$separador; 
			$cabecalho .= "From: ".$de.$separador;
			$cabecalho .= "Return-Path: ".$de.$separador;
		
			if(strlen($cc)>0)
				$cabecalho .= "Cc: ".$cc.$separador;
		
			if(strlen($bcc)>0)
				$cabecalho .= "Bcc: ".$bcc.$separador;
		
			if($confirmar_leitura){
				$cabecalho .= "X-Confirm-Reading-To: ".$de.$separador;
				$cabecalho .= "Disposition-Notification-To: ".$de.$separador;
				$cabecalho .= "Return-Receipt-To: ".$de.$separador;
			}
		
		/*
			if($cc!=null){
				
				if(is_array($cc)){
				
					foreach($cc as $email){
						
					if(strlen($email)>0)
					$cabecalho .= "Cc: ".$email.$separador;	
					}
				}
				else{
				
				if(strlen($cc)>0)
				$cabecalho .= "Cc: ".$cc.$separador;
				}
			}
	*/
	
			$mensagem .= $msg;
		}
		else{
		
			$boundary = "XYZ-".md5(uniqid(time())). "-ZYX";

			$cabecalho .= "MIME-Version: 1.0" . $separador;
			$cabecalho .= "From: ".$de.$separador;
			$cabecalho .= "Return-Path: ".$de.$separador;
			$cabecalho .= "Content-Type: multipart/mixed; boundary=\"" . $boundary ."\"". $separador;
			$cabecalho .= "$boundary" . $separador;

			if(strlen($cc)>0)
				$cabecalho .= "Cc: ".$cc.$separador;
		
			if(strlen($bcc)>0)
				$cabecalho .= "Bcc: ".$bcc.$separador;
		
		
			if($confirmar_leitura){
				$cabecalho .= "X-Confirm-Reading-To: ".$de.$separador;
				$cabecalho .= "Disposition-Notification-To: ".$de.$separador;
				$cabecalho .= "Return-Receipt-To: ".$de.$separador;
			}
		/*
			if($cc!=null){
				
				if(is_array($cc)){
				
					foreach($cc as $email){
						
					if(strlen($email)>0)
					$cabecalho .= "Cc: ".$email.$separador;	
					}
				}
				else{
				
				if(strlen($cc)>0)
				$cabecalho .= "Cc: ".$cc.$separador;
				}
			}
	*/
	
			$fp = fopen( $path_anexo, "rb" );
	
			if($fp===false)
				return false;
	
	
			$anexo = fread( $fp, filesize( $path_anexo ) );
			$anexo = chunk_split(base64_encode( $anexo ));
		
			fclose( $fp ); 

	
			$extencao = pathinfo(basename($path_anexo), PATHINFO_EXTENSION);
	
			$tipo = "";
			if(strcmp($extencao, "pdf")==0)
				$tipo = "application/pdf";
			elseif(strcmp($extencao, "bmp")==0)
				$tipo = "image/bmp";
			elseif(strcmp($extencao, "gif")==0)
				$tipo = "image/gif";
			elseif(strcmp($extencao, "html")==0)
				$tipo = "text/html";
			elseif(strcmp($extencao, "ico")==0)
				$tipo = "image/x-icon";
			elseif(strcmp($extencao, "jpg")==0)
				$tipo = "image/jpeg";
			elseif(strcmp($extencao, "jpeg")==0)
				$tipo = "image/jpeg";
			elseif(strcmp($extencao, "png")==0)
				$tipo = "image/png";
			elseif(strcmp($extencao, "exe")==0)
				$tipo = "application/x-msdownload";
			elseif(strcmp($extencao, "ppt")==0)
				$tipo = "application/vnd.ms-powerpoint";
			elseif(strcmp($extencao, "doc")==0)
				$tipo = "application/msword";
			elseif(strcmp($extencao, "txt")==0)
				$tipo = "text/plain";
			elseif(strcmp($extencao, "rar")==0)
				$tipo = "application/x-rar-compressed";
			elseif(strcmp($extencao, "zip")==0)
				$tipo = "application/zip";
	
	
			$mensagem  = "--".$boundary . $separador;
			$mensagem .= "Content-Transfer-Encoding: 8bits" . $separador; 
			$mensagem .= "Content-Type: text/html; charset=\"utf-8\"" . $separador. $separador;
			$mensagem .= $msg. $separador;
			$mensagem .= "--".$boundary . $separador;


			$mensagem .= "Content-Type: ".$tipo . $separador;
			$mensagem .= "Content-Disposition: attachment; filename=\"". basename($path_anexo) . "\"" . $separador;
			$mensagem .= "Content-Transfer-Encoding: base64" . $separador .$separador; 
			$mensagem .= $anexo. $separador;
			$mensagem .= "--".$boundary."--". $separador;
		}

	
		return mail($para, $assunto, $mensagem, $cabecalho);	
	}
	
	
	
	
	
	
	
}

?>


