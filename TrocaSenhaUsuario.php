<?php 
session_start();

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
<title>Troca a senha do usuario</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'troca_senha_usuario' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<div class="table-responsive" align="center">

<?php
if (!empty($_POST)){
	
	if (empty($_POST['cpf']) || empty($_POST['senha']) || empty($_POST['comfirmarSenha'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Campos vazio não permitido </font> </div> <?php
	} else {
	
		$cpf = $_POST['cpf'];
		$senha = $_POST['senha'];
		$comfirmar_senha = $_POST['comfirmarSenha'];
		
		if ($senha !== $comfirmar_senha){
			?> <div class="alert alert-error"> <font size="3px" color="red"> A "senha" deve ser a mesma senha de "comfirme sua senha"! </font> </div> <?php
			} else {
		
			$cad = new UsuarioDAO();
			$result = $cad->alterarSenhaEnviadaPorEmail($senha, $cpf);
			
			if ($result){
				?>  <script type="text/javascript"> alert('Senha do usuario alterado com sucesso!'); window.location="index.php";  </script> <?php
			} else {
				?>  <script type="text/javascript"> alert('Informe uma data valida!'); </script> <?php
			}
		
		}//
	
	}//
}
?>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Troca de senha do usuario</b></h1>
</div>

  <form action="" method="post" id="form" name="form">
  
  	<div class="control-group">
	<span><img alt="" src="fotos/setor.png" width="3%"  height="3%"> </span>
	<select name="cpf" class="span3">
		<?php 
		$usuDAO = new UsuarioDAO();
		$array = $usuDAO->ListaNomesParaAlteraSenha();
		foreach ($array as $usuDAO => $list){
		?>
			<option value="<?php echo $list['cpf']?>"><?php echo $list['apelido']; ?></option>
		<?php } ?>
	</select><br>
	</div><br>
	
	<span><img alt="" src="fotos/senha.jpg" width="3%" height="3%"> </span>
	<input type="password" name="senha" required placeholder="Digite sua senha" maxlength="36"/>
	<span><img alt="" src="fotos/senha.jpg" width="3%" height="3%"> </span>
	<input type="password" name="comfirmarSenha" required placeholder="Comfirme sua senha" maxlength="36"/><br><br>
	
	<input class="w3-btn w3-blue"  type="submit" value="  Alterar a senha  " /><br><br>

</form>

<a href="index.php"  class="w3-btn w3-blue">Cancelar</a>

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