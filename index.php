<!DOCTYPE HTML>

<html>

<style type="text/css">
#listagem tr:hover {
	background-color: #d6d4d4;
}
</style>

<head>
	<title>Listagem de Pedidos</title>
	<div  align="center" style="padding-bottom: 25px;">
		<h1 class="display-4">Pedido</h1>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

</div>

<div class="form-group" align="center" style="overflow-x: auto;" >
<table width="80%">
	<tr>
		<td width="82%">
			<button type="button" class="btn btn-default" aria-label="Left Align" title="Novo Pedido" id="newpedido">
				<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
			</button>
			<button type="button" class="btn btn-default" aria-label="Left Align" title="Novo Prato	">
				<span class="glyphicon glyphicon-glass" aria-hidden="true"></span>
			</button>
			<button type="button" class="btn btn-default" aria-label="Left Align" title="Novo Cadastro">
				<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
			</button>
		</td>
		<td align="right">
  			<label for="filtrar">Filtrar:</label>
  		</td>
  		<td align="right">
  			<select class="form-control" id="filtrar" style="width: 140px;">
			    <option value="">Todos</option>
			    <option value="Aguardando">Aguardando</option>
			    <option value="Atendido">Atendido</option>
			    <option value="Cancelado">Cancelado</option>
  			</select>
  		</td>
  </tr>
</table>
</div>

<div id="detalhes" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detalhes do Pedido</h4>
      </div>
      <div class="modal-body">
        <div id="conteudo-detalhes"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>

  </div>
</div>

<div id="novo-pedido" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Novo Pedido</h4>
      </div>
      <div class="modal-body">
        <table id="conteudo-novo-pedido" class="table table-striped" width="100%"></table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>

  </div>
</div>

<div id="dados" align="center">

<script>
        function pedidos(filtro="")
        {
            var page = "pedidos.php";
            $.ajax
                    ({
                        type: 'POST',
                        dataType: 'html',
                        url: page,
                        data: {filtro: filtro},
                        beforeSend: function () {
                            $("#dados").html("Carregando...");
                        },
                        success: function (msg)
                        {
                            $("#dados").html(msg);
                        },
                        complete: function()
                        {
                        	$("#listagem").delegate("tr.rows", "click", function(){
						        detalhar(this.id)
						    });
                        }
                    });
        }
        
        $(window).on("load",function () {
            pedidos("")
        });

        $('#filtrar').change(function () {
                pedidos($("#filtrar").val())
            });

        $('#newpedido').on("click",function(){
        	carregaItens();
        });

        function detalhar(id)
        {
        	var page = "detalhes.php";
            $.ajax
                    ({
                        type: 'POST',
                        dataType: 'html',
                        url: page,
                        data: {id: id},
                        success: function (msg)
                        {
                            $("#conteudo-detalhes").html(msg);
                            $("#detalhes").modal("toggle");
                        }
                    });
        }

        function carregaItens()
        {
        	var page = "novopedido.php";
            $.ajax
                    ({
                        type: 'POST',
                        dataType: 'html',
                        url: page,
                        success: function (msg)
                        {
                            $("#conteudo-novo-pedido").html(msg);
                            $("#novo-pedido").modal("toggle");
                        },
                        complete: function()
                        {
                        	$("#add-item").on("click", function(){
						        $("#conteudo-novo-pedido > tbody:last-child").append(
						        	"<tr id='" + $("#opcoes-item").val() + "'><td colspan='3' style='text-align: center;'>" 
						        	+ $("#opcoes-item :selected").text() + "</td></tr>"
						        )
						    });
                        }
                    });
        }

</script>

</body>

</html>