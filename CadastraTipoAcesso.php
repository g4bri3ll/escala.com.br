<?php 
session_start();

include_once 'Model/Modelo/TipoAcesso.php';
include_once 'Model/DAO/AcessoPaginasDAO.php';
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
<title>Cadastrado os tipos de acesso</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastro_tipo_acesso' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
	
?>

<div align="center">
	<div style="width: 80%">

<?php
/*Nessa pagina, pegara o ultimo cadastro do usuario
 * Para informar quais as paginas de acesso a ele
 * */
if (!empty($_POST)){
	
	if (empty($_POST['nome'])){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Campos vazio não permitido </font> </div> <?php
	} else {
		
		$nome         = $_POST['nome'];
		//Colocar os dados em minusculo
		$nome         = strtolower($nome);
		$arrayPaginas = $_POST['paginas'];
		
		//Verificar se o usuário já tem essa pagina cadastrada
		$tipDAO = new TipoAcessoDAO();
		$validaPagina = $tipDAO->VerificarNomeCadastro($nome);
		
		if (empty($validaPagina)){
			
			$tipoAcesso = new TipoAcesso();
			$tipoAcesso->nome = $nome;
			$tipoAcesso->status = "ativado";
			
			$tipDAO = new TipoAcessoDAO();
			$result = $tipDAO->cadastrar($tipoAcesso);
			
			if ($result){
				
				//Retorna o ultimo id cadastrado na tabela pelo nome
				$tipDAO = new TipoAcessoDAO();
				$ultimoId = $tipDAO->RetornaUltimoID($nome);
				
				foreach ($ultimoId as $tipDAO => $value){
					$idTipo = $value['id'];
				}
				
				for ($i = 0; $i < count($arrayPaginas); $i++){
					
					$idPaginas = $arrayPaginas[$i];
					
					$tipDAO = new TipoAcessoDAO();
					$res = $tipDAO->CadastraTipoAcessoPaginas($idTipo, $idPaginas);	
				
				}
				
				if ($result){
					?>  <script type="text/javascript"> alert('Tipo de acesso cadastrado com sucesso!'); window.location="index.php";  </script> <?php
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red"> Ouve um erro </font> </div> <?php
				}
				
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Ouve um erro </font> </div> <?php
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Tipo de acesso ja esta cadastrado com esse nome </font> </div> <?php
		}
		
	}//Fecha o if que verificar se esta vazio

}//Fecha o if que verifica se o post foi executado
?>

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastro os tipos de acesso do usuario</b></h1>
</div>

  <form action="" method="post">
	
	<div class="w3-panel w3-blue"><h3>Atencao!</h3></div>
	<input type="text" name="nome" class="w3-input w3-border w3-light-grey" placeholder="Informar o nome do acesso"><br>

	<div class="w3-panel w3-red"><h3>Atencao!</h3>
	<p>Selecionar as paginas</p></div>

	<table class="w3-table" border="1">
	
	<?php
	$a = 0;
	$pagDAO = new AcessoPaginasDAO();
	$arrayPaginas = $pagDAO->listaPaginas();
	for ($i = 0;$i < count($arrayPaginas);$i++){
	?>
		<?php if ($i === $a){ //echo $a . " - " . $i . "<br>"?>
		<tr>
			<?php } ?>
				
				<td>
							
				<input type="checkbox" name="paginas[]" value="<?php echo $arrayPaginas[$i]['id']; ?>"/>
				<?php echo "<-- ".$arrayPaginas[$i]['nome_paginas'] . "  |  "; ?>
				
				</td>
				
			<?php if ($i === $a){ $a = $a + 3; ?>
		</tr>
	<?php } } ?>
	</table>
	<br>
	
	<input class="w3-btn w3-blue"  type="submit" value="  Cadastrar  " /><br><br>

</form>

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