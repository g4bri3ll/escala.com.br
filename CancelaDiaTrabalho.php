<?php
session_start();

include_once 'Model/DAO/DiaTrabalhaDAO.php';
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
<title>Cancelar o dia que o usuario foi cadastrado para trablaho</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cancela_dia_trabalho' : $cdt = 36;   break ;
		}
	}	
	if (!empty($cdt) || $_SESSION['nivel_acesso'] === "1"){
?>

<div class="table-responsive" align="center">

<?php 
//Aqui esta a parte onde excluir o usuario
if (!empty($_GET['id']) || !empty($_GET['acao'])){
	$recebe = $_GET['acao'];
	$id = $_GET['id'];
	if ($recebe === "exc"){
		$status = "desativado";
		$desDAO = new DiaTrabalhaDAO();
		$verificaResult = $desDAO->DesativaDia($id, $status);
		if ($verificaResult){
			?> <script type="text/javascript"> alert('Dia trabalhado excluido com sucesso'); window.location="CancelaDiaTrabalho.php"</script> <?php
        } else {
        	?> <script type="text/javascript"> alert('Erro ao excluir o dia de trabalho!'); window.location="CancelaDiaTrabalho.php"</script> <?php
        }
	}
}
?>

<?php 
if (!empty($_POST['nome'])){
	
	$nome = $_POST['nome'];
	//Colocar os dados em minusculo
	$nome = strtolower($nome);
	
	$diaDAO = new DiaTrabalhaDAO();
	$array = $diaDAO->ListPeloNomeUsuario($nome);
	
	if (empty($array)){
		?> <script type="text/javascript"> alert('Nome do usuario nao encontrado'); window.location="CancelaDiaTrabalho.php"; </script>  <?php
	}
	?>
<a href="CancelaDiaTrabalho.php"> Cancelar Buscar </a>	
<?php 
} else {
	$diaDAO = new DiaTrabalhaDAO();
	$array = $diaDAO->listaComUsuario();
}
?>
<div align="center">
	<div style="width: 80%">
	
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>

<form action="" method="post"> 
<h3> Buscar usuario especifica pelo nome </h3>
<div class="input-append">
<input type="text" name="nome" class="w3-input w3-border w3-light-grey" id="appendedInputButton" maxlength="100">
<input type="submit" value="Buscar" class="w3-btn w3-blue w3-input">
</div>
</form>

	</div>
</div>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Lista de todos os usuario com dia para trabalar!</b></h1>
</div>
	
	<table class="table table-hover">
	<tr align="center" class="info">
	<td><label class=""> Nome do usuario</label></td>
	<td><label class=""> Dia inicio</label></td>
	<td><label class=""> Dia Fim</label></td>
	<td><label class=""> Acao </label></td>
	</tr>
	
<?php foreach ( $array as $usu => $list ) { ?>
	
	<tr align="center">
	<td><label class=""> <?php echo $list['apelido']; ?> </label></td>
	<td><label class=""> <?php echo $list['data_inicio']; ?> </label></td>
	<td><label class=""> <?php echo $list['data_final']; ?> </label></td>
	<td>
	<a href="CancelaDiaTrabalho.php?acao=exc&id=<?php echo $list['id']; ?>"> <i class="fa fa-remove"></i></a>
	</td>
	</tr>

<?php } ?>

	</table>
	
</div>

<?php
	} else {
		?>  <script type="text/javascript"> alert('Usuario nao tem privilegio de acessar esta pagina!'); window.location="index.php";  </script> <?php
	}
} else {
	header("Location: index.php");
}
?>

</body>
</html>