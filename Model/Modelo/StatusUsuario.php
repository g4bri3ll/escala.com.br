<?php

class StatusUsuario{
	
	private $id;
	private $idUsuario;
	private $status;
	private $dataInicio;
	private $dataFinal;
	private $idTipoFolga;
	
	//Atribuir o set a todos os atributos
	public function __set($atrib, $value){
		$this->$atrib = $value;
	}
	
	//Atribuir o get a todos os atributos
	public function __get($atrib){
		return $this->$atrib;
	}
	
}