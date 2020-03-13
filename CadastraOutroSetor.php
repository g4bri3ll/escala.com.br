<?php 
session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/SetorDAO.php';
include_once 'Controller/ControllerCadDataUsuario.php';

$idUnidadeSession = $_SESSION['id_unidade'];

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
<title>Cadastrada outro setor no usuario</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastrar_outro_setor' : $as = 15;   break ;
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
	
	if (empty($_POST['idSetor']) || empty($_POST['idSubSetor']) || 
	empty($_POST['id_usuario']) || empty($_POST['data'])){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Preenchar todos os campos </font> </div> <?php
	} else {
		
		$idSubSetor = $_POST['idSubSetor'];
		$idSetor = $_POST['idSetor'];
		$idUsuario = $_POST['id_usuario'];
		$principal = $_POST['principal'];
		$data = $_POST['data'];
		
		//Verificar se o usuário já tem acesso ao sub setor
		$setDAO = new SetorDAO();
		$validaPagina = $setDAO->VerificarSetorCadNoUsuario($idSubSetor, $idUsuario);
		
		//Pegar a data que e hoje com a data de um dia a menos e verificar se a data e igual a estas datas
		if ($data === date('Y-m-d') || $data === date('Y-m-d', strtotime("-1 days",strtotime(date('Y-m-d'))))){

			if (empty($validaPagina)){
				
				/* Nessa parte ele pegar as data e o setor do usuario para cadastrar
				 * inicio
				 * Pegar a data de quatro dias a tras para cadastrar 
				 * */
				//Cadastrar as data no usuario e no setor
				$cadData = new ControllerCadDataUsuario();
				$resultData = $cadData->CadastrarDatasParImpar($data, $idUsuario, $idSubSetor);
				//Final
				
				$status = "ativado";
				//Cadastrar os id na tabela setor_usuario
				$setDAO = new SetorDAO();
				$result = $setDAO->cadastrarOutroSetor($idSubSetor, $idUsuario, $principal, $status);
				
				if ($result){
					?>  <script type="text/javascript"> alert('Sub setor inserido com sucesso!'); window.location="index.php";  </script> <?php
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red"> Ouve um erro </font> </div> <?php
				}
				
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Sub Setor ja cadastrado para o usuario selecionado!</font> </div> <?php
			}
			
		} else{
				?> <div class="alert alert-error"> <font size="3px" color="red"> Data nao pode ser maior que a data de hoje, ou menor que dois dias, tem que cadastrar com um dia anterior!</font> </div> <?php
		}
		
	}//Fecha o if que verificar se esta vazio

}//Fecha o if que verifica se o post foi executado
?>

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastra um sub setor para o usuario</b></h1>
</div>

<?php

$idUnidade = $_SESSION['id_unidade'];

//$setDAO = new SetorDAO();
//$arraySetor = $setDAO->listaSetorPeloUsuario($idUnidade);

$usuDAO = new UsuarioDAO();
$arrayUsu = $usuDAO->ListaParaInserirOutroSetor($idUnidade);
?>

  <form action="" method="post">
	
	<table border="" width="100%">
		<tr>
			<td align="center">
	
				<div class="w3-panel w3-blue"><h3>Informe o setor</h3></div>
					<select name="idSetor" id="id_categoria" class="form-control">
						<option value="0" >Escolha o setor</option>
						<?php
							$sql = "SELECT * FROM setor WHERE status = 'ativado' AND id_unidade_trabalho = '".$idUnidadeSession."'";
							
							$conn = new Conexao();		$conn->openConnect();
							
							$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
							$result = mysqli_query($conn->getCon(), $sql); 
							
							$conn->closeConnect ();
							
							while($row = mysqli_fetch_assoc($result) ) {
								echo '<option value="'.$row['id'].'">'.$row['nome_setor'].'</option>';
							}
						?>
					</select><br>
	
			</td>
			
			<td align="center"> == </td>
			
			<td align="center">
	
				<div class="w3-panel w3-blue"><h3>Informe o sub setor</h3></div>
				<select name="idSubSetor" id="id_sub_categoria" class="form-control">
					<option value="0" >Escolha o sub setor</option>
				</select><br>
	
			</td>
		</tr>
	</table>
	
	
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("jquery", "1.4.2");
</script>
		
<script type="text/javascript">
$(function(){
	$('#id_categoria').change(function(){
		if( $(this).val() ) {
			$('#id_sub_categoria').hide();
			$('.carregando').show();
			$.getJSON('carrega_sub_setor.php?search=',{id_categoria: $(this).val(), ajax: 'true'}, function(j){
				var options = '<option value="#">Escolha Subcategoria</option>';	
				for (var i = 0; i < j.length; i++) {
					options += '<option value="' + j[i].id + '">' + j[i].nome_sub_categoria + '</option>';
				}	
				$('#id_sub_categoria').html(options).show();
				$('.carregando').hide();
			});
		} else {
			$('#id_sub_categoria').html('<option value="">– Escolha o setor –</option>');
		}
	});
});
</script>

	
	<div class="w3-panel w3-red"><h3>Informe o usuario!</h3></div>
	<select class="form-control" name="id_usuario">
		<option value="">Informe o usuario</option>
		<?php foreach ($arrayUsu as $usuDAO => $lista){	?>
		<option value="<?php echo $lista['id']; ?>"><?php echo $lista['apelido']; ?></option>
		<?php } ?>
	</select><br>
	
	<div class="w3-panel w3-blue"><h3>Dia que comeca a trabalhar!</h3></div>
	<input type="date" name="data"><br><br><br>

	<div align="left">
	<span>Este e o sub setor principal</span>
	<input type="radio" value="1" name="principal"> Sim - 
	<input type="radio" value="0" name="principal"> Nao 
	</div><br><br>
	
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