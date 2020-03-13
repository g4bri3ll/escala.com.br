<?php
session_start();

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
<title>Lista Unidade Hospitalar</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'lista_unidade_hospitalar' : $as = 15;   break ;
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
		$uniDAO = new UnidadeHospitalarDAO();
		$verificaResult = $uniDAO->delete($status, $id);
		if ($verificaResult){
			?> <script type="text/javascript"> alert('Unidade excluido com sucesso'); window.location="ListaUnidadesHospitalar.php"</script> <?php
        } else {
        	?> <script type="text/javascript"> alert('Erro ao excluir a unidade!'); window.location="ListaUnidadesHospitalar.php"</script> <?php 
        }			
	}
}
?>

<div align="center">
	<div style="width: 80%">
	
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
	
<form action="" method="post"> 
<h3> Buscar unidade pelo nome </h3>
<div class="input-append">
<input type="text" name="nome" class="w3-input w3-border w3-light-grey" maxlength="100">
<input type="submit" value="Buscar" class="w3-btn w3-blue w3-input">
</div>
</form>

	</div>
</div>

<?php 
if (!empty($_POST['nome'])){
	
	$nome = $_POST['nome'];
	//Colocar os dados em minusculo
	$nome = strtolower($nome);
	
	$uniDAO = new UnidadeHospitalarDAO();
	$array = $uniDAO->ListaPeloNome($nome);
	
	if (empty($array)){
		?> <script type="text/javascript"> alert('Nome nao encontrado'); window.location="ListaUnidadesHospitalar.php"</script>  <?php
	}
	?>
<a href="ListaUnidadesHospitalar.php"> <i class="fa fa-repeat"></i> </a>
<?php 
} else {
	$uniDAO = new UnidadeHospitalarDAO();
	$array = $uniDAO->lista();
		
}
?>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Lista de todas as unidades hospitalar!</b></h1>
</div>
	
	<table class="table table-hover">
	<tr align="center" class="info">
	<td><label class=""> Nome </label></td>
	<td><label class=""> Acao </label></td>
	</tr>
	
<?php foreach ( $array as $usu => $list ) { ?>
	
	<tr align="center">
	<td><label class=""> <?php echo $list['nome']; ?> </label></td>
	<td>
	<a href="ListaUnidadesHospitalar.php?acao=exc&id=<?php echo $list['id']; ?>"> <i class="fa fa-remove"></i> </a>
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