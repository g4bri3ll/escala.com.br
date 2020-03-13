<?php
session_start();

include_once 'Model/DAO/TrocaServicoDAO.php';
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
<title>Liberar a troca de servico</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'libera_troca_servico' : $lts = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<div class="table-responsive" align="center">

<?php 
if (!empty($_GET['id'])){
	
	//Usuario que vai liberar a troca "chefe"
	$idChefe = $_SESSION['id'];
	//Id da troca do servico
	$id = $_GET['id'];
	
	if (!empty($_GET['acao'])){
		
		//Se for para cancelar ele pegar essa acao aqui
		$acao = $_GET['acao'];
		
		$comfirmarcao = "cancelada";
		$comfirmarQuemTira = "cancelada";
		$status = "cancela_chefe";
		
		$comDAO = new ComfirmarTrocaServicoDAO();
		$result = $comDAO->CancelarTrocaChefe($comfirmarcao, $id, $idChefe, $status, $comfirmarQuemTira);
				
		if ($result){
			?> <script type="text/javascript"> alert('troca de servico cancelado com sucesso'); window.location="LiberaTrocaServico.php"</script> <?php
	    } else {
	  	  ?> <script type="text/javascript"> alert('Erro ao cancelar a troca do servico!'); window.location="LiberaTrocaServico.php"</script> <?php
	    }
	    
	} else {
		
		//Verificar se o outro funcionar ja concordou com a troca
		$comDAO = new ComfirmarTrocaServicoDAO();
		$concorda = $comDAO->ConcordaTrocaServico($id);
		
		foreach ($concorda as $comDAO => $value){
			$nome = $value['nome_usuario'];
		}
		
		if(empty($nome)){
		
			$liberaTroca = "sim";
			$status = "verificado";
			$pendente = "nao";
			
			$comDAO = new ComfirmarTrocaServicoDAO();
			$result = $comDAO->LiberaTrocaChefe($liberaTroca, $pendente, $id, $idChefe, $status);
			
			if ($result){
				?> <script type="text/javascript"> alert('Troca de servico comfirmarda com sucesso'); window.location="LiberaTrocaServico.php"</script> <?php
		    } else {
		    	?> <script type="text/javascript"> alert('Erro ao libera a troca do servico!'); window.location="LiberaTrocaServico.php"</script> <?php
		    }
		    
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> E necessarioa que o(a) funcionaria <?php echo $nome; ?>, comfirmar primeiro! </font> </div> <?php
		}
	    
	}
	
}

?>

<div align="center">
	<div style="width: 80%">
	
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
	
<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Lista todas as troca de servico pedentes!</b></h1>
</div>

	</div>
</div>
	
	<table class="table table-hover">
	<tr align="center" class="info">
	<td><label class=""> Quem Solicitou </label></td>
	<td><label class=""> Data solicitou tira</label></td>
	<td><label class=""> Quem tira </label></td>
	<td><label class=""> Data tira </label></td>
	<td><label class=""> Motivo da troca </label></td>
	<td><label class=""> Acao </label></td>
	</tr>
	
<?php 
$comDAO = new ComfirmarTrocaServicoDAO();
$array = $comDAO->ListaTrocasPedentes();
foreach ( $array as $usu => $list ) { 
?>
	
	<tr align="center">
	<td><label class=""> <?php echo $list['nome_solicita']; ?> </label></td>
	<td><label class=""> <?php echo $list['data_solicitante']; ?> </label></td>
	<td><label class=""> <?php echo $list['nome_tira']; ?> </label></td>
	<td><label class=""> <?php echo $list['data_tira'] ; ?> </label> </td>
	<td><label class=""> <?php echo $list['motivo_troca']; ?> </label> </td>
	<td>
	<a href="LiberaTrocaServico.php?id=<?php echo $list['id']; ?>"> <i class="fa fa-check"></i> </a> |
	<a href="LiberaTrocaServico.php?acao=cancelar&id=<?php echo $list['id']; ?>"> <i class="fa fa-close"></i> </a>
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