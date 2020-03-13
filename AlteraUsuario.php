<?php 
session_start();

include_once 'Model/Modelo/Usuario.php';
include_once 'Model/DAO/UsuarioDAO.php';

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
<title>Altera Usuario</title>
</head>
<body>

<?php 
if (!empty($_SESSION['nivel_acesso'])){
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
	
<?php
if (!empty($_POST)){
	
if (empty($_POST['nome']) || empty($_POST['apelido']) && empty($_POST['email'])){
	?> <div class="alert alert-error"> <font size="3px" color="red"> Campos vazio não permitido </font> </div> <?php
	} else {
	
		$nome = $_POST['nome'];
		//Colocar os dados em minusculo
		$nome = strtolower($nome);
		$apelido = $_POST['apelido'];
		//Colocar os dados em minusculo
		$apelido = strtolower($apelido);
		$email = $_POST['email'];
		$idUsuario = $_GET['id'];
		
		$usuarioDAO = new UsuarioDAO();
		$resultado = $usuarioDAO->ValidarDadosParaAlterar($nome, $apelido, $email, $idUsuario);
		
		if (!empty($resultado['nome'])){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Usuario ja cadastrado com esse nome </font> </div> <?php
			header("Location: AlteraUsuarios.php?id="+$idUsuario);
		} else if (!empty($resultado['apelido'])){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Usuário ja cadastrado com essa apelido </font> </div> <?php
			header("Location: AlteraUsuarios.php?id="+$idUsuario);
		} else if (!empty($resultado['email'])){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Usuario ja cadastrado com esse email </font> </div> <?php
			header("Location: AlteraUsuarios.php?id="+$idUsuario);
		} else {
	
			$usu = new Usuario();
			$usu->nome = $nome;
			$usu->apelido = $apelido;
			$usu->email = $email;
			$usu->id = $idUsuario;
			
			$cad = new UsuarioDAO();
			$result = $cad->alterar($usu);
			
			if ($result){
				?> <script type="text/javascript"> alert('Usuario alterado com sucesso!'); window.location="ListaUsuarios.php"; </script> <?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao alterar o usuario, causa possivel <?php echo $result; ?>  </font> </div> <?php
			}
			
		}//Fecha o else que esta verificando se as senhas estão certas

	}//fecha o post que verifica o se o campo esta vazio
	
}

//Inicia o get para lista o usuario
$id = $_GET['id'];
$usuDAO = new UsuarioDAO();		
$array = $usuDAO->listaUsuarioParaAlterar($id);
//Se o array estiver vazio ele redirecionar para o listaUsuario.php
if (!empty($array)){
	
  foreach ($array as $usuDAO => $lista){
  	$id = $lista['id'];
	$nomeUsu = $lista['nome_usuario'];
	$apelidoUsu = $lista['apelido'];
	$cpf = $lista['cpf'];
	$email = $lista['email'];
}
?>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Alteracao de Usuario</b></h1>
</div>

  <form action="AlteraUsuario.php?id=<?php echo $id; ?>" method="post">
	
	<div class="control-group">
	<span> Informe o novo nome </span>
	<input type="text" name="nome" value="<?php echo $nomeUsu; ?>" class="w3-input w3-border w3-light-grey" placeholder="Digite seu nome" maxlength="80" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{4,20}$"/>
	</div><br>
	<div class="control-group">
	<span> Informe o apeldio </span>
	<input type="text" name="apelido" value="<?php echo $apelidoUsu; ?>" class="w3-input w3-border w3-light-grey" placeholder="Digite o apelido" maxlength="80" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{4,20}$"/>
	</div><br>
	<div class="control-group">
	<span> CPF </span>
	<input type="text" value="<?php echo $cpf; ?>" />
	</div><br>
	<div class="control-group">
	<span> Informe o novo email </span>
	<input type="email" required name="email" value="<?php echo $email; ?>" class="w3-input w3-border w3-light-grey" placeholder="Digite o email" maxlength="200"/>
	</div><br>
	
	<input class="w3-btn w3-blue"  type="submit" value="  Alterar  " /><br><br>

</form>

<a href="EnviaAlteraUsuario.php"  class="w3-btn w3-blue">Cancelar Alteracao</a>

<?php } else { header("Location: EnviaAlteraUsuario.php"); }?>

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