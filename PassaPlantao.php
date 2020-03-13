<?php

session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/Modelo/PassaPlantao.php';
include_once 'Model/DAO/PassaPlantaoDAO.php';
include_once 'Model/DAO/EstadoChamadoDAO.php';

//Atualizar com o fusio horario do brasil
setlocale( LC_ALL, 'pt_BR.utf-8' );
date_default_timezone_set( 'America/Sao_Paulo' );

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="Content-Type: text/html; charset=UTF-8">
<link rel="stylesheet" href="CSS/style.css" />
<link rel="stylesheet" href="boot/css/bootstrap.css" />
<link rel="stylesheet" href="boot/css/bootstrap.css.map" />
<link rel="stylesheet" href="boot/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Passagem de Plantao</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'passa_plantao' : $pp = 21;  break ;
		}
	}
	if (!empty($pp) || $_SESSION['nivel_acesso'] === "1"){

//Lista todos os tipos de estado do chamado
$estChaDAO = new EstadoChamadoDAO();
$arrayEstado = $estChaDAO->lista();
	
//Pegar os dados da session
$idSetor = $_SESSION['id_setor'];
$idUnidade = $_SESSION['id_unidade'];
$idSession = $_SESSION['id'];

//Pegar a lista de usuario do mesmo setor e unidade mesmo ele proprio
$usuDAO = new UsuarioDAO();
$arrayNome = $usuDAO->listaUsuParaPlantao($idSetor, $idUnidade, $idSession);
?>

<?php 
if (!empty($_POST)){
	
	if (empty($_POST['estadoChamado']) || empty($_POST['comentario'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Preencha todos os campos </font> </div> <?php
	} else {
		
		$numChamado    = $_POST['numeroChamado'];
		$estadoChamado = $_POST['estadoChamado'];
		$idTecnico     = $_POST['idTecnico'];
		$foiFeito      = $_POST['foiFeito'];
		$deveFazer     = $_POST['deveFazer'];
		$comentario    = $_POST['comentario'];
		
		$pasDAO = new PassaPlantaoDAO();
		$verifica = $pasDAO->VerificaNumChamado($numChamado);
		
		if (empty($verifica)){
		
			$passa = new PassaPlantao();
			$passa->numeroChamado = $numChamado;
			$passa->estadoChamado = $estadoChamado;
			$passa->tecnicoCiente = $idTecnico;
			$passa->foiFeito      = $foiFeito;
			$passa->deveFazer     = $deveFazer;
			$passa->idUsuario     = $idSession;
			$passa->comentarios   = $comentario;
			$passa->status        = "ativado";
			$passa->data          = date('Y-m-d H:i:s');
			
			$pasDAO = new PassaPlantaoDAO();
			$result = $pasDAO->cadastrar($passa);
			
			if($result){
				?>  <script type="text/javascript"> alert('Passagem de plantao cadastrada com sucesso!'); window.location="index.php";  </script>  --><?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastrar a passagem de plantao, tente novamente! </font> </div> <?php
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> ja existe um numero de chamado ja adicionado! </font> </div> <?php
		}
		
	}
}
?>

<div class="container" style="background-color: ;">

<div class="w3-container w3-blue">
  <h2>Passagem de plantao</h2>
</div>

<form class="w3-container" method="post" action="">
  <label class="">Numero do chamado</label>
  <input type="text" name="numeroChamado" class="w3-input w3-border w3-light-grey" maxlength="10" />
  
   <label class=""><font color="red">*</font>Estado do chamado</label>
  <select class="w3-select w3-light-grey" name="estadoChamado">
  <option value="" disabled selected>Estado</option>
  <?php	foreach ($arrayEstado as $estChaDAO =>$valor){ ?>
  <option value="<?php echo $valor['id']; ?>"><?php echo $valor['nome']; ?></option>
  <?php } ?>
  </select>
  
  <label class="">Existe algum tecnico ciente do problema</label>
  <select class="w3-select w3-light-grey" name="idTecnico">
  <option value="" disabled selected>Tecnicos</option>
  <?php foreach ($arrayNome as $usuDAO =>$value){ ?>
  	<option value="<?php echo $value['id']; ?>"><?php echo $value['nome_usuario']; ?></option>
  <?php } ?>
  </select>
  
	<div class="w3-row-padding">
	  <div class="w3-half">
	    <label class="">O que foi feito</label>
	    <textarea name="foiFeito" class="w3-input w3-border w3-light-grey" placeholder="" maxlength="200"></textarea>
	  </div>
	  <div class="w3-half">
	    <label class="">O que deve fazer</label>
	    <textarea name="deveFazer" class="w3-input w3-border w3-light-grey" placeholder="" maxlength="200"></textarea>
	  </div>
	</div>
  
  <label class=""><font color="red">*</font>Observacoes</label>
  <textarea name="comentario" class="w3-input w3-border w3-light-grey" maxlength="400"></textarea><br>

  <button class="w3-btn w3-blue">Register</button>
  <a href="index.php" class="w3-btn w3-botton w3-right w3-blue">Retorna</a>
</form>

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