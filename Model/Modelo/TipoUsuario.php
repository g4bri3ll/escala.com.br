<?php
/*Cadastro os tipos de usuario se e dar informatica, enfermagem, médicos, manunteção, rh. Tem que fazer esse cadastro
 * primeiro para dizer se ele e de qual setor
 * */
class TipoUsuario{
	
	private $id;
	private $tipo_usuario;
	
	//Atribuir o set a todos os atributos
	public function __set($atrib, $value){
		$this->$atrib = $value;
	}
	
	//Atribuir o get a todos os atributos
	public function __get($atrib){
		return $this->$atrib;
	}
	
}