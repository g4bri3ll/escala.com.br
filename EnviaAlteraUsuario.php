<?php
session_start();

include_once 'Model/DAO/UsuarioDAO.php';

?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="CSS/style.css" />
<link rel="stylesheet" href="boot/css/bootstrap.css" />
<link rel="stylesheet" href="boot/css/bootstrap.css.map" />
<link rel="stylesheet" href="boot/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Seleciona usuario para alterar</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'altera_usuario' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<div class="table-responsive" align="center">

<div align="center">
	<div style="width: 80%">
	
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
	
<form action="" method="post"> 
<h3> Informa o nome do usuario para alterar </h3>

<div class="w3-row w3-section">
  <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-user"></i></div>
    <div class="w3-rest">
      <input class="w3-input w3-border w3-light-grey" name="nomeUsuario" type="text" placeholder="Nome do usuario">
      <input type="submit" value="Buscar" class="w3-btn w3-blue w3-input">
    </div>
</div>
</form>

	</div>
</div>

<?php 
if (!empty($_POST['nomeUsuario'])){
	
	$nomeUsuario = $_POST['nomeUsuario'];
	//Colocar os dados em minusculo
	$nomeUsuario = strtolower($nomeUsuario);
	
	$usuDAO = new UsuarioDAO();
	$array = $usuDAO->ListaUsuarioPeloNome($nomeUsuario);
	
	if (empty($array)){
		?> <script type="text/javascript"> alert('Nome do usuario nao encontrado'); window.location="ListaUsuarios.php"</script>  <?php
	} else {
	?>
	
	<table class="table table-hover">
	<tr align="center" class="info">
	<td><label class=""> Nome usuario </label></td>
	<td><label class=""> Apeldio usuario  </label></td>
	<td><label class=""> altera </label></td>
	</tr>
	
<?php foreach ( $array as $usu => $listaUsu ) { ?>
	
	<tr align="center">
	<td><label class=""> <?php echo $listaUsu['nome_usuario']; ?> </label></td>
	<td><label class=""> <?php echo $listaUsu['apelido']; ?> </label></td>
	<td>
	<a href="AlteraUsuario.php?id=<?php echo $listaUsu['id']; ?>"> <i class="fa fa fa-edit"></i> </a>
	</td>
	</tr>

<?php } ?>

	</table>
<?php 
	}//Fecha o if que lista todos os usuarios do banco 
} 
?>
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