<?php

class TrocaServico{
	
	private $id;
	private $dataSolicitante;
	private $dataTira;
	private $idUsuarioTira;
	private $idUsuarioSolicitante;
	private $motivoTroca;
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