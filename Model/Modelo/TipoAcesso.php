<?php
/* Se o usuario e diretoria, cordenador, supervisor, diretor, etc. Tem que ter essa cadastro primeiro para
 * colocar para fazer o cadatro do usuario
 * 
 * */
class TipoAcesso{
	
	private $id;
	private $nome;
	private $status;
	
	//Atribuir o set a todos os atributos
	public function __set($atrib, $value){
		$this->$atrib = $value;
	}
	
	//Atribuir o get a todos os atributos
	public function __get($atrib){
		return $this->$atrib;
	}
	
}

