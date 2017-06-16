<?php 

$table =  pg_connect(
			   'host=	localhost
				port=	5432
				dbname=	buteko
				user=	postgres
				password=123456'
);

$query = '
		SELECT * 
		FROM "ItemCardapio"
';

$selectGerente = '
		SELECT *
		FROM "Gerente"
';

$selectGarcom = '
		SELECT *
		FROM "Garcom"
';

$selectCliente = '
		SELECT *
		FROM "Cliente"
';

$result = pg_exec($table,$query);
$resultGerente = pg_exec($table,$selectGerente);
$resultGarcom = pg_exec($table,$selectGarcom);
$resultCliente = pg_exec($table,$selectCliente);
$dados =  pg_fetch_all($result);
$gerente = pg_fetch_all($resultGerente);
$garcom = pg_fetch_all($resultGarcom);
$cliente = pg_fetch_all($resultCliente);

?>

<html>

<body>

<table id="table-novo-pedido">
	<tr>
		<td align="right">
			<label for="opcoes-item">Adicionar Item:</label>
			<select class="selectpicker" style="height: 32px;" id="opcoes-item">
			<?php 
			foreach ($dados as $row) {
				echo "<option class='opcoes' value='". $row['cod_item_cardapio'] . "'>" . $row['nome'] . "</option>";
			}
			?>
			</select>
		</td>
		<td style="padding-left: 4px;" align="left" >
			<button type="button" class="btn btn-default" aria-label="Left Align" title="Novo Pedido" id="add-item">
				<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
			</button>
		</td>

		<td>
			<label for="atendente">Atendente:</label>
			<select class="selectpicker" style="height: 32px;" id="atendente">
				  <optgroup label="Garçom">
				    <?php 
					foreach ($garcom as $row) {
						echo "<option value='ga_". $row['cod_garcom'] . "'>" . $row['nome'] . "</option>";
					}
					?>
				  </optgroup>
				  <optgroup label="Gerente">
				    <?php 
					foreach ($gerente as $row) {
						echo "<option value='ge_". $row['cod_gerente'] . "'>" . $row['nome'] . "</option>";
					}
					?>
				  </optgroup>
				  <optgroup label="Cliente">
				    <?php 
					foreach ($cliente as $row) {
						echo "<option value='cl_". $row['cod_cliente'] . "'>" . $row['nome'] . "</option>";
					}
					?>
				  </optgroup>
			</select>

		</td>
	</tr>
</table>	

</body>
</html>