<?php
session_start();

include_once 'Model/DAO/SetorDAO.php';

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
<title>Lista setor</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'lista_sub_setor' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<div class="table-responsive" align="center">

<?php 
//Aqui esta a parte onde excluir o usuario
if (!empty($_GET['id']) || !empty($_GET['acao'])){
	$recebe = $_GET['acao'];
	$id = $_GET['id'];
	if ($recebe === "exc"){
		$status = "desativado";
		$setDAO = new SetorDAO();
		$verificaResult = $setDAO->CancelarPeloIdSubSetor($status, $id);
		if ($verificaResult){
			?> <script type="text/javascript"> alert('Sub Setor excluido com sucesso'); window.location="ListaSubSetor.php"</script> <?php
        } else {
        	?> <script type="text/javascript"> alert('Erro ao excluir o sub setor!'); window.location="ListaSubSetor.php"</script> <?php
        }
	}
}
?>

<?php 
if (!empty($_POST['nome'])){
	
	$nome = $_POST['nome'];
	//Colocar os dados em minusculo
	$nome = strtolower($nome);
	
	$setDAO = new SetorDAO();
	$array = $setDAO->BuscarPeloNomeSubSetor($nome);
	
	if (empty($array)){
		?> <script type="text/javascript"> alert('Nome do sub setor nao encontrado'); window.location="ListaSubSetor.php"; </script>  <?php
	}
	?>
<a href="ListaSubSetor.php"> Cancelar Buscar </a>	
<?php 
} else {
	$setDAO = new SetorDAO();
	$array = $setDAO->listSubSetor();
}
?>
<div align="center">
	<div style="width: 80%">
	
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>

<form action="" method="post"> 
<h3> Buscar sub setor especifica pelo nome </h3>
<div class="input-append">
<input type="text" name="nome" class="w3-input w3-border w3-light-grey" id="appendedInputButton" maxlength="100">
<input type="submit" value="Buscar" class="w3-btn w3-blue w3-input">
</div>
</form>

	</div>
</div>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Lista de todos os sub setores cadastrado!</b></h1>
</div>
	
	<table class="table table-hover">
	<tr align="center" class="info">
	<td><label class=""> Nome sub setor </label></td>
	<td><label class=""> Acao </label></td>
	</tr>
	
<?php foreach ( $array as $usu => $list ) { ?>
	
	<tr align="center">
	<td><label class=""> <?php echo $list['nome']; ?> </label></td>
	<td>
	<a href="ListaSetor.php?acao=exc&id=<?php echo $list['id']; ?>"> <i class="fa fa-remove"></i> | </a>
	<a href="AlteraSetor.php?id=<?php echo $list['id']; ?>"> <i class="fa fa fa-edit"></i> </a>
	</td>
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