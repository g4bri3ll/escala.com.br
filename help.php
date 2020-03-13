<?php
?>

<!doctype html>
<html lang="pt-BR">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script	src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="CSS/style.css" />
<title>Help</title>
</head>
<body>

	<div class="container">

<div class="w3-panel w3-blue">
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Ajudar aos usuarios</b></h1>
</div>
		<p>
			<strong>Aqui:</strong> E um guia de rápida navegação pelo site.
		</p>
		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion"
							href="#collapse1">Index -> Cadastros -> Cadastro de Tipo de Acesso</a>
					</h4>
				</div>
				<div id="collapse1" class="panel-collapse collapse in">
					<div class="panel-body">Nesta parte coloca-se o nome da permissão e os acesso
					das paginas do sistema, dando-lhe permissão para acessar, essa parte e parecido com 
					o esquema de "Papel" da MV.</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion"
							href="#collapse2">Index -> Cadastros -> Cadastro de Setor</a>
					</h4>
				</div>
				<div id="collapse2" class="panel-collapse collapse">
					<div class="panel-body">Faz o cadastrado do setor que o usuario ira trabalhar.</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion"
							href="#collapse3">Index -> Cadastros -> Cadastro de Unidade Hospitalar</a>
					</h4>
				</div>
				<div id="collapse3" class="panel-collapse collapse">
					<div class="panel-body">Faz o cadastrado das unidade hospitalar, onde o usuario trabalhar.</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion"
							href="#collapse4">Index -> Cadastros -> Cadastro de Usuario</a>
					</h4>
				</div>
				<div id="collapse4" class="panel-collapse collapse">
					<div class="panel-body">Faz o cadastro dos usarios no sistemas.</div>
				</div>
			</div>
		</div>

		<a href="index.php" class="btn btn-info">Voltar</a>

	</div>

</body>
</html>

