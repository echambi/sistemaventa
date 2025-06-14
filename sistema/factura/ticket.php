<?php
	$subtotal 	= 0;
	$iva 	 	= 0;
	$impuesto 	= 0;
	$tl_sniva   = 0;
	$total 		= 0; 
 //print_r($configuracion); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Venta</title>
    <link rel="stylesheet" href="styleticket.css" />
	<style>
@import url('fonts/BrixSansRegular.css');
@import url('fonts/BrixSansBlack.css');
*{
margin: 0;
padding: 0;
box-sizing: border-box;
}
p, label, span, table{
font-family: 'BrixSansRegular';
font-size: 9pt;
}
.h2{
font-family: 'BrixSansBlack';
font-size: 12pt;
padding: 0px;
}
img{
width: 200px;
}
.h3{
font-family: 'BrixSansBlack';
font-size: 10pt;
display: block;
text-align: center;
padding: 0px;
margin-bottom: 2px;
}
#page_pdf{
width: 204pt;
}

#factura_head, #factura_cliente{
width: 100%;
border-collapse: collapse;
}
.logo_factura{
width: 100%;
}
.logo_factura img{
padding-left: 25%;
width: 100pt;
}
.info_empresa{
padding-top: 0;
width: 100%;
text-align: center;
}
.info_factura{
padding-top: 0;
width: 100%;
text-align: center;
}
.info_cliente{
padding-top: 0;
width: 100%;
text-align: center;
}
.datos_cliente{
padding-top: 0;
width: 100%;
text-align: center;
}
.datos_cliente{
padding-top: 0;
width: 100%;
text-align: center;
}
.textright{
text-align: right;
}
.textleft{
text-align: left;
}
.textcenter{
text-align: center;
}
#factura_detalle{
width: 100%;
padding: 5px;
border-collapse: collapse;
}
#factura_detalle thead th{
width: 100%;
}
#detalle_productos tr:nth-child(even) {
background: #ededed;
}
#detalle_totales span{
font-family: 'BrixSansBlack';
}
.nota{
width: 100%;
font-size: 8pt;
margin-top: 10px;
text-align: center;
}
.label_gracias{
width: 100%;
font-family: verdana;
font-weight: bold;
font-style: italic;
text-align: center;
margin-top: 10px;
}
.anulada{
position: absolute;
left: 50%;
top: 50%;
transform: translateX(-50%) translateY(-50%);
}
</style>
</head>
<body>
<?php echo $anulada; ?>
<div id="page_pdf">
	<br>
	<br>
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<div>
					<img src="./img/<?php echo $configuracion['foto']; ?>">
<?php
					// A few settings
$img_file = "./img/".$configuracion['foto']."";

// Read image path, convert to base64 encoding
$imgData = base64_encode(file_get_contents($img_file));

// Format the image SRC:  data:{mime};base64,{data};
$src = 'data: '.mime_content_type($img_file).';base64,'.$imgData;

// Echo out a sample image
echo '<img src="'.$src.'">';
?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="info_empresa">

				<?php					

					if($result_config > 0){
						$iva = $configuracion['iva'];
						$moned = $configuracion['moneda'];							
				 ?>
				<div>
					<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
					<p><?php echo $configuracion['razon_social']; ?></p>
					<p><?php echo $configuracion['direccion']; ?></p>
					<p>RUC: <?php echo $configuracion['nit'].' &nbsp;&nbsp;Cel: '.$configuracion['telefono']; ?></p>					
				</div>
				<?php
				if ($venta['status'] == 1) {
					$tipo_pago = 'Contado';
				}elseif($venta['status'] == 3){
					$tipo_pago = 'Crédito';
				}else{
					$tipo_pago = 'Anulado';
				}
					}
	
						if ($tipo_pago == 'Crédito') {
							date_default_timezone_set("America/Managua");
							$fecha = date('d-m-Y',strtotime($venta["fecha"]));
							$fecha_a_vencer = date('d-m-Y',strtotime($fecha. '+ 30 days'));
							$vence = '<p>&nbsp;&nbsp;Vencimiento: '.$fecha_a_vencer.'</p>';
						}else{
							$vence = '';
						}
					
				 ?>
			</td>
			</tr>
			<tr>
			<td class="">

				<div class="round">
					<p>&nbsp;&nbsp;Tipo de venta: <?php echo $tipo_pago; ?></p>
					<strong>&nbsp;&nbsp;No. Venta: <?php echo str_pad($venta['noventa'],11,'0', STR_PAD_LEFT); ?>&nbsp;&nbsp;&nbsp;&nbsp;Fecha: <?php echo $venta['fechaF']; ?></strong>
					<p>&nbsp;&nbsp;Hora: <?php echo $venta['horaF']; ?></p>	
					<p>&nbsp;&nbsp;Vendedor: <?php echo $venta['vendedor'];?></p>
					<strong>&nbsp;&nbsp;Cliente: <?php echo $venta['nombre']; ?></strong>
					<p>&nbsp;&nbsp;Nit: <?php echo $venta['nit']; ?></p>
					<?php echo $vence;?>

				</div>
			</td>
		</tr>
	</table>
	<table id="factura_detalle">
			<thead>
				<tr>
					<th colspan="3" class="textleft"> ------------------------------------------------------------------ Descripción</th>
				</tr>
				<tr><th width="">Código</th>
					<th width="">Cantidad</th>
					<th class="" width="">Precio</th>
					<th class="" width="">Total</th>
				</tr>
				<tr>
					<th colspan="3" class="textleft"> ------------------------------------------------------------------ </th>
				</tr>
			</thead>
			<tbody id="detalle_productos">
			<?php

				if($result_detalle > 0){

					while ($row = mysqli_fetch_assoc($query_productos)){
						$precio_venta = number_format($row['precio_venta'],2);
						$precio_total = number_format($row['precio_total'],2);
			 ?>
				<tr>
					<td colspan="3"width=""><?php echo $row['descripcion']; ?></td>
				</tr>
				<tr>
					<td width=""><?php echo $row['codigo']; ?></td>
					<td width=""><?php echo $row['cantidad']; ?></td>
					<td width=""><?php echo $precio_venta; ?></td>
					<td class=""><?php echo $precio_total; ?></td>
				</tr>
			<?php
						$precio_total = $row['precio_total'];
						$subtotal = round($subtotal + $precio_total, 2);
					}
				}

				$impuesto 	= round($subtotal * ($iva / 100), 2);
				$tl_sniva 	= round($subtotal - $impuesto,2 );
				$total 		= $tl_sniva + $impuesto;
				$tl_sniva1 = number_format($subtotal - $impuesto,2);
				$impuesto1 = number_format($subtotal * ($iva / 100), 2);
				$descuento = number_format($venta['descuento'],2);
			?>
			</tbody>
			<tfoot id="detalle_totales">
				<tr>
					<td colspan="3"><p>---------------------------------------------------------</p></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="" class=""><span>Sub total </span></td>					
					<td class=""><span> <?php echo $moned.' '.number_format($total,2); ?></span></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="" class=""><span>Descuento </span></td>					
					<td class=""><span> <?php echo $moned.' '.$descuento; ?></span></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="" class=""><span>TOTAL </span></td>					
					<td class=""><span> <?php echo $moned.' '.number_format($total-$descuento,2); ?></span></td>
				</tr>
		</tfoot>
	</table>
	<br>
	<br>
	<br>
	<div>
		<!--<p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con nombre, teléfono y Email</p>-->
		<h4 class="label_gracias">¡Gracias por su compra!</h4>
		<p class="label_gracias">Revise su producto, no aceptamos devoluciones.</p>
	</div>

</div>

</body>
</html>