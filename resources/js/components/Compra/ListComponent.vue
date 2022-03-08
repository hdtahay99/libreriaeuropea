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
                        <button type="button" @click="nuevaCompra()" class="btn btn-secondary">
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
                                        <input type="text" v-model="buscar" @keyup.enter="listarCompra(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarCompra(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                            <th>Cr&eacute;dito</th>
                                            <th>Fecha Compra</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="compra in arrayCompra" :key="compra.compraid">
                                            <td>
                                                <button type="button" @click="verCompra(compra.codigo_compra)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                                </button> &nbsp;
                                                <button type="button" @click="pdfCompra(compra.codigo_compra)" class="btn btn-info btn-sm">
                                                <i class="icon-doc"></i>
                                                </button> &nbsp;
                                                <template v-if="compra.estado==1">
                                                    <button type="button" class="btn btn-danger btn-sm" @click="anularCompra(compra.codigo_compra)">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </template>
                                            </td>
                                            <td v-text="compra.empleado_nombre + ', ' + compra.empleado_apellido"></td>
                                            <td v-text="compra.proveedor_nombre"></td>
                                            <td v-text="compra.proveedor_nit"></td>
                                            <td v-text="compra.codigo_compra"></td>
                                            <td v-text="compra.compra_total"></td>
                                            <template v-if="compra.compra_estado == 1">
                                                <td class="btn-info">Cr&eacute; Activo</td>
                                            </template>
                                            <template v-if="compra.compra_estado == 0">
                                                <td class="btn-success">Sin cr&eacute;dito</td>
                                            </template>
                                            <td v-text="compra.compra_fecha"></td>
                                            <template v-if="compra.estado == 1">
                                                <td class="btn-success">Activo</td>
                                            </template>
                                            <template v-if="compra.estado == 0">
                                                <td class="btn-danger">Anulado</td>
                                            </template>
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
                    <!-- Ver ingreso 
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
                    fin ver ingreso -->
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

                // Variables globales
                listado: 1,

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
            nuevaCompra()
            {
                
            },
            verCompra(codigo_compra)
            {
                return 1;
            },
            pdfCompra(codigo_compra)
            {
                return 1;
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
            this.listado = 1;
            this.criterio = 'codigo_compra';
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
