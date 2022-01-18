
<?php
$medidaTicket = 225;

?>
<!DOCTYPE html>
<html>

<head>

<style>
        * {
            font-size: 12px;
            font-family: 'DejaVu Sans', serif;
        }

        h1 {
            font-size: 18px;
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
            margin: 0 auto;
        }

        td.precio {
            text-align: right;
            font-size: 11px;
        }

        td.cantidad {
            font-size: 11px;
        }

        td.producto {
            text-align: center;
        }

        th {
            text-align: center;
        }


        .centrado {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: <?php echo $medidaTicket ?>px;
            max-width: <?php echo $medidaTicket ?>px;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .ticket {
            margin: 0;
            padding: 0;
        }

        body {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="ticket centrado">
        <h3>Librería Europea</h2>
        <h4>Ticket de venta No.{{ $factura[0]->facturaid }}</h4>
        <h4>{{date('d/m/Y h:i:sa', strtotime($factura[0]->factura_fecha))}}</h4>
        <br />

        <table>
            <thead>
                <tr class="centrado">
                    <th class="cantidad">CANT</th>
                    <th class="producto">PRODUCTO</th>
                    <th class="precio">SUB</th>
                </tr>
            </thead>
            <tbody>
            @foreach($detalles as $det)
                    <tr>
                        <td class="cantidad">{{number_format($det->detalle_cantidad, 2)}}</td>
                        <td class="producto">{{$det->producto_nombre}}</td>
                        <td class="precio">Q{{number_format($det->detalle_cantidad*$det->detalle_monto, 2)}}</td>
                    </tr>
            @endforeach
            </tbody>
            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>TOTAL</strong>
                </td>
                <td class="precio">
                    Q{{number_format($factura[0]->factura_total, 2)}}
                </td>
            </tr>
        </table>
        <br />
        <p class="centrado">¡Gracias por su compra!
            <br/>Librería Europea</p>
    </div>
</body>

</html>