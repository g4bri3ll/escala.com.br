<?php
session_start();

include_once 'Model/DAO/EstadoChamadoDAO.php';

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
<title>Cadastrado o estado do chamado</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastro_estado_chamado' : $cec = 27;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
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
		
		$estChaDAO = new EstadoChamadoDAO();
		$validar = $estChaDAO->VerificarCadastro($nome);
		
		if (empty($validar)){
		
			$status = 'ativado';
			
			$estCadDAO = new EstadoChamadoDAO();
			$result = $estCadDAO->cadastrar($nome, $status);
			
			if($result){
				?> <script type="text/javascript"> alert('Estado chamado cadastrado com sucesso!'); window.location="index.php";  </script><?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra, causa	possivel <?php echo $result; ?> </font> </div> <?php
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Ja existe um cadastro com esse nome </font> </div> <?php
		}
		
	}

}//Fecha o if que verifica se o post foi executado
?>
		
<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastra o estado do chamado</b></h1>
</div>

		<form action="" method="post" id="form" name="form">

			<div class="control-group">
				<label class="w3-text-brown w3-left">Informe o estado do chamado</label>
				<input type="text" name="nome" required class="w3-input w3-border w3-light-grey" placeholder="Informe o estado do chamado" maxlength="50" />
			</div>
			
			<br> <input class="w3-btn w3-blue" type="submit" value="  Cadastrar  " />

		</form><br>

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