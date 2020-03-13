<?php
session_start();

include_once 'Model/Modelo/UnidadeHospitalar.php';
include_once 'Model/DAO/UnidadeHospitalarDAO.php';
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
<title>Cadastrado de Unidade Hospitalar</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastro_unidade_hospitalar' : $as = 15;   break ;
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
		
		$unidade = $_POST['nome'];
		//Colocar os dados em minusculo
		$unidade = strtolower($unidade);
		
		$uniDAO = new UnidadeHospitalarDAO();
		$validar = $uniDAO->ValidarDados($unidade);
		
		if (empty($validar)){
		
			$uniHospitalar = new UnidadeHospitalar();
			$uniHospitalar->nome = $unidade;
			$uniHospitalar->status = "ativado";
			
			$uniDAO = new UnidadeHospitalarDAO();
			$result = $uniDAO->cadastrar($uniHospitalar);
			
			if($result){
				?> <script type="text/javascript"> alert('Unidade hospitalar cadastrado com sucesso!'); window.location="index.php";  </script><?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra, causa	possivel <?php echo $result; ?> </font> </div> <?php
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Existe uma unidade com esse nome </font> </div> <?php
		}
		
	}

}//Fecha o if que verifica se o post foi executado
?>
		
		<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastro de Unidade Hospitalar</b></h1>
</div>

		<form action="" method="post" id="form" name="form">

			<div class="control-group">
				<label class="w3-text-brown w3-left">Informe o nome do hospital</label>
				<input type="text" name="nome" required class="w3-input w3-border w3-light-grey" placeholder="Informe a unidade hospitalar" maxlength="100" />
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