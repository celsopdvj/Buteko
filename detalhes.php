<?php

$table =  pg_connect(
			   'host=	localhost
				port=	5432
				dbname=	buteko
				user=	postgres
				password=123456'
);

$id = $_POST['id'];

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
		pedido.data as data_pedido,
		(SELECT SUM(card.valor)
		FROM "ItemPedido" item
		INNER JOIN "ItemCardapio" card
		ON item.cod_item_cardapio = card.cod_item_cardapio
		WHERE item.cod_pedido = pedido.cod_pedido
		) as valor_pedido,
		cards.nome as itens_cardapio_do_pedido,
		cards.valor as unitario,
		mesa.horario,
		mesa.cod_mesa
		FROM "Pedido" AS pedido
		LEFT JOIN "ItemPedido" itens
		ON itens.cod_pedido = pedido.cod_pedido
		LEFT JOIN "ItemCardapio" cards
		ON itens.cod_item_cardapio = cards.cod_item_cardapio
		LEFT JOIN "MesaPedido" AS mesa
		ON mesa.cod_pedido = pedido.cod_pedido 
		WHERE pedido.cod_pedido = '.$id;

		$result = pg_exec($table,$query);
		$linhas = pg_num_rows($result);
		$dados =  pg_fetch_all($result);
?>

<html>

<?php if($linhas) : ?>

<head>
	
</head>

<body>

<div style="overflow-x: auto;" class="modal-content">
	<table class="table">

		<?php 
		$valor = empty($dados[0]['valor_pedido'])?"R$0,00":$dados[0]['valor_pedido'];
		echo "<tr>";
		echo '<td> <b>Data: </b>' . date_format(new DateTime($dados[0]['data_pedido']),'d/m/Y') . '</td>';
		echo "<td align='center'><b> Atendente: </b>" . $dados[0]['atendente'] . "</td>";
		echo '</tr>';
		echo '<tr>';
		echo "<td align='left'> <b>Mesa: </b>".$dados[0]['cod_mesa'] . '</td>';
		echo "<td align='center'> <b>Horário: </b>". $dados[0]['horario'] . '</td>';
		echo '</tr>';

		foreach($dados as $row) {
					echo '<tr><td>' . $row['itens_cardapio_do_pedido'] . '</td>'.
					"<td align='center'>" . $row['unitario'] . '</td>
					</tr>';
			}
		echo "<tr style='background-color: #e8e8e8;'><td><b>Total</b></td>
			  <td align='center'>" . $valor . "</td></tr>";

			 ?>
		
	</table>

</div>

</body>

<?php else: ?>

<h3>Nenhum dado encontrado</h3>

<?php endif; ?>

</html>