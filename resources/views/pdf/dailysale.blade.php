
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
        <h4>Reporte de Venta</h4>
        <h4>{{date('d/m/Y h:i:sa', strtotime($fecha))}}</h4>
        <br />

        <table>
            <thead>
                <tr class="centrado">
                    <th class="producto">Descripci&oacute;n</th>
                    <th class="precio">Total</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td class="producto">Venta Total</td>
                        <td class="precio">Q{{number_format($ventas[0]->total, 2)}}</td>
                    </tr>
            </tbody>
        </table>
        <br />
        <p class="centrado"><br/>Librería Europea</p>
    </div>
</body>

</html>