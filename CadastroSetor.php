<?php
session_start();
//Nessa chasse ela descrever quais são as regras para cada setor fazer o seu cadastro de horas

include_once 'Model/Modelo/Setor.php';
include_once 'Model/DAO/SetorDAO.php';
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
<title>Cadastrado de Setor</title>
</head>
<body>
	
<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastro_setor' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
	
?>

<div align="center">
	<div style="width: 80%">
	
<?php
if (!empty($_POST)){
	
	if (empty($_POST['nome']) || empty($_POST['idUnidadeTrabalho'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Preencha todos os campos </font> </div> <?php
	} else {
		
		$nome = $_POST['nome'];
		//Colocar os dados em minusculo
		$nome = strtolower($nome);
		$idUnidadeTrabalho = $_POST['idUnidadeTrabalho'];
		
		$setDAO = new SetorDAO();
		$verificar = $setDAO->VerificarNomeCad($nome, $idUnidadeTrabalho);
				
		if (empty($verificar)){
			
			$setor = new Setor();
			$setor->nome = $nome;
			$setor->status = "ativado";
			$setor->idUnidadeTrabalho = $idUnidadeTrabalho;
			
			$cad = new SetorDAO();
			$result = $cad->cadastrar($setor);
			
			if($result){
				?>  <script type="text/javascript"> alert('Setor cadastrado com sucesso!'); window.location="index.php";  </script> <?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastrar o setor! </font> </div> <?php
			}
	
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Esse nome do setor ja foi cadastrado, para essa unidade de trabalho! </font> </div> <?php
		}

	}

}//Fecha o if que verifica se o post foi executado
?>

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastro de Setor</b></h1>
</div>

	<form action="" method="post" id="form" name="form">

	<span>Informe o hospital do setor</span>
	<select name="idUnidadeTrabalho" class="w3-input w3-border w3-light-grey" id="appendedInput">
		<option value="">Selecionar uma unidade hospitalar</option>
		<?php 
			//Lista todas as unidade para lista na tela, quando selecionada
			$uniTraDAO = new UnidadeHospitalarDAO();
			$arrayUnidade = $uniTraDAO->lista();
			foreach ($arrayUnidade as $uniTraDAO => $a){
		?>
		<option value="<?php echo $a['id']; ?>"><?php echo $a['nome']; ?></option>
		<?php } ?>
	</select><br>

		<div class="control-group">
			<span>Informe o nome do setor</span>
			<input type="text" name="nome" required class="w3-input w3-border w3-light-grey" placeholder="Digite o nome do setor" maxlength="100" />
		</div>
					
			<br> <input class="w3-btn w3-blue" type="submit" value="  Cadastrar  " />		
		</form>

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