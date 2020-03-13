<?php

class Setor{
	
	private $id;
	private $nome;
	private $status;
	private $idUnidadeTrabalho;
	
	//Atribuir o set a todos os atributos
	public function __set($atrib, $value){
		$this->$atrib = $value;
	}
	
	//Atribuir o get a todos os atributos
	public function __get($atrib){
		return $this->$atrib;
	}
	
}