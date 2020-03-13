<?php
session_start();

include_once 'Model/DAO/SetorDAO.php';
include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/FuncaoChefeSetorDAO.php';
include_once 'Model/DAO/ChefeSetorDAO.php';

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
<title>Cadastra chefe no setor</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'chefe_setor' : $cst = 31;   break ;
		}
	}	
	if (!empty($cst) || $_SESSION['nivel_acesso'] === "1"){
?>

<div align="center">
	<div style="width: 80%">

<?php
if (!empty($_POST)){
	
	if (empty($_POST['idFuncaoChefe']) || empty($_POST['idSetor']) || empty($_POST['idUsuario'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Campos vazio nao permitido </font> </div> <?php
	} else {
		
		$idFuncaoChefe    = $_POST['idFuncaoChefe'];
		$idSetor          = $_POST['idSetor'];
		$idUsuario        = $_POST['idUsuario'];
		$visualizarPainel = $_POST['visualizar'];
		
		$cheSetDAO = new ChefeSetorDAO();
		$validar = $cheSetDAO->ValidarDados($idUsuario, $idFuncaoChefe, $idSetor);
		
		if (empty($validar)){
		
			$status = 'ativado';
			
			$cheSetDAO = new ChefeSetorDAO();
			$result = $cheSetDAO->cadastrar($idSetor, $idUsuario, $idFuncaoChefe, $status, $visualizarPainel);
			
			if($result){
				?> <script type="text/javascript"> alert('Funcao e usuario cadastrada no setor com sucesso!'); window.location="index.php";  </script><?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra, causa	possivel <?php echo $result; ?> </font> </div> <?php
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> O usuario ja esta cadastrado para o setor e a funcao</font> </div> <?php
		}
		
	}

}//Fecha o if que verifica se o post foi executado
?>
		
<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastra chefe no setor</b></h1>
</div>

		<form action="" method="post">

			<p><label class="w3-text-brown w3-left"><b>Informe o setor</b></label>
			<select name="idSetor" class="w3-input w3-border w3-light-grey">
				<option value="">Escolha o setor</option>
				<?php 
					$idUnidade = $_SESSION['id_unidade'];
					$setDAO = new SetorDAO();
					$array = $setDAO->ListSetor($idUnidade);
					foreach ($array as $setDAO => $value){
				?>
				<option value="<?php echo $value['id']; ?>"><?php echo $value['nome_setor']; ?></option>
				<?php } ?>
			</select></p>
			
			<p><label class="w3-text-brown w3-left"><b>Informe a funcao do chefe no setor</b></label>
			<select name="idFuncaoChefe" class="w3-input w3-border w3-light-grey">
				<option value="">Escolha a funcao que o usuario ira exercer</option>
				<?php 
					$funCheDAO = new FuncaoChefeSetorDAO();
					$arrayFun = $funCheDAO->lista();
					foreach ($arrayFun as $funCheDAO => $valu){
				?>
				<option value="<?php echo $valu['id']; ?>"><?php echo $valu['nome']; ?></option>
				<?php } ?>
			</select></p>
			
			<p><label class="w3-text-brown w3-left"><b>Informe o usuario</b></label>
			<select name="idUsuario" class="w3-input w3-border w3-light-grey">
				<option value="">Informe o usuario</option>
				<?php 
					$usuDAO = new UsuarioDAO();
					$arrayUsu = $usuDAO->ListaUsuChefe();
					foreach ($arrayUsu as $usuDAO => $valor){
				?>
				<option value="<?php echo $valor['id']; ?>"><?php echo $valor['apelido']; ?></option>
				<?php } ?>
			</select></p>
			
			<br><div align="left">
				<span> Visualizar o painel de escala de servico </span><br>
				Sim <input type="radio" value="1" name="visualizar">
				Nao <input type="radio" value="0" name="visualizar">
			</div><br>
			
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