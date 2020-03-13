<?php
session_start();

include_once 'Model/DAO/FuncaoChefeSetorDAO.php';
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
<title>Cadastra Funcao chefe setor</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'funcao_chefe_setor' : $fcs = 30;   break ;
		}
	}	
	if (!empty($fcs) || $_SESSION['nivel_acesso'] === "1"){
?>

<div align="center">
	<div style="width: 80%">

<?php
if (!empty($_POST)){
	
	if (empty($_POST['nome'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Campos vazio nao permitido </font> </div> <?php
	} else {
		
		$nome = $_POST['nome'];
		//Colocar os dados em minusculo
		$nome = strtolower($nome);
		
		$funDAO = new FuncaoChefeSetorDAO();
		$validar = $funDAO->VerificarNome($nome);
		
		if (empty($validar)){
		
			$status = 'ativado';
			
			$funDAO = new FuncaoChefeSetorDAO();
			$result = $funDAO->cadastrar($nome, $status);
			
			if($result){
				?> <script type="text/javascript"> alert('Funcao do usuario ao setor cadastrado com sucesso!'); window.location="index.php";  </script><?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra, causa	possivel <?php echo $result; ?> </font> </div> <?php
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Existe uma funcao usuario cadastrado com esse nome </font> </div> <?php
		}
		
	}

}//Fecha o if que verifica se o post foi executado
?>
		
		<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastra a funcao chefe setor</b></h1>
</div>

		<form action="" method="post" id="form" name="form">

			<div class="control-group">
				<label class="w3-text-brown w3-left">Informe a funcao do chefe do setor</label>
				<input type="text" name="nome" required class="w3-input w3-border w3-light-grey" placeholder="Informe a funcao exercida para o setor" maxlength="100" />
			</div>
			
			<br> <input class="w3-btn w3-blue" type="submit" value="  Cadastrar  " />

		</form><br>

		<a href="index.php" class="w3-btn w3-blue">Cancelar Cadastro</a>

	</div>
</div>

<?php
	} else {
		?>  <script type="text/javascript"> alert('Usuario não tem privilegio de acessar esta pagina!'); window.location="index.php";  </script> <?php
	}
} else {
	header("Location: index.php");
}
?>
	
</body>
</html>