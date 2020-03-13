<?php 
session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/DiaTrabalhaDAO.php';

//Atualizar com o fusio horario do brasil
date_default_timezone_set('America/Bahia');

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
<title>Cadastrar o dia que o usuario trabalhara</title>
</head>
<body>
<script src="boot/js/bootstrap.js" type="text/javascript" ></script>
<script src="boot/js/bootstrap.min.js" type="text/javascript" ></script>
<script src="boot/js/npm.js" type="text/javascript" ></script>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'dia_trabalho' : $as = 35;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<div align="center">
	<div style="width: 80%">

<?php
if (!empty($_POST)){
	
	if (empty($_POST['idUsuario']) || empty($_POST['inicio']) || empty($_POST['fim']) || empty($_POST['idSubSetor'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Preencha todos os campos </font> </div> <?php
	} else {
	
		//Pegar a data de hoje para verificar se a data foi digitada corretamente
		$dataAtual = date('Y-m-d');
		
		//Pegar os dados do post
		$idUsuario  = $_POST['idUsuario'];
		$dataInicio = $_POST['inicio'];
		$dataFinal  = $_POST['fim'];
		$idSubSetor = $_POST['idSubSetor'];
		$status     = "ativado";
		
		if ($dataInicio > $dataAtual){
		
			//Verificar se a data de inicio e maior que a data final
			if ($dataInicio > $dataFinal){
				?> <div class="alert alert-error"> <font size="3px" color="red"> A data 'inicio' nao pode ser maior que a data 'fim' </font> </div> <?php
			} else {

				//Verificar se ja esta cadastrado essa data no usuario
				$diaDAO = new DiaTrabalhaDAO();
				$arrayVerificar = $diaDAO->VerificaCadastro($idUsuario, $dataInicio, $dataFinal, $status);
				
				if (empty($arrayVerificar)){
					
					$diaDAO = new DiaTrabalhaDAO();
					$result = $diaDAO->cadastrar($idUsuario, $idSubSetor, $dataInicio, $dataFinal, $status);
					
					if ($result){
						?> <script type="text/javascript"> alert('Dia de trabalho cadastrado com sucesso!'); window.location="index.php";  </script> <?php
					} else {
						?> <div class="alert alert-error"> <font size="3px" color="red"> Ocorreu um erro: <?php print_r($result); ?> </font> </div> <?php
					}
						
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red"> Existe um cadastro com essa data no usuario </font> </div> <?php
				}
				
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> A data digitada nao pode ser menor que a data de hoje </font> </div> <?php
		} 
		
	}
	
}
?>

<?php 
//Invoca a classe que lista os usuarios 
//$usuDAO = new UsuarioDAO();
//$array = $usuDAO->listaUsuario();
?>

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastra o dia que o usuario trabalhara</b></h1>
</div>

<form action="" method="post" >	

	<div align="center" style="width: 70%;">
	<div class="w3-panel w3-blue"><h3>Informe a nome do usuario</h3></div>
		<select name="idUsuario" id="id_categoria" class="form-control">
			<option value="0" >Escolha o usuario</option>
			<?php
				$idUnidade = $_SESSION['id_unidade'];
				
				//Pegar o id da unidade da session para lista o usuario
				$sql = "SELECT u.id, u.apelido FROM usuario u 
						INNER JOIN unidade_trabalho ut ON(u.id_unidade_trabalho = ut.id)
						WHERE u.status = 'ativado' AND ut.status = 'ativado' AND u.id_unidade_trabalho = '".$idUnidade."'";
				echo $sql;
				$conn = new Conexao();		$conn->openConnect();
				
				$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
				$result = mysqli_query($conn->getCon(), $sql); 
				
				$conn->closeConnect ();
				
				while($row = mysqli_fetch_assoc($result) ) {
					echo '<option value="'.$row['id'].'">'.$row['apelido'].'</option>';
				}
			?>
		</select><br>
	</div>
	
	<div align="center" style="width: 80%;">
	<div class="w3-panel w3-blue"><h3>Informe o setor</h3></div>
	<select name="idSubSetor" id="id_sub_categoria" class="form-control">
		<option value="0" >Escolha o setor</option>
	</select><br>
	</div>
	
	
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
			$.getJSON('CarregaSubSetorPorUsuario.php?search=',{id_categoria: $(this).val(), ajax: 'true'}, function(j){
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
	
<br>
	<div class="control-group">
	<span> Informe a data inicio </span>
	<input type="date" name="inicio">
	
	<span> Informe a data final </span>
	<input type="date" name="fim"><br>
	</div><br><br>
	
<input type="submit" value=" cadastrar " class="w3-btn w3-blue">

</form>

	</div>
</div>

<?php
	} else {
		?>  <script type="text/javascript"> alert('Usuario nao tem privilegio de acessar esta pagina!'); window.location="index.php";  </script> <?php
	}
} else {
	header("Location: index.php");
}
?>

</body>
</html>