<template onload="onLoadBody">
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/WebEuropea/public">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ventas
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
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
                                                </button> &nbsp;
                                                <template v-if="factura.condicion==1">
                                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarFactura(factura.facturaid)">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </template>
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
                    <!-- Detalle-->
                    <template v-else-if="listado==0">
                        <div class="card-body">
                            <div class="form-group row border">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Cliente(*)</label>
                                        <div class="form-inline">
                                            <input type="text" style="width: 50%" id="nit" v-model="cliente_nit" @keyup="buscarCliente(cliente_nit)" class="form-control" placeholder="Ingrese el nit del cliente"><br>
                                            <button v-if="editar==0" type="submit" @click="modificarCF()" class="btn btn-warning"><i class="icon-pencil"></i> </button>
                                            <button v-if="editar==1" type="submit" @click="resetCF()" class="btn btn-primary"><i class="icon-action-undo"></i> </button>
                                        </div>
                                        <br>
                                        <input :hidden="bandera ? true : false" :disabled="bandera ? true : false" type="text" style="width: 50%" id="cnombre" v-model="cliente_nombre"  class="form-control" placeholder="Ingrese el nombre del cliente"><br>
                                        <input :hidden="bandera ? true : false" :disabled="bandera ? true : false" type="text" style="width: 50%" id="capellido" v-model="cliente_apellido"  class="form-control" placeholder="Ingrese el apellido del cliente"><br>
                                        <input :hidden="bandera ? true : false" :disabled="bandera ? true : false" type="text" style="width: 50%" id="cdireccion" v-model="cliente_direccion"  class="form-control" placeholder="Ingrese la dirección del cliente">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div v-show="errorFactura" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjFactura" :key="error" v-text="error">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Producto <span style="color:red;" v-show="productoid==0">(*Seleccione)</span></label>
                                        <div class="form-inline">
                                            <input id="buscarpro" type="text" class="form-control" v-model="producto_barra" v-on:input="buscarProducto" placeholder="Ingrese producto">
                                            <button @click="abrirModal(1)"  class="btn btn-primary">...</button>
                                            <input type="text" style="width: 50%" readonly class="form-control" v-model="producto_nombre">
                                        </div>                                    
                                    </div>
                                </div>
                                <!--
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Precio <span style="color:red;" v-show="producto_pventa==0">(*Ingrese)</span></label>
                                        <input type="number" value="0" min="0" step=".01" class="form-control" v-model="producto_pventa">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Cantidad <span style="color:red;" v-show="cantidad==0">(*Ingrese)</span></label>
                                        <input type="number" value="0" class="form-control" v-model="cantidad">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                                -->
                            </div>
                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.detalleid">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detalle.producto_nombre">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-show="detalle.detalle_monto <= 0">No puede ser posible el monto ingresado</span>
                                                    <input v-model="detalle.detalle_monto" step="any" type="number"  value="3" class="form-control">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-show="detalle.detalle_cantidad>detalle.producto_existencia">Existencia: {{detalle.producto_existencia}}</span>
                                                    <input v-model="detalle.detalle_cantidad"  type="number" value="2" class="form-control">
                                                </td>
                                                <td>
                                                    {{detalle.detalle_monto*detalle.detalle_cantidad}}
                                                </td>
                                            </tr>

                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                                <td>Q {{total=calcularTotal}}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5">
                                                    No hay productos agregados al detalle
                                                </td>
                                            </tr>
                                        </tbody>                                    
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="abrirModal(2)">Registrar venta</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- Fin Detalle-->
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
                                                <th>Precio Inventario</th>
                                                <th>Precio Venta</th>
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
                                                <td v-text="detalle.detalle_monto">
                                                </td>
                                                <td v-text="detalle.detalle_cantidad">
                                                </td>
                                                <td>
                                                    {{detalle.detalle_monto*detalle.detalle_cantidad}}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Neto:</strong></td>
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
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" id="myModal1" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">x</span>
                            </button>
                        </div>

                        <template v-if="modal == 1">
                            <div onfocus="enfocar()" class="modal-body">
                                <div class="form-group row">
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <select style="width: 50%" class="form-control col-md-4" v-model="criterioA">
                                            <option value="producto_nombre">Nombre</option>
                                            <option value="producto_barra">Código</option>
                                            </select>
                                            <input id="textareaID1" style="width: 100%" type="text" v-model="buscarA" @keyup="listarProducto(1,buscarA,criterioA)" class="form-control" placeholder="Ingrese el nombre del producto o su código">
                                            <button type="submit" @click="listarProducto(1,buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Categoría</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Visualización</th>
                                                <th>Precio Venta</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="producto in arrayProducto" :key="producto.productoid">
                                                <td>
                                                    <button type="button" @click="agregarDetalleModal(producto)" class="btn btn-success btn-sm">
                                                    <i class="icon-check"></i>
                                                    </button>
                                                </td>
                                                <td v-text="producto.categoria_nombre"></td>
                                                <td v-text="producto.producto_barra"></td>
                                                <td v-text="producto.producto_nombre"></td>
                                                <td><img width="100" height="100" v-bind:src="'uploads/'+producto.producto_imagen" /></td>
                                                <td v-text="producto.producto_pventa"></td>
                                                <td v-text="producto.producto_existencia"></td>
                                            </tr>                                
                                        </tbody>
                                    </table>
                                </div>
                                <nav>
                                    <ul class="pagination">
                                        <li class="page-item" v-if="paginationM.current_page > 1">
                                            <a class="page-link" href="#" @click.prevent="cambiarPaginaM(paginationM.current_page - 1,buscarA,criterioA)">Ant</a>
                                        </li>
                                        <li class="page-item" v-for="page in pagesNumberM" :key="page" :class="[page == isActivedM ? 'active' : '']">
                                            <a class="page-link" href="#" @click.prevent="cambiarPaginaM(page,buscarA,criterioA)" v-text="page"></a>
                                        </li>
                                        <li class="page-item" v-if="paginationM.current_page < paginationM.last_page">
                                            <a class="page-link" href="#" @click.prevent="cambiarPaginaM(paginationM.current_page + 1,buscarA,criterioA)">Sig</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                            </div>
                        </template>

                        <template v-else-if="modal == 2">
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Monto:</label>
                                    <div class="col-md-9">
                                        <input id="InMonto" type="number" step="any" v-model="factura_pago" @keyup.enter="registrarFactura(factura_pago)" class="form-control" placeholder="Ingrese el monto">
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
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
                consumidor_final: null,
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
                paginationM : {
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
                cantidad : 0,
                cliente_nit : '',
                cliente_direccion: '',
                cliente_nombre: '',
                cliente_apellido: '',
                editar: 0,
                bandera: null,
                bandera2: null, 
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            
            isActivedM: function(){
                return this.paginationM.current_page;
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

            pagesNumberM: function() {
                if(!this.paginationM.to) {
                    return [];
                }
                
                var from = this.paginationM.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.paginationM.last_page){
                    to = this.paginationM.last_page;
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
            selectCliente(search,loading){
                let me=this;
                loading(true)

                var url= this.ruta +  '/cliente/selectCliente?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayCliente=respuesta.clientes;
                    me.consumidor_final = me.arrayCliente.find(clientes => clientes.cliente_nit === "c/f");
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            buscarCliente(nit){
                let me = this;

                var url = this.ruta + '/cliente/buscarCliente/?cliente_nit='+nit;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    if (response.data.cliente.length != 0){
                        me.cliente_nombre = respuesta.cliente[0].cliente_nombre;
                        me.cliente_apellido = respuesta.cliente[0].cliente_apellido;
                        me.cliente_direccion = respuesta.cliente[0].cliente_direccion;
                        me.clienteid = respuesta.cliente[0].clienteid;
                        me.bandera = true;

                    }
                    else {
                        me.cliente_nombre = '';
                        me.cliente_apellido = '';
                        me.cliente_direccion = '';
                        me.clienteid = 0;
                        me.bandera = false;
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            pdfVenta(id){
                window.open(this.ruta + '/factura/pdf/'+id+','+'_blank');
            },
            getDatosCliente(val1){
                let me = this;
                me.loading = true;
                me.clienteid = val1.clienteid;
            },
            buscarProducto(){
                let me=this;

                if(!me.producto_barra.length)
                {
                    return;
                }

                var url= this.ruta + '/producto/buscarProductoVenta?filtro=' + me.producto_barra;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos;

                    console.log(me.arrayProducto);

                    if (me.arrayProducto.length>0){
                        me.producto_nombre=me.arrayProducto[0]['producto_nombre'];
                        me.productoid=me.arrayProducto[0]['productoid'];
                        me.producto_pventa = me.arrayProducto[0]['producto_pventa']
                        me.producto_existencia = me.arrayProducto[0]['producto_existencia'];
                        me.cantidad = 1;
                        me.agregarDetalle();
                    }
                    else{
                        me.producto_nombre='No existe artículo';
                        me.productoid=0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarFactura(page,buscar,criterio);
            },
            cambiarPaginaM(page,buscarA,criterioA){
                let me = this;
                //Actualiza la página actual
                me.paginationM.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarProducto(page,buscarA,criterioA);
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].productoid==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },
            agregarDetalle(){
                let me=this;
                if(me.productoid==0 || me.cantidad==0 || me.producto_pventa==0){
                }
                else{
                    if(me.encuentra(me.productoid)){

                        // Definir índice
                        let idx = -1;

                        // Buscar índice del producto existente
                        idx = me.arrayDetalle.findIndex(p => p.productoid ===  me.productoid);

                        if(idx >= 0)
                        {
                            if(parseInt(me.arrayDetalle[idx].detalle_cantidad) < me.producto_existencia){
                                me.arrayDetalle[idx].detalle_cantidad = parseInt(me.arrayDetalle[idx].detalle_cantidad) + 1;
                            }
                            else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Ya no hay más existencia para la venta',
                                })
                            }
                        }

                        me.producto_barra  = '';
                        me.producto_nombre = '';
                        document.getElementById('buscarpro').focus();
                    }
                    else{
                        if(me.cantidad > me.producto_existencia){
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: '¡No hay existencia disponible para la venta!',
                            })
                        }
                        else{
                            me.arrayDetalle.push({
                                productoid: me.productoid,
                                producto_nombre: me.producto_nombre,
                                detalle_cantidad: me.cantidad,
                                detalle_monto: me.producto_pventa,
                                producto_existencia: me.producto_existencia
                            });
                            me.producto_barra="";
                            me.productoid=0;
                            me.producto_nombre="";
                            me.producto_existencia=0;
                            me.cantidad = 0;
                            me.producto_pventa=0; 
                        }
                    }
                    
                }
            },
            agregarDetalleModal(data =[]){
                let me=this;
                if(me.encuentra(data['productoid'])){
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: '¡Este producto ya ha sido agregado!',
                        })
                    }
                    else{
                       me.arrayDetalle.push({
                            productoid: data['productoid'],
                            producto_nombre: data['producto_nombre'],
                            detalle_cantidad: 1,
                            detalle_monto: data['producto_pventa'],
                            producto_existencia: data['producto_existencia']
                        }); 
                    }
            },
            listarProducto(page,buscar,criterio){
                if(parseInt(buscar)){
                    criterio = 'producto_barra';
                }
                else if(criterio == 'producto_nombre' && typeof buscar == 'string')
                {
                    criterio = 'producto_nombre';
                }
                let me=this;
                var url= this.ruta + '/producto/listarProductoVenta?buscar='+ buscar + '&criterio='+ criterio+'&page=' +page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos.data;
                    me.paginationM= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error.response);
                });
            },
            registrarFactura(pago){
                if (this.factura_total > pago){
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: '¡La cantidad debe ser igual al total o mayor!',
                        })
                }
                else{
                    Swal.fire({
                    position: 'top-center',
                    type: 'success',
                    title: 'El vuelto es de: '+ (pago - this.factura_total),
                    showConfirmButton: false,
                    timer: 4200
                    })
                    this.modal = 0;
                    
                    let me = this;

                    if (me.editar == 1){                    
                        axios.post(this.ruta + '/factura/registrar2', {
                            'cliente_nit': this.cliente_nit,
                            'cliente_nombre': this.cliente_nombre,
                            'cliente_apellido': this.cliente_apellido,
                            'cliente_direccion': this.cliente_direccion,
                            'factura_total': this.factura_total,
                            'factura_pago' : this.factura_pago,
                            'data': this.arrayDetalle
                        }).then(function (response) {
                            me.listado=0;
                            me.editar = 0;
                            me.bandera = true;
                            me.listarFactura(1,'','');
                            me.clienteid=0;
                            me.cliente_nombre = '';
                            me.cliente_apellido = '';
                            me.cliente_direccion = '';
                            me.cliente_nit = 'c/f';
                            me.buscarCliente(this.cliente_nit);
                            document.getElementById('nit').readOnly = false;
                            me.factura_total=0.0;
                            me.factura_pago=0.0;
                            me.productoid=0;
                            me.producto_nombre='';
                            me.cantidad=0;
                            me.producto_existencia=0;
                            me.producto_pventa = 0;
                            me.producto_barra = '';
                            me.arrayDetalle=[];
                            window.open(me.ruta + '/factura/pdf/'+response.data.facturaid);
                            document.getElementById('buscarpro').focus();
                        }).catch(function (error){
                            console.log(error.data);
                        });
                    } else {
                        axios.post(this.ruta + '/factura/registrar',{
                            'clienteid': this.clienteid,
                            'factura_total': this.factura_total,
                            'factura_pago' : this.factura_pago,
                            'data': this.arrayDetalle

                        }).then(function (response) {
                            me.listado=0;
                            me.listarFactura(1,'','');
                            me.clienteid=0;
                            me.factura_total=0.0;
                            me.factura_pago=0.0;
                            me.productoid=0;
                            me.producto_nombre='';
                            me.cantidad=0;
                            me.producto_existencia=0;
                            me.producto_pventa = 0;
                            me.producto_barra = '';
                            me.arrayDetalle=[];
                            window.open(me.ruta + '/factura/pdf/'+response.data.facturaid);
                            document.getElementById('buscarpro').focus();
                            me.cliente_nit = 'c/f';
                            me.buscarCliente(me.cliente_nit);

                        }).catch(function (error) {
                            alert(error);
                        });
                    }
                }
            },
            validarFactura(){
                let me = this;
                this.errorFactura=0;
                this.errorMostrarMsjFactura =[];

                var producto;

                me.arrayDetalle.map(function(x){
                    if(x.detalle_cantidad > x.producto_existencia){
                        producto = x.producto_nombre + " con stock insuficiente";
                        me.errorMostrarMsjFactura.push(producto);
                    }
                });

                if (this.clienteid==0) this.errorMostrarMsjFactura.push("Es necesario seleccionar un cliente para la venta.");
                if (this.arrayDetalle.length<=0) this.errorMostrarMsjFactura.push("Ingrese productos al detalle.");

                if (this.errorMostrarMsjFactura.length) this.errorFactura = 1;

                return this.errorFactura;
            },
            modificarCF(){
                let me = this;
                me.editar = 1;
                me.cliente_nombre = '';
                me.cliente_apellido = '';
                me.cliente_direccion = '';
                document.getElementById('nit').readOnly = true;
                me.bandera = false;
            },
            resetCF(){
                let me = this;
                me.editar = 0;
                me.cliente_nombre = '';
                me.cliente_apellido = '';
                me.cliente_direccion = '';
                document.getElementById('nit').readOnly = false;
                me.bandera = true;
                me.cliente_nit = 'c/f';
            },
            mostrarDetalle(){
                let me=this;
                me.listado=0;
                me.bandera2 =true;
                me.idproveedor=0;
                me.total=0.0;
                me.idarticulo=0;
                me.articulo='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
                me.focusInp = true;

                setTimeout(() => {
                    document.getElementById("buscarpro").focus();
                }, 10);
            },
            ocultarDetalle(){
                this.listado=1;
                this.facturaid = 0;
                this.editar = 0;
                this.bandera = true;
                this.listarFactura(1,'','');
                this.clienteid=0;
                this.cliente_nombre = '';
                this.cliente_apellido = '';
                this.cliente_direccion = '';
                this.cliente_nit = 'c/f';
                this.buscarCliente(this.cliente_nit);
                document.getElementById('nit').readOnly = false;
                this.factura_total=0.0;
                this.factura_pago=0.0;
                this.productoid=0;
                this.producto_nombre='';
                this.cantidad=0;
                this.producto_existencia=0;
                this.producto_pventa = 0;
                this.producto_barra = '';
                this.arrayDetalle=[];
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
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            },
            abrirModal(condicion){
                if(condicion == 1){
                    this.arrayProducto=[];
                    this.modal = 1;
                    this.tituloModal = 'Seleccione uno o varios productos';
                    this.listarProducto(1,'','');
                    this.buscarA = '';
                    setTimeout(() => {
                        document.getElementById('textareaID1').focus();
                    }, 10);
                }
                else{
                    if (this.validarFactura()){
                        return;
                    }
                    this.modal = 2;
                    this.tituloModal = 'Ingrese el monto recibido por el cliente';
                    this.factura_pago = '';
                    setTimeout(() => {
                        document.getElementById('InMonto').focus();
                    }, 10);
                }               
            },

            desactivarFactura(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: '¿Está seguro de anular esta factura?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.put(this.ruta + '/factura/desactivar',{
                        'facturaid' : id
                    }).then(function (response){
                        me.listarFactura(1,'','nombre');
                                            
                        swalWithBootstrapButtons.fire(
                        'La Factura ha sido desactivada',
                        )
                    }).catch(function (error){
                        console.log(error);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            }
        },
        mounted() {
            this.listarFactura(1,this.buscar,this.criterio);
            this.cliente_nit = 'c/f';
            this.buscarCliente(this.cliente_nit);
        }
    }
</script>
<style>
    .modal{
        overflow-y: auto;
    }    
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
