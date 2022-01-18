<template>
            <main class="main">
            <!-- Breadcrumb -->
             <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/EuropeaWeb/public/main">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ventas
                    </div>
                    <!-- Listado-->
                    <template v-if="listado==1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="facturaid">No. Factura</option>
                                        <option value="cliente_nit">Nit</option>
                                        <option value="factura_fecha">Fecha-Hora</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarFactura(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarFactura(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Empleado</th>
                                            <th>Cliente</th>
                                            <th>Nit Cliente</th>
                                            <th>No. Factura</th>
                                            <th>Total</th>
                                            <th>Total pago</th>
                                            <th>Fecha Hora</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="factura in arrayFactura" :key="factura.facturaid">
                                            <td>
                                                <button type="button" @click="verFactura(factura.facturaid)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                                </button> &nbsp;
                                                <button type="button" @click="pdfVenta(factura.facturaid)" class="btn btn-info btn-sm">
                                                <i class="icon-doc"></i>
                                                </button>
                                            </td>
                                            <td v-text="factura.empleado_nombre + ', ' + factura.empleado_apellido"></td>
                                            <td v-text="factura.cliente_nombre + ', ' + factura.cliente_apellido"></td>
                                            <td v-text="factura.cliente_nit"></td>
                                            <td v-text="factura.facturaid"></td>
                                            <td v-text="factura.factura_total"></td>
                                            <td v-text="factura.factura_pago"></td>
                                            <td v-text="factura.factura_fecha"></td>
                                            <td v-text="factura.factura_estado"></td>
                                        </tr>                                
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                    <!--Fin Listado-->
                    <!-- Ver ingreso -->
                    <template v-else-if="listado==2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Empleado que facturó</label>
                                        <p v-text="empleado"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Cliente</label>
                                        <p v-text="cliente"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Nit</label>
                                    <p v-text="nit"></p>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Dirección</label>
                                        <p v-text="direccion"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <p v-text="fecha"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Número Factura</label>
                                        <p v-text="facturaid"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Monto ingresado a caja</label>
                                        <p v-text="tpago"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Total Factura</label>
                                        <p v-text="total"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="detalle in arrayDetalle" :key="detalle.detalleid">
                                                <td v-text="detalle.producto_nombre">
                                                </td>
                                                <td v-text="detalle.producto_pventa">
                                                </td>
                                                <td v-text="detalle.detalle_cantidad">
                                                </td>
                                                <td>
                                                    {{detalle.producto_pventa*detalle.detalle_cantidad}}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                                <td>Q {{total}}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="4">
                                                    NO hay artículos agregados
                                                </td>
                                            </tr>
                                        </tbody>                                    
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                    
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- fin ver ingreso -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

        </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
        props: ['ruta'],
        data (){
            return {
                facturaid: 0,
                clienteid:0,
                cliente:'',
                empleado: '',
                fecha: '',
                nit: '',
                direccion: '',
                total: 0.0,
                tpago: 0.0,
                factura_total: 0.0,
                factura_pago : 0.0,
                arrayFactura : [],
                arrayCliente: [],
                arrayDetalle : [],
                listado:1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorFactura : 0,
                errorMostrarMsjFactura : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'num_comprobante',
                buscar : '',
                criterioA:'producto_nombre',
                buscarA: '',
                arrayProducto: [],
                productoid: 0,
                producto_barra: '',
                producto_nombre: '',
                producto_pventa: 0,
                producto_existencia : 0,
                cantidad : 0
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].detalle_monto*this.arrayDetalle[i].detalle_cantidad)
                }
                this.factura_total = resultado;
                return resultado;
            }
        },
        methods : {
            listarFactura (page,buscar,criterio){
                let me=this;
                var url= this.ruta + '/factura?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayFactura = respuesta.facturas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            pdfVenta(id){
                window.open(this.ruta + '/factura/pdf/'+id+','+'_blank');
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarFactura(page,buscar,criterio);
            },

            ocultarDetalle(){
                this.listado=1;
                this.facturaid = 0;
            },
            verFactura(id){
                let me=this;
                me.listado=2;
                me.facturaid = id;
                
                //Obtener los datos del ingreso
                var arrayFacturaT=[];
                var url= this.ruta + '/factura/obtenerCabecera?facturaid=' + id;
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayFacturaT = respuesta.factura;

                    me.cliente = arrayFacturaT[0]['nombre'];
                    me.empleado = arrayFacturaT[0]['enombre'];
                    me.nit = arrayFacturaT[0]['cliente_nit'];
                    me.direccion = arrayFacturaT[0]['cliente_direccion'];
                    me.tpago = arrayFacturaT[0]['factura_pago'];
                    me.total = arrayFacturaT[0]['factura_total'];
                    me.fecha = arrayFacturaT[0]['factura_fecha'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
                var urld= this.ruta + '/factura/obtenerDetalles?facturaid=' + id;
                
                axios.get(urld).then(function (response) {
                    console.log(response);
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });               
            },
        },
        mounted() {
            this.listarFactura(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: fixed !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>
