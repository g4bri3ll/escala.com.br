<?php
session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/ComfirmarTrocaServicoDAO.php';

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
<title>Comfirmar a troca de servico entre funcionarios</title>
</head>
<body>

<?php 
if (!empty($_POST)){
	
	if (empty($_POST['motivo']) || empty($_POST['valor'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Preencha todos os campos </font> </div> <?php
	} else {
		$motivo = $_POST['motivo'];
		$valor = $_POST['valor'];
		$id = $_GET['id'];
		
		if ($valor === 'sim'){
			
			$comfirmar = "comfirmada";
			
			$comDAO = new ComfirmarTrocaServicoDAO();
			$res = $comDAO->ComfirmarTrocaQuemTira($id, $comfirmar, $motivo);
			
			if ($res){
				?> <script type="text/javascript"> alert('Troca de servico comfirmada, informe o chefe do setor para esta liberando a troca do servico!'); window.location="index.php";  </script> <?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra a troca de servico </font> </div> <?php
			}
			
		} else {
			
			$comfirmarChefe = "cancelada";
			$comfirmarUsu = "cancelada";
			$status = "cancela_usuario";
			
			$comDAO = new ComfirmarTrocaServicoDAO();
			$res = $comDAO->CancelarTrocaUsuario($comfirmarUsu, $comfirmarChefe, $motivo, $status, $id);
			
			if ($res){
				?>  <script type="text/javascript"> alert('Troca de serviço cancelada!'); window.location="index.php";  </script> <?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cancelar a troca do serviço </font> </div> <?php
			}
			
		}
		
	}
	
}
?>


<?php 
$idSession = $_SESSION['id'];
	
$usuDAO = new UsuarioDAO();
$arrayServico = $usuDAO->ListaTrocaServicoUsuario($idSession);
	
if (!empty($arrayServico)){
	
?>

<div align="center">
	<div style="width: 80%">
		<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
	
		<div class="w3-panel w3-blue">
		  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  		  <b>Comfirmar a troca do servico!</b></h1>
		</div>

<?php 	for ($i = 0; $i < count($arrayServico); $i++){ ?>
  	
<form class="w3-container" method="post" action="ComfirmarTrocaServico.php?id=<?php echo $arrayServico[$i]['id']; ?>">

  	<div class="w3-panel w3-red"><h3>O(a) funcionario <?php echo $arrayServico[$i]['nome_usuario']; ?> que fazer uma troca de servico</h3>
	<p>Para o dia <?php echo date('d-m-Y', strtotime($arrayServico[$i]['data_tira'])); ?></p></div>
	
	<label>Desejar realmente tirar o servico? </label>
	<input type="radio" name="valor" value="sim"> Sim 
	<input type="radio" name="valor" value="nao"> Nao<br><br>

	<label class="">Qual o motivo da troca</label>
	<textarea name="motivo" class="w3-input w3-border w3-light-grey"></textarea><br>
	
	<button class="w3-btn w3-blue">Comfirmar</button>
	
	<hr style="border: none;   border-top: 1px solid #FF0000;">
	
</form>

<?php } ?>

	</div>
</div>
	
<?php } else {	header("Location: index.php"); } ?>

</body>
</html>