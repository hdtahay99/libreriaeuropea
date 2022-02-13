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
                        <i class="fa fa-align-justify"></i> Compras
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;NuevaCompra
                        </button>
                    </div>
                    <!-- Listado-->
                    <template v-if="listado==1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="codigo_compra">C&oacute;digo compra</option>
                                        <option value="proveedor_nit">Nit</option>
                                        <option value="compra_fecha">Fecha Compra</option>
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
                                            <th>Proveedor</th>
                                            <th>Nit Proveedor</th>
                                            <th>C&oacute;digo Compra</th>
                                            <th>Total</th>
                                            <th>Saldo Cr&eacute;dito</th>
                                            <th>Fecha Compra</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="compra in arrayCompra" :key="compra.compraid">
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

                //Variables array
                arrayCompra: [], //lista de compras consultadas

                //Filtro
                criterio : '', //cirterio de consulta (codigo, fecha, nit)
                buscar   : '', //valor de busqueda
                fecha    : '', //fecha busqueda

                //Paginacion
                offset     : 3,
                pagination : {
                    'total'        : 0,
                    'current_page' : 0,
                    'per_page'     : 0,
                    'last_page'    : 0,
                    'from'         : 0,
                    'to'           : 0,
                },
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
        },
        methods : {

            listarCompra(page, buscar, criterio)
            {
                let url = this.ruta + '/compra?page=' + page + '&buscar' + buscar + '&criterio=' + criterio;
                
                axios.get(url).then((response) => {
                    let respuesta = response.data;

                    this.arrayCompra = respuesta.compras.data;
                    this.pagination  = respuesta.pagination;
                })
                .catch((error) => {
                    console.log(error);
                });
            },



            cambiarPagina(page,buscar,criterio){
                //Actualiza la pagina actual
                this.pagination.current_page = page;
                //Envia la peticion para visualizar la data de esa página
                this.listarCompra(page,buscar,criterio);
            },

        },
        mounted() {
            this.listarCompra(1,this.buscar,this.criterio);
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
