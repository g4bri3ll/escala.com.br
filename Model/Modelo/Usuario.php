<?php

class Usuario{
	
	private $id;
	private $nome;
	private $cpf;
	private $senha;
	private $comfirmar_senha;
	private $nivel_acesso;
	private $email;
	//Se trabalhar na central, acesso, plantão
	private $idSetor;
	private $apelido;
	private $idUnidadeTrabalho;
	private $idTipoAcesso;
	private $status;
	//Cadastrar na tabela de usuario_funcao
	private $cidade;
	private $bairro;
	private $complemento;
	private $idFuncaoExercida;
	private $opcaoVisualizarPainel;
	private $cep;
	private $codigoUsu;
	private $dataCadastro;
	
	//Atribuir o set a todos os atributos
	public function __set($atrib, $value){
		$this->$atrib = $value;
	}
	
	//Atribuir o get a todos os atributos
	public function __get($atrib){
		return $this->$atrib;
	}
	
}