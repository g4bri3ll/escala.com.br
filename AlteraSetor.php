<?php 
session_start();

include_once 'Model/Modelo/Setor.php';
include_once 'Model/DAO/SetorDAO.php';
include_once 'Model/DAO/HorasDAO.php';

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
<title>Altera Setor</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'altera_setor' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<div class="table-responsive" align="center">

<div align="center">
	<div style="width: 80%">
	
<?php
if (!empty($_POST)){
	
if (empty($_POST['nome'])){
	?> <div class="alert alert-error"> <font size="3px" color="red"> Campos vazio não permitido </font> </div> <?php
	} else {
	
		$nome = $_POST['nome'];
		//Colocar os dados em minusculo
		$nome = strtolower($nome);
		$idUni = $_GET['idUni'];
		$id = $_GET['id'];
		
		$setDAO = new SetorDAO();
		$verificar = $setDAO->BuscaDadosParaAlterar($nome, $idUni);
		
		if (empty($verificar)){
			
			$setor = new Setor();
			$setor->id = $id;
			$setor->nome = $nome;
		
			$setDAO = new SetorDAO();
			$result = $setDAO->alterar($setor);
			
			if($result){
				?> <script type="text/javascript"> alert('Setor alterado com sucesso!'); window.location="EnviaAlteraSetor.php";  </script> <?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra o setor, causa	possivel <?php echo $result; ?> </font> </div> <?php
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Esse nome do setor ja foi cadastrado! </font> </div> <?php
		}
		
	}//fecha o post que verifica o se o campo esta vazio
	
}

//Inicia o get para lista o usuario
$id = $_GET['id'];
$setDAO = new SetorDAO();
$array = $setDAO->ListaPeloId($id);
//Se o array estiver vazio ele redirecionar para o listaUsuario.php
if (!empty($array)){
	
  foreach ($array as $usuDAO => $lista){
	
  	$id = $lista['id'];
	$nomeSetor = $lista['nome_setor'];
	$nomeUnidade = $lista['nome'];
	$idUni = $lista['id_unidade'];
	
}
?>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Alteracao o setor</b></h1>
</div>

  <form action="AlteraSetor.php?id=<?php echo $id; ?>&idUni=<?php echo $idUni; ?>" method="post">
	
	<div class="control-group">
	<span> Informe o novo nome do setor </span>
	<input type="text" name="nome" value="<?php echo $nomeSetor; ?>" class="w3-input w3-border w3-light-grey" placeholder="Digite o nome do setor" maxlength="80"/>
	</div><br>
	
	<div class="control-group">
	<span> Nome da unidade do setor </span>
	<input type="text" name="nomeUnidade" disabled="disabled" value="<?php echo $nomeUnidade; ?>" class="w3-input w3-border w3-light-grey"/>
	</div><br>
	
	<input class="w3-btn w3-blue"  type="submit" value="  Alterar  " />

</form><br>

<a href="EnviaAlteraSetor.php"  class="w3-btn w3-blue">Cancelar Alteracao</a>

<?php } else { header("Location: EnviaAlteraSetor.php"); }?>

	</div>
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