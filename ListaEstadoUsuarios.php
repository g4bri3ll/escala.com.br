<?php
session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/StatusUsuarioDAO.php';

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
<title>Lista o estado do usuarios</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'lista_estado_usuario' : $as = 15;   break ;
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
		$staUsuDAO = new StatusUsuarioDAO();
		$verificaResult = $staUsuDAO->deleteId($id);
		if ($verificaResult){
			?> <script type="text/javascript"> alert('Estado do usu�rio excluido com sucesso'); window.location="ListaEstadoUsuarios.php"</script> <?php
        } else {
        	?> <script type="text/javascript"> alert('Erro ao excluir os dados!'); window.location="ListaEstadoUsuarios.php"</script> <?php 
        }			
	}
}
?>

<?php 
if (!empty($_POST['apelido'])){
	
	$apelido = $_POST['apelido'];
	//Colocar os dados em minusculo
	$apelido = strtolower($apelido);
	
	$staUsuDAO = new StatusUsuarioDAO();
	$array = $staUsuDAO->ListaPeloNome($apelido);
		
	if (empty($array)){
		?> <script type="text/javascript"> alert('Nome do usuario nao encontrado'); window.location="ListaEstadoUsuarios.php"</script>  <?php
	}
	?>
<a href="ListaEstadoUsuarios.php"> Cancelar Buscar </a>	
<?php 
} else {
	$staUsuDAO = new StatusUsuarioDAO();
	$array = $staUsuDAO->ListaTudo();
		
}
?>

<div align="center">
	<div style="width: 80%">
	
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>

<form action="" method="post"> 
<h3> Informe o apelido do usuario para listar </h3><br>
<div class="input-append">
<input type="text" name="apelido" class="w3-input w3-border w3-light-grey" id="appendedInputButton" maxlength="100">
<input type="submit" value="Buscar" class="w3-btn w3-blue w3-input">
</div>
</form>

	</div>
</div>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Lista o estado do usuario!</b></h1>
</div>
	
	
	<table class="w3-table">
	<tr align="center" class="info">
	<td><label class=""> Nome usuario </label></td>
	<td><label class=""> Data inicio </label></td>
	<td><label class=""> Data fim </label></td>
	<td><label class=""> Tipo Folga </label></td>
	<td><label class=""> Acao </label></td>
	</tr>
	
<?php foreach ( $array as $usu => $listaUsu ) { ?>
	
		<tr align="center" class="alert alert-success">
		<td><label class=""> <?php echo $listaUsu['apelido']; ?> </label></td>
		<td><label class=""> <?php echo $listaUsu['data_inicio']; ?> </label></td>
		<td><label class=""> <?php echo $listaUsu['data_final']; ?> </label> </td>
		<td><label class=""> <?php echo $listaUsu['tipo_folgas']; ?> </label></td>
		<td>
		<a href="ListaEstadoUsuarios.php?acao=exc&id=<?php echo $listaUsu['id']; ?>"> <i class="fa fa-remove"></i> </a>
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