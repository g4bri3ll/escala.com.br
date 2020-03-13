<?php
session_start();

include_once 'Model/Modelo/Usuario.php';
include_once 'Model/DAO/UsuarioDAO.php';

//Verificar se tem alguem na session
if (!empty($_SESSION['id'])){
$id =  $_SESSION ['id'];

?>
<!doctype html>
<html lang="pt-BR">
<head>
<link rel="stylesheet" href="CSS/style.css" />
<link rel="stylesheet" href="boot/css/bootstrap.css" />
<link rel="stylesheet" href="boot/css/bootstrap.css.map" />
<link rel="stylesheet" href="boot/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Alterar a senha do usuário</title>
</head>
<body>

<?php //Permissão para acessar a pagina
foreach ($_SESSION['nome_paginas'] as $key) {
	switch ($key) {
		case 'altera_senha' : $as = 15;   break ;
	}
}
if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>
<div align="center">
	<div style="width: 80%">

<?php
if (! empty ( $_POST )) {
	
	if (empty ($_POST ['senhaAtual']) || empty ($_POST ['senha']) ||	empty ($_POST ['comfirmar_senha'])) {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Campos vazio não permitido </font> </div> <?php
		} else {
			
		$senhaAtual = $_POST ['senhaAtual'];
		$senha = $_POST ['senha'];
		$senha_comfirma = $_POST ['comfirmar_senha'];
		
		if ($senha !== $senha_comfirma){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Senhas não comferem, tente novamente! </font> </div> <?php
		} else {
		
			$usuDAO = new UsuarioDAO();
			$result = $usuDAO->VerificaSenha($senhaAtual);
			
			if (!$result){
				?> <div class="alert alert-error"> <font size="3px" color="red"> Senha atual errada, tente novamente! </font> </div> <?php
			} else {
					
				$usuDAO = new UsuarioDAO();
				$res = $usuDAO->AlterarSenha($senha, $senha_comfirma, $id);
					
				if ($res){
					?> <script type="text/javascript">  alert('Senha alterada com sucesso!'); window.location.assign('index.php'); </script> <?php
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red">  Ocorreu erro ao cadastra o usuario, erro: <?php print_r($res); ?>  </font> </div> <?php
				}//Fecha o else que esta verificando se foi cadastrado na base de dados ou não
				
			}//Fechar o else que esta verificando se existe um outro usuario com os dados no banco de dados
			
		}//Fechar o if que confere se as senha estão batendo
		
	}//Fecha o else que esta verificando se existe campos vazios.
			
}//Fecha o if que esta verificando se houve o post
?>

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Alteracao de senha</b></h1>
</div>

		<form action="" method="post">

			<div style="width: 80%;">
				<p class="w3-wide"> Informe sua senha atual </p>
				<input type="password" name="senhaAtual" placeholder="Informe sua senha atual" class="w3-input w3-border w3-light-grey" /><br>
				<p class="w3-wide"> Informe a nova senha </p> 
				<input type="password" name="senha" placeholder="Informe a nova senha" class="w3-input w3-border w3-light-grey" /><br>
				<p class="w3-wide"> Comfirme sua nova senha </p> 
				<input type="password" name="comfirmar_senha" placeholder="Comfirme sua nova senha" class="w3-input w3-border w3-light-grey" /><br>
			</div>
				<br><input type="submit" value="Alterar a senha" class="w3-btn w3-blue">
				
		</form>

	</div>
</div>

<?php } else {
	?>  <script type="text/javascript"> alert('Usuario nao tem privilegio de acessar esta pagina!'); window.location="index.php";  </script> <?php
}?>

</body>
</html>

<?php 
}//Verifica se tem alguem na session
else {
	header("Location: index.php");
} 
?>