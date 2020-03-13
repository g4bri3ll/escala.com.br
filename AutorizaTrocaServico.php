<?php
session_start();

include_once 'Model/DAO/ComfirmarTrocaServicoDAO.php';

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
<title>Autorizar a troca de servico entre os usuarios</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	
	$idUsuario = $_SESSION['id']; 
	$idSetor = $_SESSION['id_setor'];
	$data = date('Y-m-d');
	
	$comDAO = new ComfirmarTrocaServicoDAO();
	$arrayDados = $comDAO->AutorizarTrocaServico($idUsuario, $idSetor, $data);
	
	if (!empty($arrayDados) || $_SESSION['nivel_acesso'] === "1"){
?>

<?php 
if (!empty($_GET['passa']) || !empty($_GET['id'])){
	
	$liberaTroca = 'sim';
	$pendente = 'nao';
	$id = $_GET['id'];
	$idChefe = $idUsuario;
	$status = 'verificado';
	
	$comDAO = new ComfirmarTrocaServicoDAO();
	$result = $comDAO->LiberaTrocaChefe($liberaTroca, $pendente, $id, $idChefe, $status);
	
	if ($result){
		?>  <script type="text/javascript"> alert('Troca de servico comfirmada com sucesso!'); window.location="index.php";  </script> <?php
	} else {
		?> <div class="alert alert-error"> <font size="3px" color="red"> Ouve um erro, entra em contato com a TI para verificar o problema </font> </div> <?php
	}
	
}
?>

<div align="center">
	<div style="width: 80%">
	
<!-- slogan -->
<div class="siteListaFilmeTable">

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Libera a troca de servico entre funcionarios!</b></h1>
</div>
	
	<table class="table table-hover">
	<tr align="center" style="background-color: #ADD8E6;">
	<td><font color="black"> Quem tira      </font></td>
	<td><font color="black"> Data tira      </font></td>
	<td><font color="black"> Quem solicitar </font></td>
	<td><font color="black"> Data solicitar </font></td>
	<td><font color="black"> Liberar        </font></td>
	</tr>
	
<?php
//traz o array, mais nessa data ele modificar se for 0000-00-00 para nao tira servico  
foreach ( $arrayDados as $comDAO => $lista ){
	$dataSolicitante = '';
	if ($lista['data_solicitante'] === '0000-00-00'){
		$dataSolicitante = 'Nao tira servico';
	} else { $dataSolicitante = $lista['data_solicitante']; }
?>
	
	<tr align="center">
	<td><label class=""> <?php echo $lista['usu_quem_tira']; ?> </label></td>
	<td><label class=""> <?php echo $lista['data_tira']; ?> </label></td>
	<td><label class=""> <?php echo $lista['usu_quem_solicita']; ?> </label></td>
	<td><label class=""> <?php echo $dataSolicitante; ?> </label></td>
	<td>
	<a href="AutorizaTrocaServico.php?acao=libera&id=<?php echo $lista['id']; ?>"> <i class="fa fa-check"></i> </a>
	</td>
	</tr>

<?php } ?>

	</table>

</div>
<!-- fim slogan -->

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