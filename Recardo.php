<?php

session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/EstadoChamadoDAO.php';
include_once 'Model/Modelo/Recardo.php';
include_once 'Model/DAO/RecardoDAO.php';

//Atualizar com o fusio horario do brasil
date_default_timezone_set('America/Bahia');

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
<title>Recardo ao colaborador</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'recardo' : $re = 26;  break ;
		}
	}
	if (!empty($re) || $_SESSION['nivel_acesso'] === "1"){


$idSetor = $_SESSION['id_setor'];
$idSession = $_SESSION['id'];

//Lista todos os usuario que faz parte do setor do usuario
$usuDAO = new UsuarioDAO();
$arrayNome = $usuDAO->listaUsuParaPlantao($idSetor, $idSession);

//Lista todos os tipos de estado do chamado
$estChaDAO = new EstadoChamadoDAO();
$arrayEstado = $estChaDAO->lista();

?>

<?php 
if (!empty($_POST)){
	
	if (empty($_POST['estadoChamado']) || empty($_POST['comentario'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Preencha todos os campos </font> </div> <?php
	} else {
		
		$estadoCha  = $_POST['estadoChamado'];
		$comentario = $_POST['comentario'];
		$idTecnico  = $_POST['idTecnico'];
		$idSession  = $_SESSION['id'];
		
		$recardo = new Recardo();
		$recardo->status = 'nao_visto';
		$recardo->idUsuRecebe = $idTecnico;
		$recardo->idUsuEnvia = $idSession;
		$recardo->idEstadoChamado = $estadoCha;
		$recardo->comentario = $comentario;
		$recardo->data = date('Y-m-d H:i:s');
		
		
		$recDAO = new RecardoDAO();
		$result = $recDAO->cadastrar($recardo);
		
		if($result){
			?> <script type="text/javascript"> alert('Recardo cadastrado com sucesso!'); window.location="index.php";  </script><?php
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra, causa	possivel <?php echo $result; ?> </font> </div> <?php
		}
		
	}
}
?>

<div class="container" style="background-color: ;">

<div class="w3-container w3-blue">
  <h2>Recardo ao colaborador</h2>
</div>

<form class="w3-container" method="post" action="">
  
  <label class=""><font color="red">*</font>Estado do chamado</label>
  <select class="w3-select w3-light-grey" name="estadoChamado">
  <option value="" disabled selected>Estado</option>
  <?php foreach ($arrayEstado as $estChaDAO =>$valor){ ?>
  <option value="<?php echo $valor['id']; ?>"><?php echo $valor['nome']; ?></option>
  <?php } ?>
  </select>
  
  <label class="">Informe aqui o colaborador do para receber a mensagem</label>
  <select class="w3-select w3-light-grey" name="idTecnico">
  <option value="" disabled selected>Tecnicos</option>
  <?php foreach ($arrayNome as $usuDAO =>$value){ ?>
  	<option value="<?php echo $value['id']; ?>"><?php echo $value['nome_usuario']; ?></option>
  <?php } ?>
  </select>
  
  <label class=""><font color="red">*</font>Mensagem:</label>
  <textarea name="comentario" class="w3-input w3-border w3-light-grey" maxlength="400"></textarea><br>

  <button class="w3-btn w3-blue">Registra</button>
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