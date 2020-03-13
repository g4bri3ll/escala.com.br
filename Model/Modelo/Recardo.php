<?php

class Recardo{
	
	private $id;
	private $status;
	private $idUsuRecebe;
	private $idUsuEnvia;
	private $idEstadoChamado;
	private $comentario;
	private $data;
	
	//Atribuir o set a todos os atributos
	public function __set($atrib, $value){
		$this->$atrib = $value;
	}
	
	//Atribuir o get a todos os atributos
	public function __get($atrib){
		return $this->$atrib;
	}
	
}