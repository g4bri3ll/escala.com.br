<?php
session_start();

include_once 'Model/DAO/TrocaServicoDAO.php';
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
<title>Lista troca de servico</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'lista_troca_servico' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<div class="table-responsive" align="center">

<?php 
if (!empty($_POST['nome'])){
	
	$nome = $_POST['nome'];
	//Colocar os dados em minusculo
	$nome = strtolower($nome);
	
	$troDAO = new TrocaServicoDAO();
	$array = $troDAO->BuscarPeloNome($nome);
	
	if (empty($array)){
		?> <script type="text/javascript"> alert('Nome do funcionario nao encontrado!'); window.location="ListaTrocaServico.php"; </script>  <?php
	}
	?>
<a href="ListaTrocaServico.php"> Cancelar Buscar </a>	
<?php 
} else {
	$troDAO = new TrocaServicoDAO();
	$array = $troDAO->ListaTudo();
}
?>
<div align="center">
	<div style="width: 80%">
	
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>

<form action="" method="post"> 
<h3> Buscar pelo nome de quem solicitou </h3>
<div class="input-append">
<input type="text" name="nome" class="w3-input w3-border w3-light-grey" id="appendedInputButton" maxlength="100">
<input type="submit" value="Buscar" class="w3-btn w3-blue w3-input">
</div>
</form>

	</div>
</div>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Lista todas as troca de servico!</b></h1>
</div>
	
	<table class="table table-hover">
	<tr align="center" class="info">
	<td><label class=""> Nome quem solicitou </label></td>
	<td><label class=""> Nome quem tirou </label></td>
	<td><label class=""> Comfirmacao quem tira </label></td>
	<td><label class=""> Comfirmacao do chefe </label></td>
	<td><label class=""> Data quem solicitou </label></td>
	<td><label class=""> Data Quem tirou </label></td>
	<td><label class=""> Motivo da troca </label></td>
	</tr>
	
<?php foreach ( $array as $usu => $list ) { ?>
	
	<tr align="center">
	<td><label class=""> <?php echo $list['id_usuario_solicitante']; ?> </label></td>
	<td><label class=""> <?php echo $list['id_usuario_tira']; ?> </label></td>
	<td><label class=""> <?php echo $list['libera_troca_quem_tira']; ?> </label></td>
	<td><label class=""> <?php echo $list['libera_troca']; ?> </label></td>
	<td><label class=""> <?php echo $list['data_solicitante']; ?> </label></td>
	<td><label class=""> <?php echo $list['data_tira']; ?> </label> </td>
	<td><label class=""> <?php echo $list['motivo_troca']; ?> </label> </td>
	</tr>

<?php } ?>

	</table>
	
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