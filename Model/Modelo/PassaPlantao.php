<?php

class PassaPlantao{
	
	private $id;
	private $numeroChamado;
	private $estadoChamado;
	private $tecnicoCiente;
	private $foiFeito;
	private $deveFazer;
	private $comentarios;
	private $idUsuario;
	private $status;
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