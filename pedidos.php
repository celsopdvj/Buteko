<?php

$table =  pg_connect(
			   'host=	localhost
				port=	5432
				dbname=	buteko
				user=	postgres
				password=123456'
);	

$status = $_POST['filtro'];

$query = '
		SELECT
		pedido.cod_pedido,
		CASE
		WHEN pedido.cod_gerente IS NOT NULL
		THEN (SELECT gerente.nome FROM "Gerente" gerente where gerente.cod_gerente = pedido.cod_gerente)
		WHEN pedido.cod_garcom IS NOT NULL
			THEN (SELECT garcom.nome FROM "Garcom" garcom where garcom.cod_garcom = pedido.cod_garcom)
		WHEN pedido.cod_cliente IS NOT NULL
			THEN (SELECT cliente.nome FROM "Cliente" cliente where cliente.cod_cliente = pedido.cod_cliente)
		END AS atendente,
		pedido.data as data,
		(SELECT SUM(card.valor)
		FROM "ItemPedido" item
		INNER JOIN "ItemCardapio" card
		ON item.cod_item_cardapio = card.cod_item_cardapio
		WHERE item.cod_pedido = pedido.cod_pedido
		) as valor,
		pedido.status,
		pedido.cod_pedido as codigo,
		mesa.horario
		FROM "Pedido" AS pedido
		LEFT JOIN "MesaPedido" AS mesa
		ON mesa.cod_pedido = pedido.cod_pedido 
		';

		if(!empty($status))
			$query.= "WHERE pedido.status = '$status'";

		$query.='ORDER BY pedido.data DESC,mesa.horario DESC';

		$result = pg_exec($table,$query);
		$linhas = pg_num_rows($result);
		$dados =  pg_fetch_all($result);
?>

<html>

<?php if($linhas) : ?>

<head>
	
</head>

<body>

<div style="overflow-x: auto;" class="container">
	<table id="listagem" align="center" width="80%" class="table table-striped" style="text-align: center;">

		<tr>
			<th width="15%" style="text-align: center;">
				Data
			</th>
			<th width="35%" style="text-align: center;">
				Atendente
			</th>
			<th width="40%" style="text-align: center;">
				Status
			</th>
			<th width="10%" style="text-align: center;">
				Valor
			</th>
		</tr>

		<?php foreach($dados as $row) {

				$valor = empty($row['valor'])?"R$0,00":$row['valor'];
				$data = preg_split('[-]', $row['data']);
				$data = $data[2].'/'.$data[1].'/'.$data[0];

				echo "<tr class='rows' id='" . $row['codigo'] . "'>";
				
					echo '<td>' . $data . ' - '. $row['horario'] .'</td>';
					echo '<td>' . $row['atendente'] . '</td>';
					echo '<td>' . $row['status'] . '</td>';
					echo '<td>' . $valor . '</td>';

				echo '</tr>';

			} ?>
		
	</table>

</div>

</body>

<?php else: ?>

<h3>Nenhum dado encontrado</h3>

<?php endif; ?>

</html>