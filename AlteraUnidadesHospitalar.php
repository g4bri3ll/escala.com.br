<?php 

session_start();

include_once 'Model/Modelo/UnidadeHospitalar.php';
include_once 'Model/DAO/UnidadeHospitalarDAO.php';

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
<title>Altera Unidades de trabalho</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'altera_unidade_hospitalar' : $as = 15;   break ;
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
		$id = $_GET['id'];
		
		$uniDAO = new UnidadeHospitalarDAO();
		$resultado = $uniDAO->ValidarDadosParaAlterar($nome, $id);
		
		if (!empty($resultado)){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Unidade ja cadastrado com esse nome </font> </div> <?php
			header("Location: AlteraUnidadesHospitalar.php?id="+$id);
		} else {
	
			$uniHospitalar = new UnidadeHospitalar();
			$uniHospitalar->nome = $nome;
			$uniHospitalar->id = $id;
			
			$uniDAO = new UnidadeHospitalarDAO();
			$result = $uniDAO->alterar($uniHospitalar);
			
			if ($result){
				?> <script type="text/javascript"> alert('Unidade alterado com sucesso!'); window.location="EnviaAlteraUnidadesHospitalar.php"; </script> <?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao alterar o usuario, causa possivel <?php echo $result; ?>  </font> </div> <?php
			}
			
		}//Fecha o else que esta verificando se as senhas estão certas

	}//fecha o post que verifica o se o campo esta vazio
	
}

//Inicia o get para lista o usuario
$id = $_GET['id'];
$uniDAO = new UnidadeHospitalarDAO();
$array = $uniDAO->listaParaAlterar($id);
//Se o array estiver vazio ele redirecionar para o listaUsuario.php
if (!empty($array)){
	
  foreach ($array as $usuDAO => $lista){
	
  	$id = $lista['id'];
	$nome = $lista['nome'];
	
}
?>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Alteracao de Unidade Hospitalar</b></h1>
</div>

  <form action="AlteraUnidadesHospitalar.php?id=<?php echo $id; ?>" method="post">
	
	<div class="control-group">
	<span> Altera nome da unidade </span>
	<input type="text" name="nome" value="<?php echo $nome; ?>" class="w3-input w3-border w3-light-grey" placeholder="Digite a unidade" maxlength="80"/>
	</div><br>
	
	<input class="w3-btn w3-blue"  type="submit" value="  Alterar  " />

</form><br>

<a href="EnviaAlteraUnidadesHospitalar.php"  class="w3-btn w3-blue">Cancelar Alteracao</a>

<?php } else { header("Location: EnviaAlteraUnidadesHospitalar.php"); }?>

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