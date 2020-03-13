<?php
session_start();

include_once 'Model/DAO/AcessoPaginasDAO.php';
include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/TipoAcessoDAO.php';

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
<title>Lista de paginas acessiveis web</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'lista_tipo_acesso' : $as = 15;   break ;
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
		$aceDAO = new TipoAcessoDAO();
		$verificaResult = $aceDAO->delete($status, $id);
		if ($verificaResult){
			?> <script type="text/javascript"> alert('Pagina web excluido com sucesso'); window.location="ListaTipoAcesso.php"</script> <?php
        } else {
        	?> <script type="text/javascript"> alert('Erro ao excluir o pagina da web!'); window.location="ListaTipoAcesso.php"</script> <?php 
        }			
	}
}
?>

<div align="center">
	<div style="width: 80%">
	
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
	
<form action="" method="post"> 
<h3>Buscar pagina especifica</h3>
<div class="input-append">
<input type="text" name="nomePagina" class="w3-input w3-border w3-light-grey" id="appendedInputButton" maxlength="100">
<input type="submit" value="Buscar" class="w3-btn w3-blue w3-input">
</div>
</form>

	</div>
</div>

<?php 
if (!empty($_POST['nomePagina'])){
	
	$nomePagina = $_POST['nomePagina'];
	//Colocar os dados em minusculo
	$nomePagina = strtolower($nomePagina);
	
	$pagDAO = new TipoAcessoDAO();
	$array = $pagDAO->MostraPaginasPeloNome($nomePagina);
	
	if (empty($array)){
		?> <script type="text/javascript"> alert('Nome da pagina nao encontrado'); window.location="ListaTipoAcesso.php"</script>  <?php
	}
	?>
	<br><a href="ListaTipoAcesso.php"> <i class="fa fa-repeat"></i> </a>
<?php 
} else {
	$lisDAO = new TipoAcessoDAO();
	$array = $lisDAO->Lista();
}
?>

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Lista de todos as paginas da web cadastrado!</b></h1>
</div>
	
	<div class="tabela_titulo">
		<div class="nome_primeiro"><label class=""> Nome da permissao </label></div>
		<div class="nome_segundo"><label class=""> Paginas Web </label></div>
		<div class="nome_terceiro"><label class=""> Excluir </label></div>
	</div>
	<div class="texto">	
		<?php $valida = 0; ?>	
		<?php foreach ( $array as $aceDAO => $lista ) { ?>
			
			<?php if ($lista['nome'] !== $valida){ ?>
				<div class="nom_primeiro">
					<label class=""> <?php echo $lista['nome']; ?> </label>
				</div>
			<?php } ?>
				
				 <div class="nom_segundo">
				  
				  <?php 
				  //Lista todos os tipo de acesso pelo nome do acesso
				  $acesso = $lista['nome'];
				  $acp = new AcessoPaginasDAO();
				  $arrayPaginaAcesso = $acp->listaPaginasPeloAcesso($acesso);
				  foreach ($arrayPaginaAcesso as $acp => $value){
				  ?>
				 	<label class=""> <?php echo $value['nome_paginas']; ?> </label> | 
				 <?php } ?>
				 <hr>
				 </div>
				 
			<?php if ($lista['nome'] !== $valida){ ?>
				<div class="nom_terceiro">
					<a href="ListaTipoAcesso.php?acao=exc&id=<?php echo $lista['id']; ?>"> <i class="fa fa-remove"></i></a>
				</div>
			<?php } $valida = $lista['nome']; ?>
			
		<?php } ?>
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