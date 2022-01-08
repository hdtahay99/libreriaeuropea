<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de venta - Librería Europea</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }

        #logo{
        float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }

        #imagen{
        width: 100px;
        }

        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }

        #encabezado{
        text-align: center;
        margin-left: 10%;
        margin-right: 35%;
        font-size: 15px;
        }

        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }

        section{
        clear: left;
        }

        #cliente{
        text-align: left;
        }

        #facliente{
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #de{
            text-align: center;
            font-size: 15px;
        }

        #facliente thead{
        padding: 20px;
        background: #2183E3;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facarticulo thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #gracias{
        text-align: center; 
        }
    </style>
    <body>
        @foreach ($factura as $v)
        <header>
            <div id="logo">
                <img src="img/FF.png" alt="logoLibrería" id="imagen">
            </div>
            <div id="datos">
                <p id="encabezado">
                    <b>Librería Europea</b><br> Sololá, Sololá<br>Telefono:(+502)56449800<br>Email:info@libreriaeuropea.com
                </p>
            </div>
            <div id="fact">
                <p>Factura<br>
                No.{{ $v->facturaid }}</p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            <th id="fac">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><p id="cliente">Sr(a). {{ $v->nombre }}<br>
                            Dirección: {{ $v->cliente_direccion }}<br>
                            Nit: {{ $v->cliente_nit }}<br>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
            <div>
                <table id="facvendedor">
                    <thead>
                        <tr id="fv">
                            <th>VENDEDOR</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="de">
                            <td>{{ $v->enombre }}</td>
                            <td>{{ $v->factura_fecha }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        @endforeach
        <section>
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Precio Unit</th>
                            <th>Monto Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $det)
                        <tr id="de">
                            <td>{{ $det->detalle_cantidad }}</td>
                            <td>{{ $det->producto_nombre }}</td>
                            <td>Q {{ $det->detalle_monto }}</td>
                            <td>Q {{$det->detalle_cantidad*$det->detalle_monto}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @foreach($factura as $fac)
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <td>Q {{ $fac->factura_total }}</td>
                        </tr>
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
            <div id="gracias">
                <p><b>Librería-Europea, gracias por la compra</b></p>
            </div>
        </footer>
    </body>
</html>