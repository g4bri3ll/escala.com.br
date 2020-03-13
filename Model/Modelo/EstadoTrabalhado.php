<?php

class EstadoTrabalhado{
	
	private $id;
	private $estado;
	private $idUsuario;
	private $idSubSetor;
	private $id_data_semanais;
	
	//Atribuir o set a todos os atributos
	public function __set($atrib, $value){
		$this->$atrib = $value;
	}
	
	//Atribuir o get a todos os atributos
	public function __get($atrib){
		return $this->$atrib;
	}
	
}