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
<title>Lista de usuarios ativados</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'lista_usuario_ativado' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<div class="table-responsive" align="center">

<?php 
//Aqui esta a parte onde excluir o usuario
if (!empty($_GET['id']) || !empty($_GET['acao'])){
	$recebe = $_GET['acao'];
	$idusu = $_GET['id'];
	if ($recebe === "ati"){
		$status = "desativado";
		$atiDAO = new AtivacaoUsuarioDAO();
		$verificaResult = $atiDAO->DesativaUsuario($status, $idusu);
		if ($verificaResult){
			?> <script type="text/javascript"> alert('Usuario desativado com sucesso'); window.location="index.php"</script> <?php
        } else {
        	?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao ativar o usuario: <?php echo $verificaResult; ?> </font> </div> <?php 
        }			
	}
}
?>

<div align="center">
	<div style="width: 80%">
	
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
	
<form action="" method="post"> 
<h3> Buscar usuario especifica pelo apelido dele </h3>

<div class="w3-row w3-section">
  <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-user"></i></div>
    <div class="w3-rest">
      <input class="w3-input w3-border w3-light-grey" name="apelido" type="text" placeholder="Apelido do usuario">
      <input type="submit" value="Buscar" class="w3-btn w3-blue w3-input">
    </div>
</div>
</form>

	</div>
</div>

<?php 
if (!empty($_POST['apelido'])){
	
	$apelido = $_POST['apelido'];
	//Colocar os dados em minusculo
	$apelido = strtolower($apelido);
	
	$atiDAO = new AtivacaoUsuarioDAO();
	$array = $atiDAO->ListaUsuPeloApelido($apelido);
	
	if (empty($array)){
		?> <script type="text/javascript"> alert('Usuario nao encontrado'); window.location="ListaUsuariosAtivado.php"</script>  <?php
	}
	?>
	<a href="ListaUsuariosAtivado.php"> <i class="fa fa-repeat"></i> </a>
<?php 
} else {
	$atiDAO = new AtivacaoUsuarioDAO();
	$array = $atiDAO->ListaUsuAtivado();
}
?>

	<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Todos usuarios ativado!</b></h1>
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
	<a href="ListaUsuarioAtivado.php?acao=ati&id=<?php echo $lista['id']; ?>"> <i class="fa fa-remove"></i> </a>
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