 <?php

/*versao 1.0*/



class Arquivo{



/************************ validacao ********************************/


	

	
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
	
	
	
	

	
}

?>


