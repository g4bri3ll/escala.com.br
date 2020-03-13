<?php
session_start();

include_once 'Model/DAO/RecardoDAO.php';

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
<title>Lista recardo deixado por outro usuario</title>
<style> 
textarea {
    width: 100%;
    height: 150px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    font-size: 16px;
    resize: none;
}
</style>
</head>
<body>

<?php 
if (!empty($_GET['acao'])){
	$idRecardo = $_GET['acao'];
	$status = 'visto';
	
	$recDAO = new RecardoDAO();
	$result = $recDAO->alteraStatusLido($status, $idRecardo);
	
	if($result){
		header('Location: index.php');
	} else {
		?> <div class="alert alert-error"> <font size="3px" color="red"> Erro, informar a TI sobre o erro! </font> </div> <?php
	}
	
}
?>

<?php 
if (!empty($_SESSION)){
	 if (!empty($_GET['recardo'])){
		
		$idSession = $_GET['recardo'];

		$recDAO = new RecardoDAO();
		$arrayListaUsu = $recDAO->listaRercadoPeloUsuario($idSession);
		
		$comentario = 0;
		$idUsuQueEnvio = 0;
		$idRec = 0;
		$data = 0;
		foreach ($arrayListaUsu as $recDAO => $valor){
			$comentario    = $valor['comentario'];
			$nomeQuemEnvio = $valor['apelido'];
			$idRec         = $valor['id_recardo'];
			$data          = $valor['data'];
		}
?>

<div align="center">
	<div style="width: 80%">

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h2 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Lista o recardo deixado pelo <?php echo $nomeQuemEnvio; ?>!</b></h2>
</div>

<form action="ListaRecardoUsuario.php?acao=<?php echo $idRec; ?>" method="post">

<h3>Recardo enviado dia: <?php echo $data; ?></h3>

<textarea rows="" cols="" disabled="disabled">
<?php echo $valor['comentario']; ?>
</textarea>

<br><br><input type="submit" value="Ja li" class="w3-btn w3-blue" />

</form>

	</div>
</div>

<?php
	} else {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
}
?>

</body>
</html>