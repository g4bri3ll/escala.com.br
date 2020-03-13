<?php
session_start();

include_once 'Model/DAO/UsuarioDAO.php';

if (!empty($_POST)){
	
	//se o empty n�o funcionar pode colocar isso no if
	if (empty($_POST['cpf']) || empty($_POST['senha']) || empty($_POST['idUnidade'])){

		?> <script type="text/javascript"> alert('Preencha todos os campos!!'); window.location="index.php"; </script> <?php
		
	} else {
		
		$cpf = $_POST['cpf'];
		$senha = $_POST['senha'];
		$idUnidade = $_POST['idUnidade'];
			
		$cpf = str_replace("." , "" , $cpf); // Primeiro tira os pontos
		$cpf = str_replace("-" , "" , $cpf); // Depois tira o traco
		
		$usuDAO = new UsuarioDAO();
		$aut = $usuDAO->autenticar($cpf, $senha, $idUnidade);
		
		if (!$aut){
			?> <script type="text/javascript"> alert('Login nao encontrado!'); window.location="index.php"; </script> <?php
		} else{
			header("Location: index.php");
		}
		
	}
	
} 
?>