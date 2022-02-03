 <?php

//versao 1.0



class Biblioteca{



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
	xx xxxx-xxxx ou xx xxxxx-xxxx e ramal xxxx, 
	a funcao apenas valida, nao formata, logo,
	parênteses e traços bem como suas posições serão ignorados*/
	public function validaTEL( $ddd, $tel, $ramal, $opcional = false){
	

		if($opcional){
			
			if(strlen($tel) == 0 && strlen($ramal) == 0 && strlen($ddd) == 0)
			return true;	
		}
		
		
		if( !$this->somenteNUM( $ddd, false, 2, 2))
			return false;
		
		
		if(strlen ($ramal)> 0){
			
			if( !$this->somenteNUM( $ramal, false, 0, 4))
				return false;
		}
		
		
		if(strlen(preg_replace("/[^0-9\s]/", "", $tel)) < 8 || strlen(preg_replace("/[^0-9\s]/", "", $tel)) > 9)
			return false;

		$char_validos = array('0','1','2','3','4','5','6','7','8','9','-');
	
		$comp = strlen($tel);
			for($i = 0; $i < $comp; $i++){
				$control = false;	
				foreach( $char_validos as $value){
		
					if(strcmp($tel[$i], $value) == 0){
						$control = true;	
					break;
					}
				}
		
			if(!$control)
				return false;
			}
	
	
		return true;
	}
	

	
		
	public function  validaMoeda($valor, $pode_zero =false) {
	
		$aux = str_replace('.', '', $valor);
		$aux = str_replace(',', '', $aux);
		
		if(!$this->somenteNUM($aux, $pode_zero, 1, 15))
			return false;
	
		if(substr_count($valor, ',') > 1 )
			return false;
	
		//nao importa onde esteja o ponto, o campo será formatado depois
		//porem a virgula decimal, caso haja, deverá está no local correto
	
		if( strripos($valor, ',') ===false ||
				strripos($valor, ',') == (strlen($valor) - 3) || 
					strripos($valor, ',') == (strlen($valor) - 2))
			return true;	
		
	return false;
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
	public function getTEL( $tel){
	
		$aux = array('ddd'=>'', 'num'=>'', 'ramal'=>'');
	
			if( strlen($tel) > 0){
		
				$tel = str_replace(' ', '', $tel);
				$posicao_traco = strpos($tel, '-')-4;
				$tel = preg_replace("/[^0-9\s]/", "", $tel);
		
			$aux['ddd'] = substr( $tel, 0, 2);
			$aux['num'] = substr( $tel, 2, $posicao_traco).'-'.substr( $tel, $posicao_traco+2, 4);
			$aux['ramal'] = substr( $tel, $posicao_traco+ 6 );

			}
			
		return $aux;
	}
	
	
	
	
	
	
	public function  FormataMoeda($valor) {
	
		$valor_format = "";
		$aux = str_replace('.', '', $valor);

	
		$decimal = "";
		$posicao_virg = strripos($aux, ',');
	
		if($posicao_virg === false)
			$decimal = ",00";
		else{	
			$decimal = substr($aux, $posicao_virg, strlen($aux));
		
			if(strlen($decimal )== 2)
				$decimal .="0";
		
			$aux = substr($aux, 0, $posicao_virg);
		}
	
	
		$resto = strlen($aux)%3;
		$primeira_parte = substr($aux, 0, $resto);
		$segunda_parte = substr($aux, $resto, strlen($aux));
	
		for($i = 0; $i<strlen($segunda_parte); $i+=3)
			$valor_format.=substr($segunda_parte, $i, 3).($i == (strlen($segunda_parte)-3)?"":".");
	
		$final = $primeira_parte;
		if( strlen($segunda_parte)>0 && strlen($primeira_parte)>0)
			$final .="."; 
	
		return $final.$valor_format.$decimal;
	}
	
	
	
	
	
	
	
/************************ diversos ********************************/				
		

	
	public function somaValorMonetario(&$reg){
	
	$cont = "0.00";
		foreach($reg  as $valor){
		
		$aux = str_replace(".","",$valor);
		$aux = str_replace(",",".",$aux);
		
		$cont += $aux;
		
		$cd=explode(".",$cont);
		
		if (empty($cd)) 
		$cont .=".00";
		elseif (count($cd) < 2)
		$cont .=".0";
		}
	
	$cont= str_replace(".",",",$cont);

	return $this->FormataMoeda($cont);
	}
	
	
		
		
		
	public function somenteNUM( $campo, $pode_zero, $min =0, $max=0){
	
		if($min != 0){
	
		if( strlen($campo) < $min)
		return false;
		}
	
	
		if($max != 0){
	
		if( strlen($campo) > $max)
		return false;
		}
	
	
	$char_validos = array('0','1','2','3','4','5','6','7','8','9');
	
	$comp = strlen($campo);
		for($i = 0; $i < $comp; $i++){
		$control = false;	
			foreach( $char_validos as $value){
		
				if(strcmp($campo[$i], $value) == 0){
				$control = true;	
				break;
				}
			}
		
		if(!$control)
		return false;
		}
	
	
	
	if(!$pode_zero && $campo==0)
	return false;
	
	
	return true;
	}
	

	
	
	
	public function extraiNumTEL($tel){
	
	$resultado = array('ddd'=>'', 'num'=>'', 'ramal'=>'');
	
	$tel = preg_replace("/[^0-9\s]/", "", str_replace(" ", "", $tel));
	
	if(strlen($tel) < 10)
	return $resultado;

	$resultado['ddd'] = substr($tel, 0, 2);
	
		switch( strlen($tel)){
		
		case  14:
		$resultado['ramal'] = substr($tel, -4);
		case 10:
		$resultado['num']  = substr($tel, 2, 4).'-'.substr($tel, 6, 4);	
		break;
			
			case  15:
			$resultado['ramal'] = substr($tel, -4);
			case 11;
			$resultado['num']  = substr($tel, 2, 5).'-'.substr($tel, 7, 4);	

		}
	
	
	return $resultado;
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
	
	
	
	
	
	public function preparaHTMLParaJson($valor){
		
	return 	str_replace('"', '\"', str_replace("\t", "\\t", str_replace("\n", "\\n", str_replace("\r", "\\r", $valor))));
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
	
	
	
	
	
	public function getMes($mes){
		
		foreach(array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro", "Outubro", "Novembro", "Dezembro") as $i=>$valor){
			
		if(($mes-1)==$i)
		return $valor;
		}

	return $mes;
	}
	
	
	
	

	
	function limpaDiretorioTemp($path_absoluto) {

	if($path_absoluto==null || strlen($path_absoluto)==0)
	return;
	
	$dh  = opendir($path_absoluto);
		while (false !== ($filename = readdir($dh))) {
		
		if(is_file($path_absoluto."/".$filename))
		unlink($path_absoluto."/".$filename); 
		}
	}
	
	
	
	
	
	public function removeImg($nome){
	
	if(is_file($nome))
	unlink($nome); 
	}
	
	
	
	
	
	
	public function comparaDatas($data1, $data2){
	
	if(strlen($data1)!=10 || strlen($data2)!=10)
	return -2;	
	
	$aux = DateTime::createFromFormat('d/m/Y', $data1);
	$data1 = $aux->format('Y-m-d');
	
	$aux = DateTime::createFromFormat('d/m/Y', $data2);
	$data2 = $aux->format('Y-m-d');
	
	if(strtotime($data1) == strtotime($data2))
	return 0;

	if(strtotime($data1) < strtotime($data2))
	return 1;

	return -1;
	}
	

	

	
	public function dataFormatoBRParaUs($data){
		
	if(strlen($data)!=10)
	return "";
	
	if(strpos($data, "-")!==false)
	return $data;

	return substr($data, 6)."-".substr($data, 3, 2)."-".substr($data, 0, 2);
	}

	
	
	
	
	public function dataFormatoUsParaBr($data){
		
	if(strlen($data)!=10)
	return "";
	
	if(strpos($data, "/")!==false)
	return $data;

	return substr($data, 8)."/".substr($data, 5, 2)."/".substr($data, 0, 4);
	}

	
	
}

?>


