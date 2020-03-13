<?php 
session_start();

include_once 'Model/DAO/UsuarioDAO.php';
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
<title>Mudar a unidade de servico do usuario</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'mudar_unidade_trabalho_usu' : $as = 15;   break ;
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
	
	if (isset($_POST['idUnidade']) || isset($_POST['idUsuario']) || isset($_POST['idSetor'])){
		?><div class="w3-panel w3-red"><h3>Cuidado!</h3> <p>Campos vazio não permitido</p></div><?php
	} else {
	
		$idUnidade = $_POST['idUnidade'];
		$idUsuario = $_POST['idUsuario'];
		$idSetor   = $_POST['idSetor'];
				
		//Verificar se o usuário já esta cadastrado nesta unidade
		$usuDAO = new UsuarioDAO();
		$validaPagina = $usuDAO->VerificarUnidadeCadParaAlterar($idUnidade, $idUsuario, $idSetor);
				
		if (!empty($validaPagina)){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Esse usuario ja esta cadastrado nesta unidade </font> </div> <?php
		} else {
			
			
			
			
			$usuDAO = new UsuarioDAO();
			$result = $usuDAO->alterarUnidadeTrabalho($idUsuario, $idUnidade, $idSetor);
			
			if (empty($result)){
				?>  <script type="text/javascript"> alert('Unidade de trabalho alterado com sucesso!'); window.location="index.php"; </script> <?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao alterar a unidade, causa possivel <?php echo $result; ?>  </font> </div> <?php
			}
			
		}
		
	}
	
}
?>

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Informe a nova unidade de trabalho do usuario</b></h1>
</div>

  <form action="" method="post">
	
	<p><label class="w3-text-brown"><b>Selecionar um usuario</b></label>
	<select name="idUsuario" class="form-control">
	<?php 
		$usuDAO = new UsuarioDAO();
		$array = $usuDAO->listaNomesUsuario();
		foreach ($array as $usuDAO => $lista){
		?>
			<option value="<?php echo $lista['id']?>"><?php echo $lista['apelido']; ?></option>
		<?php } ?>
	</select></p>
	<p><label class="w3-text-brown"><b>Unidade de trabalho</b></label>
	   <select name="idUnidade" id="id_categoria" class="form-control">
		<option value="">Escolha a Categoria</option>
		<?php
		$sql = "SELECT * FROM unidade_trabalho";
		$conn = new Conexao();		$conn->openConnect();
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$result = mysqli_query($conn->getCon(), $sql); 
		$conn->closeConnect ();			
		while($row = mysqli_fetch_assoc($result) ) {
			echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
		}
		?>
	</select></p>
	
	<p><label class="w3-text-brown"><b>Informe o setor</b></label>
	   <select name="idSetor" id="id_sub_categoria" class="form-control">
		<option value="">Informe o setor</option>
	</select></p><br>
	
	<input class="w3-btn w3-blue"  type="submit" value="  Alterar Unidade  " /><br><br>

</form>

	</div>
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
			$.getJSON('carrega_setor.php?search=',{id_categoria: $(this).val(), ajax: 'true'}, function(j){
				var options = '<option value="#">Escolha o setor</option>';	
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