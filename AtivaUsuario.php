<?php
session_start();

include_once 'Model/DAO/AtivacaoUsuarioDAO.php';

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
<title>Ativa usuario</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'ativa_usuario' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<?php 
$atiDAO = new AtivacaoUsuarioDAO();
$array = $atiDAO->ListaUsuDesativado();
	
if (!empty($array)){
?>

<div align="center">
	<div style="width: 80%">
	
<?php 
//Aqui esta a parte onde excluir o usuario
if (!empty($_GET['id']) || !empty($_GET['acao'])){
	$recebe = $_GET['acao'];
	$idusu = $_GET['id'];
	if ($recebe === "ati"){
		$status = "ativado";
		$atiDAO = new AtivacaoUsuarioDAO();
		$verificaResult = $atiDAO->AtivaUsuario($status, $idusu);
		if ($verificaResult){
			header("Location: AtivaUsuario.php");
			?> <div class="alert alert-error"> <font size="3px" color="lime"> Usuario ativado com sucesso </font> </div> <?php
        } else {
        	?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao ativar o usuario: <?php echo $verificaResult; ?> </font> </div> <?php 
        }			
	}
}
?>

<!-- slogan -->
<div class="siteListaFilmeTable">

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Ativacao de usuarios!</b></h1>
</div>
	
	<table class="table table-hover">
	<tr align="center" class="info">
	<td><label class=""> Usuarios </label></td>
	<td><label class=""> Ativacao </label></td>
	</tr>
	
<?php foreach ( $array as $aceDAO => $lista ) { ?>
	
	<tr align="center">
	<td><label class=""> <?php echo $lista['apelido']; ?> </label></td>
	<td>
	<a href="AtivaUsuario.php?acao=ati&id=<?php echo $lista['id']; ?>"> <i class="fa fa-check"></i> </a>
	</td>
	</tr>

<?php } ?>

	</table>

</div>
<!-- fim slogan -->

	</div>
</div>

<?php } else { ?>

	<script type="text/javascript"> alert('Nao existe usuario para ativar'); window.location="index.php"</script>
	
<?php 
	} 
	
	} else {
		?>  <script type="text/javascript"> alert('Usuario não tem privilegio de acessar esta pagina!'); window.location="index.php";  </script> <?php
	}
} else {
	header("Location: index.php");
}
?>

</body>
</html>